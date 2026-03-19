# ExamEnrollment Model

**File:** `app/Models/ExamEnrollment.php`

Records a student's enrollment in an exam. The `id` doubles as the **challan number** printed on fee receipts.

---

## Relationships

```php
$enrollment->student()            // BelongsTo → Student
$enrollment->exam()               // BelongsTo → Exam
$enrollment->enrollmentSubjects() // HasMany   → ExamEnrollmentSubject
$enrollment->result()             // HasOne    → Result
```

---

## Key Columns

| Column | Description |
|--------|-------------|
| `id` | Primary key — used as challan number |
| `hall_ticket` | Denormalized from student for fast queries |
| `fee_amount` | Calculated at enrollment time via `FeeCalculatorService` |
| `fee_paid_status` | `unpaid` / `paid` |
| `enrolled_at` | Timestamp |

---

## Fee Paid Status

```php
$enrollment->getFeeStatus()  // Returns a display string for the status badge
```

Admins update `fee_paid_status` to `paid` on the enrollment management page after the student pays at the bank.
