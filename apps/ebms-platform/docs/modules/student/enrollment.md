# Enrollment Flow

**Controller:** `app/Http/Controllers/Student/EnrollmentController.php`

Students enroll in an exam through a 3-step wizard.

---

## Steps

### Step 1 — Select Exam

`GET /student/enrollments/create`

Displays open exams filtered by the student's `course`. Only exams with `status = NOTIFY` are shown.

### Step 2 — Select Subjects

`POST /student/enrollments/subjects` (with `exam_id`)

Fetches available subjects filtered by the student's:
- `course`
- `group_code`
- `semester` (from the exam)
- `medium`
- `scheme`

Compulsory subjects are pre-selected. Elective subjects are grouped by `elective_group` for display.

### Step 3 — Confirm & Pay

`POST /student/enrollments/confirm`

The fee is calculated via `FeeCalculatorService`:

```php
$exam->load('feeRules');
$fee = $this->feeCalculator->calculate(
    $exam,
    count($subjectIds),
    $student->course,
    $student->group_code
);
```

The pending enrollment data (exam_id, subject_ids, fee_amount) is stored in the session. On final submit, the `ExamEnrollment` and `ExamEnrollmentSubject` records are created in a DB transaction.

### Redirect to Challan

On success, the student is redirected directly to the challan download page so they can print their fee receipt immediately.

---

## Duplicate Enrollment Guard

A unique constraint on `(exam_id, student_id)` prevents double-enrollment. The controller catches `UniqueConstraintViolationException` and redirects with an info message.

---

## Routes

```
GET  /student/enrollments               → index (list enrollments)
GET  /student/enrollments/create        → selectExam
POST /student/enrollments/subjects      → selectSubjects
POST /student/enrollments/confirm       → confirm
POST /student/enrollments               → store
```
