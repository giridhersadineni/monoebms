# Known Issues

Open problems that are understood but not yet fixed. Each entry records what was
observed, what was ruled out, and what has to be decided before it can be closed.

---

## 1. Stored SGPA diverges from `SemesterAggregator` for 81% of paid enrollments

**Status:** open — needs an academic decision, not a code change
**Found:** 2026-08-23, running `ebms:recalculate-gpa --dry-run` against production
**Affects:** `gpas.sgpa`, `gpas.total_marks`, `gpas.result` (production data, not code)

### What was observed

An unscoped dry run on production reported:

```
In scope: 2621. Would change: 2111. No papers (skipped): 1.
```

2,111 of 2,621 paid enrollments would get a different SGPA if recalculated. The
deltas are large and move in **both** directions:

| hall ticket | exam | sgpa before | sgpa after |
|---|---|---|---|
| 001251076 | 326 | 0.87 | 0.00 |
| 001251109 | 326 | 8.43 | 7.76 |
| 001253011 | 326 | 0.70 | 1.72 |
| 001251330 | 326 | 4.35 | 3.20 |

### What this is NOT

This divergence is **pre-existing** and unrelated to the paper-code dedup work on
`fix/paper-code-credit-dedup`. Both fixes from that branch were ruled out:

- The null-safe `excludeGradeEx` fix cannot be the cause — production has
  **zero** rows with `results.grade IS NULL`, so the widened filter matches
  nothing new.
- The `calculateDegreeCgpa()` `merge()` fix only affects degree CGPA.
  `ebms:recalculate-gpa` never calls it.

### Probable cause

The stored `gpas` rows appear to predate the platform's own calculation:

- `processed_at` on the inspected rows is `2026-07-15`.
- There are **48,915** `gpas` rows against only **2,621** paid enrollments,
  consistent with bulk legacy migration rather than platform computation.

So the stored values were most likely produced by the legacy system's formula,
and `SemesterAggregator` implements a different one.

### Evidence that recomputation may be the *more* correct side

Hall ticket `001251076`, exam 326 — every paper failed:

```
code=700 grade=F result=F credits=5.0 gp_credits=0.00 total=23
code=703 grade=F result=F credits=5.0 gp_credits=0.00 total=35
code=711 grade=F result=F credits=5.0 gp_credits=0.00 total=33
code=712 grade=F result=F credits=5.0 gp_credits=0.00 total=21
code=713 grade=F result=F credits=5.0 gp_credits=0.00 total=41
```

Recomputed SGPA is `0/25 = 0.00`. The stored value is `0.87` — a non-zero SGPA
for a student who passed nothing. That single case favours the recomputation,
but it does not generalise across 2,111 rows whose deltas run both ways.

### What has to happen before this can be closed

1. Hand-compute SGPA for a sample of students spread across the delta range,
   against the university's actual 2025-26 grading rules.
2. Decide which formula is authoritative — legacy-stored or `SemesterAggregator`.
3. If the recomputation wins, run `ebms:recalculate-gpa` **per exam**
   (`--exam-id=<id>`), not all 278 at once. Use `--limit=0` to print the full
   delta for a scoped run first.
4. If the stored values win, `SemesterAggregator` is wrong and needs fixing
   before any recalculation is run anywhere.

**Do not run `ebms:recalculate-gpa` without `--dry-run` until step 2 is settled.**
It would rewrite 81% of live SGPAs under an unvalidated formula.

### Related

- Command: `app/Console/Commands/RecalculateGpa.php`
- Calculation: `app/Domain/Results/SemesterAggregator.php`
- Backup of the pre-deploy files on production:
  `storage/prefix-backup-20260823-055004/`

---

## 2. `scopeExcludeGradeEx` was NULL-unsafe (fixed, but data impact unverified)

**Status:** fixed in code; no production impact observed
**Fixed:** 2026-08-23, commit `14fed90`

`Result::scopeExcludeGradeEx()` was `where('grade', '!=', 'EX')`. Because
`results.grade` is nullable and SQL evaluates `NULL != 'EX'` to `NULL`, every
ungraded row was silently filtered out of GPA calculations.

Production currently has **zero** NULL-grade rows, so nothing was actually
affected there. The fix prevents a latent bug rather than correcting live data —
worth re-checking after any future legacy import, which is where ungraded passes
would come from.

---

## 3. CI has been red repo-wide since at least 2026-07-16

**Status:** open, unowned

Every run of the `Tests` workflow has failed — on `main`, on `release`, and on
feature branches. The failure predates current work and is not caused by any one
branch. Logs need an authenticated `gh` (not installed in the usual dev
checkout), so the actual cause has not been read yet.

Consequence: no branch can be verified green before merge, and the fixes on
`fix/paper-code-credit-dedup` were committed without the suite ever running.

---

## 4. Detained List report is an unbounded aggregate

**Status:** open — deliberate behaviour, flagged for scale
**Location:** `app/Http/Controllers/Admin/DetainedListController.php:91`

`detainedRows()` groups the entire `results` table twice on every page load, with
no exam/year scoping and no `LIMIT`. The blade then renders every matching
student as a DOM row and paginates client-side in DataTables.

The whole-record behaviour appears intentional — it mirrors the legacy page — so
this was not "fixed". But on the shared cPanel host it is a plausible
`max_execution_time` / `memory_limit` failure as the migrated history grows.
Adding scoping or server-side pagination is a product decision.

---

## 5. `main` lost routes in a bad merge

**Status:** repaired on `fix/paper-code-credit-dedup`; `main` itself still wrong

A merge on `main` (via `0e3f312` / `bb9e95c`, which pulled in an older `release`)
silently deleted the Results and Revaluations route blocks and
`exams.preview-results` from `routes/admin.php`, while leaving
`ResultController` and `RevaluationController` in place. The routes exist in the
merge base, so this was a deletion, not a feature that never landed.

They were restored while resolving conflicts on `fix/paper-code-credit-dedup`
(commit `b9bad84`). Until that branch merges, `main` is missing them.
