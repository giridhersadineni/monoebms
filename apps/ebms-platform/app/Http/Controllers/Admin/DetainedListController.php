<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminFeature;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Detained List — students whose earned credits fall below a cut-off share of
 * the credits they have appeared for.
 *
 * Detention is judged on a student's whole record, not one exam, so there is no
 * exam selector. Each distinct paper counts its credits once however many
 * attempts it took, and a paper passed on any attempt earns its credits.
 *
 * "Each distinct paper" means each distinct paper *code* — see detainedRows().
 *
 * Ported from the legacy backoffice detainedlist.php. Two differences, both
 * forced by this schema:
 *   - Legacy matched RESULTS.HALLTICKET against students.haltckt by string and
 *     offered an "include unmatched" toggle for strays. Here results reach a
 *     student through enrollment_id, a real foreign key, so a stray row cannot
 *     exist and the toggle is gone.
 *   - Papers graded EX are excluded. EX means the paper sits outside the GPA,
 *     so counting its credits as "appeared" would understate every student who
 *     has one. The legacy schema had no equivalent.
 */
class DetainedListController extends Controller
{
    private const DEFAULT_CUTOFF = 50.0;

    public function index(Request $request): View
    {
        abort_unless(auth('admin')->user()?->canAccess(AdminFeature::ResultsView), 403);

        $courseOpts = Student::query()
            ->whereNotNull('course')->where('course', '<>', '')
            ->distinct()->orderBy('course')->pluck('course');

        $schemeOpts = Student::query()
            ->whereNotNull('scheme')->where('scheme', '<>', '')
            ->distinct()->orderBy('scheme')->pluck('scheme');

        // is_scalar guards against ?cutoff[]=1, which would otherwise blow up
        // on the cast rather than falling back.
        $rawCutoff = $request->input('cutoff');
        $cutoff    = is_scalar($rawCutoff) ? (float) $rawCutoff : self::DEFAULT_CUTOFF;
        if ($cutoff <= 0 || $cutoff > 100) {
            $cutoff = self::DEFAULT_CUTOFF;
        }

        // Only offer values that exist, so a hand-edited query string cannot
        // filter the report down to a silently empty set.
        $course = $this->scalarInput($request, 'course');
        $scheme = $this->scalarInput($request, 'scheme');
        if (! $courseOpts->contains($course)) { $course = ''; }
        if (! $schemeOpts->contains($scheme)) { $scheme = ''; }

        $t0   = microtime(true);
        $rows = $this->detainedRows($cutoff, $course, $scheme);
        $queryMs = (int) round((microtime(true) - $t0) * 1000);

        // Population the list was drawn from, for context in the summary.
        $evaluated = ($course === '' && $scheme === '')
            ? (int) DB::table('exam_enrollments as e')
                ->join('results as r', 'r.enrollment_id', '=', 'e.id')
                ->distinct()->count('e.student_id')
            : null;

        return view('admin.detained.index', compact(
            'rows', 'cutoff', 'course', 'scheme',
            'courseOpts', 'schemeOpts', 'evaluated', 'queryMs'
        ));
    }

    private function scalarInput(Request $request, string $key): string
    {
        $value = $request->input($key);

        return is_scalar($value) ? trim((string) $value) : '';
    }

    /**
     * Collapse results to one row per student+paper, then total each student's
     * credits and keep those below the cut-off.
     */
    private function detainedRows(float $cutoff, string $course, string $scheme)
    {
        $earned = "SUM(CASE WHEN t.passed = 1 THEN t.pcredits ELSE 0 END)";
        // 100.0 rather than 100 keeps the division in floating point on SQLite,
        // where a whole-numbered credit column otherwise truncates the share.
        $pct = "{$earned} * 100.0 / SUM(t.pcredits)";

        // Collapse on the paper code, not subject_id. The same paper has more
        // than one subjects row (the unique key is code + group + medium +
        // semester + scheme), so a regular and a supplementary attempt at one
        // paper can carry different subject_ids and would otherwise have its
        // credits counted twice. This is what the legacy report grouped on.
        $perPaper = DB::table('results as r')
            ->join('exam_enrollments as e', 'e.id', '=', 'r.enrollment_id')
            ->join('subjects as sub', 'sub.id', '=', 'r.subject_id')
            ->select('e.student_id', 'sub.code as paper_code')
            ->selectRaw('MAX(COALESCE(r.credits, 0)) as pcredits')
            ->selectRaw("MAX(CASE WHEN r.result = 'P' THEN 1 ELSE 0 END) as passed")
            ->selectRaw('COUNT(*) as attempts')
            // grade is nullable, and `grade <> 'EX'` alone would drop NULL rows.
            ->where(fn ($q) => $q->where('r.grade', '<>', 'EX')->orWhereNull('r.grade'))
            ->groupBy('e.student_id', 'sub.code');

        if ($course !== '' || $scheme !== '') {
            $perPaper->join('students as sf', 'sf.id', '=', 'e.student_id');
            if ($course !== '') { $perPaper->where('sf.course', $course); }
            if ($scheme !== '') { $perPaper->where('sf.scheme', $scheme); }
        }

        return DB::query()->fromSub($perPaper, 't')
            ->join('students as s', 's.id', '=', 't.student_id')
            ->select('s.hall_ticket', 's.name', 's.course', 's.group_code', 's.medium', 's.scheme', 's.phone')
            ->selectRaw('COUNT(*) as papers_appeared')
            ->selectRaw('SUM(t.attempts) as attempts')
            ->selectRaw('SUM(t.pcredits) as total_credits')
            ->selectRaw("{$earned} as earned_credits")
            ->selectRaw('SUM(CASE WHEN t.passed = 0 THEN 1 ELSE 0 END) as papers_pending')
            ->selectRaw("ROUND({$pct}, 2) as credit_pct")
            ->groupBy('t.student_id', 's.hall_ticket', 's.name', 's.course', 's.group_code', 's.medium', 's.scheme', 's.phone')
            ->havingRaw('SUM(t.pcredits) > 0')
            ->havingRaw("{$pct} < ?", [$cutoff])
            ->orderBy('credit_pct')
            ->orderBy('s.hall_ticket')
            ->get();
    }
}
