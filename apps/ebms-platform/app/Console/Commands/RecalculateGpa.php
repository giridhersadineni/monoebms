<?php

namespace App\Console\Commands;

use App\Domain\Results\SemesterAggregator;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Services\GpaCalculatorService;
use Illuminate\Console\Command;

/**
 * Recomputes stored SGPA rows.
 *
 * Needed because excludeGradeEx used to drop every ungraded row (`grade != 'EX'`
 * is NULL for a NULL grade), so any enrollment holding a passed-but-ungraded
 * paper has a stored SGPA that understates its credits. Nothing recalculates on
 * read, so those rows stay stale until something rewrites them.
 */
class RecalculateGpa extends Command
{
    protected $signature = 'ebms:recalculate-gpa
                            {--exam-id= : Restrict to one exam (platform exams.id, not the legacy EXAMID)}
                            {--dry-run : Report what would change without writing}
                            {--limit=50 : How many changed rows to print (0 prints all)}';

    protected $description = 'Recompute SGPA and overall result for paid enrollments, reporting what changes';

    public function handle(
        GpaCalculatorService $calculator,
        SemesterAggregator $aggregator,
    ): int {
        $examId = $this->option('exam-id');
        $dryRun = (bool) $this->option('dry-run');

        if ($examId !== null && ! is_numeric($examId)) {
            $this->error('--exam-id must be numeric.');

            return self::FAILURE;
        }

        $query = ExamEnrollment::query()->feePaid()->with('gpa');

        if ($examId !== null) {
            $exam = Exam::find((int) $examId);

            if (! $exam) {
                $this->error("No exam with id {$examId}.");

                return self::FAILURE;
            }

            $query->forExam((int) $examId);
            $this->line("Scope: exam {$exam->id}");
        } else {
            $this->line('Scope: every exam');
        }

        $total = (clone $query)->count();

        if ($total === 0) {
            $this->warn('No paid enrollments in scope — nothing to do.');

            return self::SUCCESS;
        }

        $this->line(sprintf('%s %d paid enrollment(s).', $dryRun ? 'Examining' : 'Recalculating', $total));

        $changed = [];
        $written = 0;
        $skipped = 0;
        $bar     = $this->output->createProgressBar($total);

        $query->chunkById(200, function ($enrollments) use (
            $calculator, $aggregator, $dryRun, $bar, &$changed, &$written, &$skipped
        ) {
            foreach ($enrollments as $enrollment) {
                $bar->advance();

                $papers = $enrollment->results()->excludeGradeEx()->get();

                if ($papers->isEmpty()) {
                    $skipped++;
                    continue;
                }

                // Aggregate without persisting so a dry run can show the delta.
                $totals = $aggregator->aggregate($papers);
                $before = $enrollment->gpa;

                $moved = $before === null
                    || round((float) $before->sgpa, 2) !== round($totals['sgpa'], 2)
                    || (int) $before->total_marks !== $totals['total_marks']
                    || $before->result !== $totals['result'];

                if ($moved) {
                    $changed[] = [
                        $enrollment->hall_ticket,
                        $enrollment->exam_id,
                        $before === null ? '—' : number_format((float) $before->sgpa, 2),
                        number_format($totals['sgpa'], 2),
                        $before === null ? '—' : (string) $before->result,
                        $totals['result'],
                    ];
                }

                if (! $dryRun) {
                    $calculator->calculateForEnrollment($enrollment, $papers);
                    $written++;
                }
            }
        });

        $bar->finish();
        $this->newLine(2);

        if ($changed !== []) {
            $limit = (int) $this->option('limit');
            $rows  = $limit > 0 ? array_slice($changed, 0, $limit) : $changed;

            $this->table(
                ['hall ticket', 'exam', 'sgpa before', 'sgpa after', 'result before', 'result after'],
                $rows
            );

            if (count($rows) < count($changed)) {
                $this->line(sprintf('… and %d more.', count($changed) - count($rows)));
            }
        }

        $this->info(sprintf(
            'In scope: %d. %s: %d. No papers (skipped): %d.',
            $total,
            $dryRun ? 'Would change' : 'Changed',
            count($changed),
            $skipped
        ));

        if ($dryRun) {
            $this->warn('Dry-run — nothing written.');

            return self::SUCCESS;
        }

        $this->info("Wrote {$written} gpa row(s).");

        return self::SUCCESS;
    }
}
