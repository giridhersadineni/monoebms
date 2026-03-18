<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MigrateLegacyData extends Command
{
    protected $signature = 'ebms:migrate-legacy
                            {--dry-run : Preview changes without writing to DB}
                            {--chunk=500 : Rows per chunk for large tables}
                            {--table=all : Which table to migrate (all|subjects|exams|students|admin_users|enrollments|results|gpas|grades)}
                            {--exam-id= : Restrict results migration to a single legacy EXAMID}';

    protected $description = 'Migrate legacy EBMS data from the legacy database to the new schema';

    private bool $dryRun = false;
    private int $chunk   = 500;
    private string $table = 'all';

    // In-memory caches to avoid repeated DB lookups
    private array $examMap    = [];   // legacy EXID → new exams.id
    private array $subjectMap = [];   // PAPERCODE → subjects.id
    private array $studentMap = [];   // legacy_stid → students.id
    private array $htStudentMap = []; // hall_ticket → students.id
    private array $enrollmentMap = []; // "hall_ticket_examId" → exam_enrollments.id

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $this->chunk  = (int) $this->option('chunk');
        $this->table  = $this->option('table');

        if ($this->dryRun) {
            $this->warn('[DRY RUN] No data will be written.');
        }

        $steps = [
            'subjects'    => fn () => $this->migrateSubjects(),
            'exams'       => fn () => $this->migrateExams(),
            'students'    => fn () => $this->migrateStudents(),
            'admin_users' => fn () => $this->migrateAdminUsers(),
            'enrollments' => fn () => $this->migrateEnrollments(),
            'results'     => fn () => $this->migrateResults(),
            'gpas'        => fn () => $this->migrateGpas(),
            'grades'      => fn () => $this->migrateGrades(),
        ];

        if ($this->table !== 'all' && ! array_key_exists($this->table, $steps)) {
            $this->error("Unknown table: {$this->table}");
            return self::FAILURE;
        }

        $toRun = ($this->table === 'all') ? $steps : [$this->table => $steps[$this->table]];

        foreach ($toRun as $name => $fn) {
            $this->info("Migrating: {$name}...");
            try {
                $fn();
                $this->info("  ✓ {$name} done");
            } catch (\Throwable $e) {
                $this->error("  ✗ {$name} failed: {$e->getMessage()}");
                Log::channel('single')->error("Migration failed [{$name}]: {$e->getMessage()}", ['exception' => $e]);
            }
        }

        return self::SUCCESS;
    }

    private function legacy(): \Illuminate\Database\ConnectionInterface
    {
        return DB::connection('legacy');
    }

    // ─── Subjects ────────────────────────────────────────────────────────────

    private function migrateSubjects(): void
    {
        $this->legacy()->table('allpapers')->orderBy('ID')->chunk($this->chunk, function ($rows) {
            foreach ($rows as $row) {
                $data = [
                    'code'           => $row->PAPERCODE,
                    'name'           => $row->PAPERNAME,
                    'course'         => $row->COURSE ?? '',
                    'group_code'     => $row->GROUPCODE ?? null,
                    'medium'         => $row->MEDIUM ?? null,
                    'semester'       => $row->SEM ?? 1,
                    'paper_type'     => strtolower($row->PAPERTYPE ?? '') === 'elective' ? 'elective' : 'compulsory',
                    'elective_group' => $row->ELECTIVEGROUP ?? null,
                    'part'           => $row->PART ?? 1,
                    'scheme'         => $row->SCHEME ?? null,
                ];
                if (! $this->dryRun) {
                    DB::table('subjects')->upsert($data, ['code'], array_keys($data));
                } else {
                    $this->line("  [subjects] Would upsert: {$row->PAPERCODE} — {$row->PAPERNAME}");
                }
            }
        });
    }

    // ─── Exams ───────────────────────────────────────────────────────────────

    private function migrateExams(): void
    {
        $courseKeys = ['BA', 'BCOM', 'BSC', 'BCOMCA', 'BAHONS'];

        $this->legacy()->table('examsmaster')->orderBy('EXID')->chunk($this->chunk, function ($rows) use ($courseKeys) {
            foreach ($rows as $row) {
                $feeJson  = [];
                foreach ($courseKeys as $course) {
                    $regular = $row->{$course . '_FEE'} ?? $row->FEE ?? 0;
                    $above2  = $row->{$course . '_ABOVE_2'} ?? $regular;
                    $feeJson[$course] = ['regular' => (int) $regular, 'above_2_sem' => (int) $above2];
                }

                $status   = strtolower($row->STATUS ?? 'open') === 'close' ? 'closed' : strtolower($row->STATUS ?? 'open');
                $examType = match (strtolower($row->EXAMTYPE ?? 'regular')) {
                    'supply', 'supplementary' => 'supplementary',
                    'revaluation'             => 'revaluation',
                    'improvement'             => 'improvement',
                    default                   => 'regular',
                };

                $data = [
                    'name'             => $row->EXAMNAME,
                    'semester'         => $row->SEMESTER ?? 1,
                    'course'           => $row->COURSE ?? '',
                    'exam_type'        => $examType,
                    'month'            => $row->MONTH ?? null,
                    'year'             => (int) ($row->YEAR ?? date('Y')),
                    'status'           => in_array($status, ['open', 'closed', 'cancelled']) ? $status : 'closed',
                    'scheme'           => $row->SCHEME ?? null,
                    'revaluation_open' => (bool) ($row->REVALOPEN ?? false),
                    'fee_json'         => json_encode($feeJson),
                ];

                if (! $this->dryRun) {
                    DB::table('exams')->upsert($data, ['name', 'semester', 'year', 'course'], array_keys($data));
                } else {
                    $this->line("  [exams] Would upsert: {$row->EXAMNAME} ({$row->YEAR})");
                }
            }
        });
    }

    // ─── Students ────────────────────────────────────────────────────────────

    private function migrateStudents(): void
    {
        $this->legacy()->table('students')->orderBy('stid')->chunk($this->chunk, function ($rows) {
            foreach ($rows as $row) {
                $dob = null;
                if (! empty($row->dob)) {
                    $parsed = \DateTime::createFromFormat('d/m/Y', $row->dob)
                        ?? \DateTime::createFromFormat('Y-m-d', $row->dob)
                        ?? null;
                    $dob = $parsed?->format('Y-m-d');
                }

                $data = [
                    'hall_ticket'     => $row->haltckt,
                    'dob'             => $dob,
                    'dost_id'         => $row->dostid ?? null,
                    'name'            => $row->sname ?? '',
                    'father_name'     => $row->fname ?? null,
                    'mother_name'     => $row->mname ?? null,
                    'email'           => $row->email ?? null,
                    'phone'           => $row->phone ?? null,
                    'aadhaar'         => $row->aadhar ?? null,
                    'gender'          => $row->gender ?? null,
                    'caste'           => $row->caste ?? null,
                    'sub_caste'       => $row->subcaste ?? null,
                    'course'          => $row->course ?? null,
                    'course_name'     => $row->course_name ?? null,
                    'group_code'      => $row->group ?? null,
                    'medium'          => $row->medium ?? null,
                    'semester'        => $row->sem ?? null,
                    'admission_year'  => $row->curryear ?? null,
                    'scheme'          => $row->SCHEME ?? null,
                    'address'         => $row->address ?? null,
                    'address2'        => $row->address2 ?? null,
                    'mandal'          => $row->mandal ?? null,
                    'city'            => $row->city ?? null,
                    'state'           => $row->state ?? null,
                    'pincode'         => $row->pincode ? (string) $row->pincode : null,
                    'apaar_id'        => $row->apaar_id ?? null,
                    'ssc_hall_ticket' => $row->ssc_hallticket ? (string) $row->ssc_hallticket : null,
                    'is_active'       => true,
                    'legacy_stid'     => $row->stid,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                if (! $this->dryRun) {
                    DB::table('students')->upsert($data, ['hall_ticket'], array_keys($data));
                } else {
                    $this->line("  [students] Would upsert: {$row->haltckt} — {$row->sname}");
                }
            }
        });
    }

    // ─── Admin Users ─────────────────────────────────────────────────────────

    private function migrateAdminUsers(): void
    {
        $this->legacy()->table('admin')->orderBy('id')->chunk($this->chunk, function ($rows) {
            foreach ($rows as $row) {
                $data = [
                    'login_id'   => $row->username,
                    'name'       => $row->username,
                    'password'   => Hash::make($row->password),
                    'role'       => 'admin',
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (! $this->dryRun) {
                    DB::table('admin_users')->upsert($data, ['login_id'], array_keys($data));
                } else {
                    $this->line("  [admin_users] Would upsert: {$row->username}");
                }
            }
        });
    }

    // ─── Enrollments ─────────────────────────────────────────────────────────

    private function migrateEnrollments(): void
    {
        $subjectColumns  = ['S1','S2','S3','S4','S5','S6','S7','S8','S9','S10'];
        $electiveColumns = ['E1','E2','E3','E4','E5'];

        $this->refreshMaps();

        $inserted = 0;
        $created  = 0; // enrollments created from missing students/exams

        $examIdFilter = $this->option('exam-id') ? (int) $this->option('exam-id') : null;

        $enrollQuery = $this->legacy()->table('examenrollments')->orderBy('ID');
        if ($examIdFilter !== null) {
            $enrollQuery->where('EXAMID', $examIdFilter);
            $this->line("  (filtering EXAMID = {$examIdFilter})");
        }

        $enrollQuery->chunk($this->chunk, function ($rows) use (
            $subjectColumns, $electiveColumns, &$inserted, &$created
        ) {
            foreach ($rows as $row) {
                // Resolve student — create if missing
                $studentId = $this->studentMap[$row->STUDENTID] ?? null;
                if (! $studentId) {
                    $studentId = $this->ensureStudent($row->HALLTICKET);
                    if ($studentId) {
                        $this->studentMap[$row->STUDENTID] = $studentId;
                        $this->htStudentMap[$row->HALLTICKET] = $studentId;
                    }
                }

                // Resolve exam — create if missing
                $examId = $this->examMap[$row->EXAMID] ?? null;
                if (! $examId) {
                    $examId = $this->ensureExam($row->EXAMID);
                    if ($examId) {
                        $this->examMap[$row->EXAMID] = $examId;
                    }
                }

                if (! $studentId || ! $examId) {
                    Log::channel('single')->warning("Enrollment {$row->ID}: unresolvable student={$row->STUDENTID} exam={$row->EXAMID}. Skipping.");
                    continue;
                }

                // Skip if already migrated
                $eKey = "{$row->HALLTICKET}_{$examId}";
                if (isset($this->enrollmentMap[$eKey])) {
                    continue;
                }

                $safeDate = fn ($v) => (! $v || str_starts_with((string) $v, '0000')) ? null : $v;
                $feePaidAt = $row->FEEPAID == 1 ? ($safeDate($row->CHALLANSUBMITTEDON) ?? now()) : null;

                $enrollData = [
                    'exam_id'              => $examId,
                    'student_id'           => $studentId,
                    'hall_ticket'          => $row->HALLTICKET,
                    'exam_type'            => $row->EXTYPE ?? 'regular',
                    'fee_amount'           => (int) ($row->FEEAMOUNT ?? 0),
                    'fee_paid_at'          => $feePaidAt,
                    'challan_number'       => $row->TXNID ?? null,
                    'challan_submitted_on' => $safeDate($row->CHALLANSUBMITTEDON),
                    'challan_received_by'  => $row->CHALLANRECBY ?? null,
                    'enrolled_at'          => $safeDate($row->ENROLLEDDATE) ?? now(),
                    'created_at'           => $safeDate($row->ENROLLEDDATE) ?? now(),
                    'updated_at'           => now(),
                ];

                if ($this->dryRun) {
                    $this->line("  [enrollments] Would insert {$row->HALLTICKET}");
                    continue;
                }

                $enrollmentId = DB::table('exam_enrollments')->insertGetId($enrollData);
                $this->enrollmentMap[$eKey] = $enrollmentId;
                $inserted++;

                foreach ($subjectColumns as $col) {
                    $code = $row->{$col} ?? null;
                    if ($code && $code !== 'null') {
                        $subjectId = $this->subjectMap[$code] ?? null;
                        if ($subjectId) {
                            DB::table('exam_enrollment_subjects')->insertOrIgnore([
                                'enrollment_id' => $enrollmentId,
                                'subject_id'    => $subjectId,
                                'subject_code'  => $code,
                                'subject_type'  => 'regular',
                            ]);
                        }
                    }
                }

                foreach ($electiveColumns as $col) {
                    $code = $row->{$col} ?? null;
                    if ($code && $code !== 'null') {
                        $subjectId = $this->subjectMap[$code] ?? null;
                        if ($subjectId) {
                            DB::table('exam_enrollment_subjects')->insertOrIgnore([
                                'enrollment_id' => $enrollmentId,
                                'subject_id'    => $subjectId,
                                'subject_code'  => $code,
                                'subject_type'  => 'elective',
                            ]);
                        }
                    }
                }
            }
        });

        $this->line("  enrollments: {$inserted} inserted");
    }

    // ─── Results ─────────────────────────────────────────────────────────────

    private function migrateResults(): void
    {
        $this->refreshMaps();

        $inserted = 0;
        $skipped  = 0;
        $seen     = []; // dedup (enrollment_id, subject_id) within this run

        $examIdFilter = $this->option('exam-id') ? (int) $this->option('exam-id') : null;

        $query = $this->legacy()->table('RESULTS')->orderBy('RHID');
        if ($examIdFilter !== null) {
            $query->where('EXAMID', $examIdFilter);
            $this->line("  (filtering EXAMID = {$examIdFilter})");
        }

        $query->chunk($this->chunk, function ($rows) use (&$inserted, &$skipped, &$seen) {
            $batch = [];

            foreach ($rows as $row) {
                // Must have a valid hall ticket and paper code
                if (! $row->HALLTICKET || ! $row->PAPERCODE) {
                    $skipped++;
                    continue;
                }

                // Resolve exam
                $examId = $this->examMap[$row->EXAMID] ?? null;
                if (! $examId && $row->EXAMID) {
                    $examId = $this->ensureExam($row->EXAMID);
                    if ($examId) {
                        $this->examMap[$row->EXAMID] = $examId;
                    }
                }
                if (! $examId) {
                    $skipped++;
                    continue;
                }

                // Resolve subject — create from RESULTS if missing
                $subjectId = $this->subjectMap[$row->PAPERCODE] ?? null;
                if (! $subjectId) {
                    $subjectId = $this->ensureSubject($row->PAPERCODE, $row->PAPERNAME, $row->SEMESTER, $row->PART);
                    if ($subjectId) {
                        $this->subjectMap[$row->PAPERCODE] = $subjectId;
                    }
                }
                if (! $subjectId) {
                    $skipped++;
                    continue;
                }

                // Resolve enrollment — create if missing
                $eKey = "{$row->HALLTICKET}_{$examId}";
                $enrollmentId = $this->enrollmentMap[$eKey] ?? null;
                if (! $enrollmentId) {
                    $enrollmentId = DB::table('exam_enrollments')
                        ->where('hall_ticket', $row->HALLTICKET)
                        ->where('exam_id', $examId)
                        ->value('id');

                    if (! $enrollmentId) {
                        $enrollmentId = $this->ensureEnrollment($row->HALLTICKET, $examId, $row->SEMESTER);
                    }

                    if ($enrollmentId) {
                        $this->enrollmentMap[$eKey] = $enrollmentId;
                    }
                }
                if (! $enrollmentId) {
                    $skipped++;
                    continue;
                }

                // Deduplicate
                $dedupKey = "{$enrollmentId}_{$subjectId}";
                if (isset($seen[$dedupKey])) {
                    continue;
                }
                $seen[$dedupKey] = true;

                $extRaw      = $row->EXT ?? null;
                $intRaw      = $row->{'INT'} ?? null;
                $isAbsentExt = strtoupper((string) $extRaw) === 'AB';
                $isAbsentInt = strtoupper((string) $intRaw) === 'AB';

                $result = match (strtoupper((string) ($row->RESULT ?? 'F'))) {
                    'P'  => 'P',
                    'R'  => 'R',
                    'M'  => 'M',
                    'AB' => 'AB',
                    default => 'F',
                };

                $batch[] = [
                    'enrollment_id'        => $enrollmentId,
                    'subject_id'           => $subjectId,
                    'hall_ticket'          => $row->HALLTICKET,
                    'exam_id'              => $examId,
                    'ext_marks'            => $isAbsentExt ? null : ($extRaw !== null ? (int) $extRaw : null),
                    'int_marks'            => $isAbsentInt ? null : ($intRaw !== null ? (int) $intRaw : null),
                    'total_marks'          => (int) ($row->MARKS ?? 0),
                    'grade'                => $row->GRADE ?? null,
                    'result'               => $result,
                    'credits'              => (float) ($row->CREDITS ?? 0),
                    'gp_value'             => (float) ($row->GPV ?? 0),
                    'gp_credits'           => (float) ($row->GPC ?? 0),
                    'is_malpractice'       => $result === 'M',
                    'is_absent_ext'        => $isAbsentExt,
                    'is_absent_int'        => $isAbsentInt,
                    'marks_secured'        => $row->MARKS_SECURED !== null ? (int) $row->MARKS_SECURED : null,
                    'etotal'               => $row->ETOTAL !== null ? (int) $row->ETOTAL : null,
                    'itotal'               => $row->ITOTAL !== null ? (int) $row->ITOTAL : null,
                    'floatation_marks'     => $row->FLOATATION_MARKS !== null ? (int) $row->FLOATATION_MARKS : null,
                    'float_deduct'         => $row->FLOAT_DEDUCT !== null ? (int) $row->FLOAT_DEDUCT : null,
                    'fl_scriptcode'        => $row->FL_SCRIPTCODE ?? null,
                    'moderation_marks'     => $row->MODERATION_MARKS !== null ? (int) $row->MODERATION_MARKS : null,
                    'ac_marks'             => $row->AC_MARKS !== null ? (int) $row->AC_MARKS : null,
                    'is_moderated'         => (bool) ($row->IS_MODERATED ?? false),
                    'passed_by_floatation' => (bool) ($row->PASSED_BY_FLOATATION ?? false),
                    'part'                 => (int) ($row->PART ?? 1),
                    'semester'             => (int) ($row->SEMESTER ?? 1),
                    'attempts'             => (int) ($row->ATTEMPTS ?? 1),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
                $inserted++;

                if (count($batch) >= 100) {
                    if (! $this->dryRun) {
                        DB::table('results')->insert($batch);
                    }
                    $batch = [];
                }
            }

            if (! empty($batch) && ! $this->dryRun) {
                DB::table('results')->insert($batch);
            }
        });

        $this->line("  results: {$inserted} inserted, {$skipped} skipped (null hallticket/papercode or unmappable examid)");
    }

    // ─── GPAs ────────────────────────────────────────────────────────────────

    private function migrateGpas(): void
    {
        $this->refreshMaps();

        $upserted = 0;
        $skipped  = 0;

        $this->legacy()->table('gpas')->orderBy('GPAID')->chunk($this->chunk, function ($rows) use (&$upserted, &$skipped) {
            foreach ($rows as $row) {
                $examId = $this->examMap[$row->EXAMID] ?? null;
                if (! $examId && $row->EXAMID) {
                    $examId = $this->ensureExam($row->EXAMID);
                    if ($examId) {
                        $this->examMap[$row->EXAMID] = $examId;
                    }
                }
                if (! $examId) {
                    $skipped++;
                    continue;
                }

                $eKey = "{$row->HALLTICKET}_{$examId}";
                $enrollmentId = $this->enrollmentMap[$eKey]
                    ?? DB::table('exam_enrollments')
                        ->where('hall_ticket', $row->HALLTICKET)
                        ->where('exam_id', $examId)
                        ->value('id');

                if (! $enrollmentId) {
                    $enrollmentId = $this->ensureEnrollment($row->HALLTICKET, $examId);
                }

                if (! $enrollmentId) {
                    $skipped++;
                    continue;
                }

                if ($enrollmentId) {
                    $this->enrollmentMap[$eKey] = $enrollmentId;
                }

                $data = [
                    'enrollment_id' => $enrollmentId,
                    'hall_ticket'   => $row->HALLTICKET,
                    'exam_id'       => $examId,
                    'sgpa'          => (float) ($row->SGPA ?? 0),
                    'cgpa'          => (float) ($row->CGPA ?? 0),
                    'total_marks'   => (int) ($row->TOTAL ?? 0),
                    'result'        => in_array($row->RESULT, ['P', 'F', 'R', 'M']) ? $row->RESULT : 'F',
                    'processed_at'  => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                if (! $this->dryRun) {
                    DB::table('gpas')->upsert($data, ['enrollment_id'], array_keys($data));
                }
                $upserted++;
            }
        });

        $this->line("  gpas: {$upserted} upserted, {$skipped} skipped (examid=0 or truly unmappable)");
    }

    // ─── Grades ──────────────────────────────────────────────────────────────

    private function migrateGrades(): void
    {
        $this->legacy()->table('grades')->orderBy('ID')->chunk($this->chunk, function ($rows) {
            foreach ($rows as $row) {
                $studentId = $this->htStudentMap[$row->HALLTICKET]
                    ?? DB::table('students')->where('hall_ticket', $row->HALLTICKET)->value('id');

                if (! $studentId) {
                    continue;
                }

                $data = [
                    'student_id'     => $studentId,
                    'hall_ticket'    => $row->HALLTICKET,
                    'memo_no'        => $row->MEMO_NO ? (string) $row->MEMO_NO : null,
                    'part1_cgpa'     => $row->P1CGPA ?? null,
                    'part2_cgpa'     => $row->P2CGPA ?? null,
                    'all_cgpa'       => $row->ALLCGPA ?? null,
                    'part1_division' => $row->P1DIV ?? null,
                    'part2_division' => $row->P2DIV ?? null,
                    'final_division' => $row->FINALDIV ?? null,
                    'part1_subjects' => $row->P1SUBS ?? null,
                    'part2_subjects' => $row->P2SUBS ?? null,
                    'created_at'     => $row->created_at ?? now(),
                    'updated_at'     => now(),
                ];

                if (! $this->dryRun) {
                    DB::table('grades')->upsert($data, ['student_id'], array_keys($data));
                }
            }
        });
    }

    // ─── Helpers: resolve or create missing dependencies ─────────────────────

    /**
     * Refresh all in-memory lookup maps from the current DB state.
     * Call before each migration step that depends on prior steps.
     */
    private function refreshMaps(): void
    {
        $this->examMap = $this->buildExamMap();

        $this->subjectMap = DB::table('subjects')->pluck('id', 'code')->all();

        $this->studentMap = DB::table('students')->whereNotNull('legacy_stid')
            ->pluck('id', 'legacy_stid')->all();

        $this->htStudentMap = DB::table('students')->pluck('id', 'hall_ticket')->all();

        $this->enrollmentMap = DB::table('exam_enrollments')
            ->select('id', 'hall_ticket', 'exam_id')
            ->get()
            ->mapWithKeys(fn ($e) => ["{$e->hall_ticket}_{$e->exam_id}" => $e->id])
            ->all();
    }

    /**
     * Build legacy EXID → new exams.id map (matched by name+semester+year).
     */
    private function buildExamMap(): array
    {
        $newExams = DB::table('exams')->select('id', 'name', 'semester', 'year')->get()
            ->keyBy(fn ($e) => strtolower(trim($e->name)) . '_' . $e->semester . '_' . $e->year);

        $map = [];
        $this->legacy()->table('examsmaster')->get()->each(function ($row) use ($newExams, &$map) {
            $key = strtolower(trim($row->EXAMNAME)) . '_' . $row->SEMESTER . '_' . $row->YEAR;
            if (isset($newExams[$key])) {
                $map[$row->EXID] = $newExams[$key]->id;
            }
        });

        return $map;
    }

    /**
     * Ensure an exam exists for the given legacy EXID. Creates it if missing.
     * Returns new exams.id or null if EXID=0/not in examsmaster.
     */
    private function ensureExam(int $legacyExid): ?int
    {
        if (! $legacyExid) {
            return null;
        }

        $row = $this->legacy()->table('examsmaster')->where('EXID', $legacyExid)->first();
        if (! $row) {
            return null;
        }

        $status   = strtolower($row->STATUS ?? 'closed') === 'close' ? 'closed' : strtolower($row->STATUS ?? 'closed');
        $examType = match (strtolower($row->EXAMTYPE ?? 'regular')) {
            'supply', 'supplementary' => 'supplementary',
            'revaluation'             => 'revaluation',
            'improvement'             => 'improvement',
            default                   => 'regular',
        };

        $data = [
            'name'             => $row->EXAMNAME,
            'semester'         => $row->SEMESTER ?? 1,
            'course'           => $row->COURSE ?? '',
            'exam_type'        => $examType,
            'month'            => $row->MONTH ?? null,
            'year'             => (int) ($row->YEAR ?? date('Y')),
            'status'           => in_array($status, ['open', 'closed', 'cancelled']) ? $status : 'closed',
            'scheme'           => $row->SCHEME ?? null,
            'revaluation_open' => (bool) ($row->REVALOPEN ?? false),
            'fee_json'         => '{}',
        ];

        DB::table('exams')->upsert($data, ['name', 'semester', 'year', 'course'], array_keys($data));

        return DB::table('exams')
            ->where('name', $row->EXAMNAME)
            ->where('semester', $row->SEMESTER)
            ->where('year', $row->YEAR)
            ->value('id');
    }

    /**
     * Ensure a subject exists for the given PAPERCODE. Creates it if missing.
     */
    private function ensureSubject(string $code, ?string $name, int $semester = 1, int $part = 1): ?int
    {
        if (! $code) {
            return null;
        }

        $data = [
            'code'       => $code,
            'name'       => $name ?: $code,
            'course'     => '',
            'semester'   => $semester,
            'part'       => $part,
            'paper_type' => 'compulsory',
        ];

        DB::table('subjects')->insertOrIgnore($data);

        return DB::table('subjects')->where('code', $code)->value('id');
    }

    /**
     * Ensure a student exists for the given hall ticket. Creates minimal record if missing.
     */
    private function ensureStudent(string $hallTicket): ?int
    {
        // Try legacy students table first
        $legacy = $this->legacy()->table('students')->where('haltckt', $hallTicket)->first();

        $dob = null;
        if ($legacy && ! empty($legacy->dob)) {
            $parsed = \DateTime::createFromFormat('d/m/Y', $legacy->dob)
                ?? \DateTime::createFromFormat('Y-m-d', $legacy->dob)
                ?? null;
            $dob = $parsed?->format('Y-m-d');
        }

        $data = [
            'hall_ticket' => $hallTicket,
            'name'        => $legacy->sname ?? $hallTicket,
            'dob'         => $dob,
            'course'      => $legacy->course ?? null,
            'course_name' => $legacy->course_name ?? null,
            'group_code'  => $legacy->group ?? null,
            'legacy_stid' => $legacy->stid ?? null,
            'is_active'   => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];

        DB::table('students')->insertOrIgnore($data);

        return DB::table('students')->where('hall_ticket', $hallTicket)->value('id');
    }

    /**
     * Ensure an enrollment exists for hall_ticket + exam_id. Creates minimal record if missing.
     */
    private function ensureEnrollment(string $hallTicket, int $examId, int $semester = 1): ?int
    {
        $studentId = $this->htStudentMap[$hallTicket]
            ?? DB::table('students')->where('hall_ticket', $hallTicket)->value('id');

        if (! $studentId) {
            $studentId = $this->ensureStudent($hallTicket);
            if ($studentId) {
                $this->htStudentMap[$hallTicket] = $studentId;
            }
        }

        if (! $studentId) {
            return null;
        }

        DB::table('exam_enrollments')->insertOrIgnore([
            'exam_id'     => $examId,
            'student_id'  => $studentId,
            'hall_ticket' => $hallTicket,
            'exam_type'   => 'regular',
            'fee_amount'  => 0,
            'enrolled_at' => now(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return DB::table('exam_enrollments')
            ->where('hall_ticket', $hallTicket)
            ->where('exam_id', $examId)
            ->value('id');
    }
}
