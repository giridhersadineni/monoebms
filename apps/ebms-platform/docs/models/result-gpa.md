# Result & GPA Models

## Result

**File:** `app/Models/Result.php`

Stores marks for a student's enrollment. Includes moderation data inline.

### Key Columns

| Column | Description |
|--------|-------------|
| `enrollment_id` | FK → exam_enrollments |
| `marks_secured` | Raw marks |
| `etotal` | External total |
| `itotal` | Internal total |
| `floatation_marks` | Floatation adjustment |
| `float_deduct` | Floatation deduction |
| `fl_scriptcode` | Floatation script code |
| `moderation_marks` | Added during moderation |
| `ac_marks` | Actual credit marks |
| `is_moderated` | Boolean |

> All 9 moderation columns are defined inline in migration `000007`. Migration `000013` (which used to add them) has been deleted — do not recreate it.

---

## Gpa

**File:** `app/Models/Gpa.php`

Stores the SGPA (Semester Grade Point Average) for a student in a single exam.

```php
$student->gpas()    // HasMany → Gpa
```

### Key Columns

| Column | Description |
|--------|-------------|
| `student_id` | FK → students |
| `exam_id` | FK → exams |
| `sgpa` | Semester GPA (decimal) |
| `credits_earned` | Credits earned in this exam |

---

## Grade

**File:** `app/Models/Grade.php`

Stores the cumulative CGPA and division for a student across all exams.

```php
$student->grade()   // HasOne → Grade
```

### Key Columns

| Column | Description |
|--------|-------------|
| `student_id` | FK → students (unique) |
| `cgpa` | Cumulative GPA |
| `division` | e.g. `First Class With Distinction` |

---

## GpaCalculatorService

**File:** `app/Services/GpaCalculatorService.php`

Calculates SGPA and CGPA from results. Called via artisan command or admin trigger.
