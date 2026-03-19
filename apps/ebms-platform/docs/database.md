# Database

## Connections

| Connection | Purpose | Config key |
|-----------|---------|------------|
| `mariadb` | Primary read/write | `DB_*` env vars |
| `legacy` | Read-only legacy data | `LEGACY_DB_*` env vars |
| `sqlite` (:memory:) | Tests only | `phpunit.xml` |

The legacy connection points to `uascexams_ebms` on the same server and is used only by the `ebms:migrate-legacy` artisan command.

---

## Migrations

Migrations run in order. The filename timestamp is the canonical run order.

| Migration | Purpose |
|-----------|---------|
| `000001_create_students_table` | Student accounts |
| `000002_create_admin_users_table` | Admin accounts |
| `000003_create_exams_table` | Exam records |
| `000004_create_subjects_table` | Paper/subject catalogue |
| `000005_create_exam_enrollments_table` | Enrollment records |
| `000006_create_exam_enrollment_subjects_table` | Per-enrollment subject selections |
| `000007_create_results_table` | Marks and moderation (9 inline columns) |
| `000008_create_gpas_table` | Per-exam SGPA records |
| `000009_create_grades_table` | Cumulative CGPA / division |
| `000010_create_revaluation_enrollments_table` | Revaluation requests |
| `000011_create_revaluation_subjects_table` | Subjects in revaluation |
| `000012_create_audit_events_table` | Audit trail |
| `2026_03_19_000001` | Replace `fee_json` with flat fee columns on exams |
| `2026_03_19_000002` | Rename fee columns, update exam status enum |
| `2026_03_19_000003` | Create `courses` and `course_groups` tables |
| `2026_03_19_000004` | Fix subjects unique key |
| `2026_03_19_000005` | Add `photo_path`, `signature_path` to students |
| `2026_03_19_000006` | Create `exam_fee_rules` table |

> **Note:** Migration `000013` (moderation columns) was merged into `000007` and deleted. Do not recreate it.

---

## Schema Reference

### `students`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `hall_ticket` | varchar unique | Login credential |
| `name` | varchar | |
| `date_of_birth` | date | Login credential (alt) |
| `dost_id` | varchar nullable | Legacy login alternative |
| `aadhaar` | varchar nullable | **PII — hidden from serialization** |
| `course` | varchar | e.g. `BA`, `BCOM`, `BSC` |
| `group_code` | varchar nullable | e.g. `HEP`, `CA` |
| `semester` | tinyint | Current semester |
| `medium` | varchar | `english` / `telugu` |
| `scheme` | varchar | Academic scheme year |
| `photo_path` | varchar nullable | `students/photos/{hall_ticket}.jpg` |
| `signature_path` | varchar nullable | `students/signatures/{hall_ticket}.jpg` |
| `is_active` | boolean | |

### `exams`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `name` | varchar | |
| `course` | varchar | `BA` / `BCOM` / `BSC` |
| `exam_type` | varchar | `regular` / `supplementary` / `improvement` |
| `semester` | tinyint | |
| `month` | varchar nullable | Numeric string e.g. `11` |
| `year` | smallint | |
| `status` | varchar | `NOTIFY` / `RUNNING` / `REVALOPEN` / `CLOSED` |
| `scheme` | varchar nullable | |
| `revaluation_open` | boolean | |
| `fee_regular` | int unsigned nullable | Flat fee (regular) or 3+ papers (supply) |
| `fee_supply_upto2` | int unsigned nullable | Supply flat fee for 1–2 papers |
| `fee_improvement` | int unsigned nullable | Per-paper improvement fee |
| `fee_fine` | int unsigned default 0 | Late fine |

### `exam_fee_rules`

Overrides exam-level fee for specific course/group combinations.

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `exam_id` | bigint FK → exams | cascade delete |
| `course` | varchar(10) nullable | `null` = all courses |
| `group_code` | varchar(20) nullable | `null` = all groups in course |
| `fee_regular` | int unsigned nullable | |
| `fee_supply_upto2` | int unsigned nullable | |
| `fee_improvement` | int unsigned nullable | |
| `fee_fine` | int unsigned default 0 | |
| Unique | `(exam_id, course, group_code)` | |

### `subjects`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `code` | varchar | e.g. `ENG101` |
| `name` | varchar | |
| `course` | varchar | |
| `group_code` | varchar nullable | |
| `semester` | tinyint | |
| `medium` | varchar | |
| `scheme` | varchar | |
| `paper_type` | varchar | `compulsory` / `elective` |
| `elective_group` | varchar nullable | Groups electives for display |

### `exam_enrollments`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | Used as challan number |
| `exam_id` | bigint FK | |
| `student_id` | bigint FK | |
| `hall_ticket` | varchar | Denormalized for performance |
| `fee_amount` | int | Calculated at enrollment time |
| `fee_paid_status` | varchar | `unpaid` / `paid` |
| `enrolled_at` | timestamp | |

### `audit_events`

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `trace_id` | uuid | From `X-Trace-Id` header |
| `actor_id` | bigint unsigned | Polymorphic — student or admin ID |
| `event` | varchar | e.g. `enrollment.created` |
| `context` | json nullable | Extra data |
| `ip_address` | varchar | |

> `actor_id` has **no FK constraint** — it references either `students.id` or `admin_users.id` polymorphically.

---

## Key Relationships

```
Student
  └── hasMany  ExamEnrollment
                 └── belongsTo  Exam
                 └── hasMany    ExamEnrollmentSubject
                                  └── belongsTo Subject
                 └── hasOne     Result
  └── hasMany  RevaluationEnrollment
  └── hasMany  Gpa
  └── hasOne   Grade

Exam
  └── hasMany  ExamEnrollment
  └── hasMany  ExamFeeRule

Course
  └── hasMany  CourseGroup
```
