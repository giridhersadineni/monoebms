---
name: ebms-migrate
description: Run the EBMS legacy data migration (ebms:migrate-legacy) from the old uascexams_ebms database into the new platform. Covers safe run order, filtering, dry-run, and post-migration verification.
---

# EBMS Legacy Migration

Migrate data from the legacy `uascexams_ebms` MySQL database into `apps/ebms-platform/`.

## When to use

- Migrating exam results, enrollments, or student records from the legacy portal
- Seeding a fresh production database from legacy data
- Syncing a specific exam's data after it was processed in the legacy system

---

## Command reference

```bash
# Migrate everything (subjects → exams → students → admin_users → enrollments → results → gpas → grades)
php artisan ebms:migrate-legacy --table=all

# Single table
php artisan ebms:migrate-legacy --table=results
php artisan ebms:migrate-legacy --table=enrollments
php artisan ebms:migrate-legacy --table=students

# Filter by legacy EXAMID (for enrollments and results only)
php artisan ebms:migrate-legacy --table=results --exam-id=326
php artisan ebms:migrate-legacy --table=enrollments --exam-id=326

# Dry run — shows what would be migrated without writing anything
php artisan ebms:migrate-legacy --table=results --dry-run
php artisan ebms:migrate-legacy --table=results --exam-id=326 --dry-run
```

Run on production via SSH:
```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan ebms:migrate-legacy --table=TABLE --exam-id=ID"
```

---

## Safe run order

Always run tables in this order when migrating all data. Dependencies flow downward:

```
subjects → exams → students → admin_users → enrollments → results → gpas → grades
```

The command handles this automatically when `--table=all`. When running individual tables, maintain this order manually.

---

## Key behaviours

- **`ensure*()`** methods create missing records rather than skipping rows — safe to re-run; duplicates are resolved via `updateOrCreate`.
- **`--exam-id`** filters `enrollments` and `results` to a single legacy `EXAMID`. Use this when a specific exam just closed and needs to be synced without touching everything else.
- **`0000-00-00` dates** — sanitized to `null` automatically.
- **Legacy `INT` column** (MySQL reserved word) — accessed as `$row->{'INT'}` in the migration code.
- **Fee mapping from legacy `examsmaster`:**

  | Legacy column | Maps to |
  |---------------|---------|
  | `FEE` | `fee_per_subject` (supplementary) or `fee_regular` (regular) |
  | `ABOVE2SUBS` | `fee_regular` (supplementary, 3+ papers) |
  | `IMPROVEMENT` | `fee_improvement` |
  | `FINE` | `fee_fine` |

---

## Post-migration verification

After migrating results/enrollments for an exam, verify counts and spot-check:

```bash
php artisan tinker --execute="
\$examId = EXAM_ID;
echo 'Enrollments: '.App\Models\ExamEnrollment::where('exam_id', \$examId)->count().PHP_EOL;
echo 'Results: '.App\Models\Result::where('exam_id', \$examId)->count().PHP_EOL;
echo 'GPAs: '.App\Models\Gpa::where('exam_id', \$examId)->count().PHP_EOL;
// Sample a student
\$sample = App\Models\ExamEnrollment::where('exam_id', \$examId)->with('enrollmentSubjects')->first();
echo 'Sample hall_ticket: '.\$sample->hall_ticket.' | subjects: '.\$sample->enrollmentSubjects->count().PHP_EOL;
"
```

Check the log for errors:
```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "tail -50 /home/uascexams/ebmsnova.uasckuexams.in/storage/logs/laravel.log"
```

---

## Common pitfalls

- **Do not edit existing migrations** (e.g. `000007_create_results_table`) — they are already applied on production. Add new additive migrations instead.
- **Migration `000013` was merged into `000007`** and deleted — do not recreate it.
- **`subjects` must be migrated before `enrollments`** — enrollment subjects reference `subjects.id`.
- **Legacy connection** (`LEGACY_DB_*` env vars) is read-only — never write to it.
- **Always dry-run first** when migrating a new exam on production to check expected record counts before committing.

---

## Key files

- `app/Console/Commands/MigrateLegacyData.php` — full migration command implementation
- `config/database.php` — `legacy` connection definition
- `.env` — `LEGACY_DB_HOST`, `LEGACY_DB_DATABASE`, `LEGACY_DB_USERNAME`, `LEGACY_DB_PASSWORD`
