# Legacy Migration Command

**File:** `app/Console/Commands/MigrateLegacyData.php`

**Command:** `php artisan ebms:migrate-legacy`

Migrates data from the legacy `uascexams_ebms` database (read via the `legacy` DB connection) into the new `mariadb` schema.

---

## Usage

```bash
# Run all migration steps
php artisan ebms:migrate-legacy --table=all

# Dry run (no writes)
php artisan ebms:migrate-legacy --table=results --dry-run

# Filter enrollments/results to a single legacy exam
php artisan ebms:migrate-legacy --table=enrollments --exam-id=326
php artisan ebms:migrate-legacy --table=results --exam-id=326

# Migrate photos and signatures from legacy server
php artisan ebms:migrate-legacy --table=photos
```

---

## Steps and Order

Run order matters — later steps depend on earlier ones:

| Step | Table | Description |
|------|-------|-------------|
| 1 | `subjects` | Paper catalogue |
| 2 | `exams` | Exam records from `examsmaster` |
| 3 | `students` | Student accounts |
| 4 | `admin_users` | Admin accounts |
| 5 | `enrollments` | Enrollment records |
| 6 | `results` | Marks and moderation data |
| 7 | `gpas` | SGPA records |
| 8 | `grades` | CGPA / division |
| 9 | `photos` | Copy photos/signatures from legacy server |

---

## Internal Maps

The command builds in-memory maps before each step:
- `$examMap` — legacy EXAMID → new exam ID
- `$subjectMap` — legacy subject code → new subject ID
- `$studentMap` — hall ticket → new student ID
- `$enrollmentMap` — (student_id, exam_id) → enrollment ID

Maps are refreshed via `refreshMaps()` before each step.

---

## Options

| Option | Description |
|--------|-------------|
| `--table` | Which step to run: `all`, `subjects`, `exams`, `students`, `admin_users`, `enrollments`, `results`, `gpas`, `grades`, `photos` |
| `--dry-run` | Output what would be written without making DB changes |
| `--exam-id` | Filter `enrollments` and `results` to a single legacy EXAMID |

---

## Known Quirks

- Legacy `INT` column (MySQL reserved word) accessed as `$row->{'INT'}`
- `0000-00-00` dates are sanitized to `null` via the `$safeDate` helper
- Legacy fee columns map as: `FEE` → `fee_per_subject` (supply) / `fee_regular` (regular); `ABOVE2SUBS` → `fee_regular` (supply); `IMPROVEMENT` → `fee_improvement`; `FINE` → `fee_fine`
- Photos use the aadhaar → hall_ticket mapping from the DB to rename files
