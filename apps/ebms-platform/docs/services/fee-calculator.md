# FeeCalculatorService

**File:** `app/Services/FeeCalculatorService.php`

Thin service that delegates to `Exam::calculateFee()`. Inject via constructor for easy testing.

---

## Signature

```php
public function calculate(
    Exam $exam,
    int $subjectCount,
    ?string $course = null,
    ?string $groupCode = null
): int
```

### Parameters

| Parameter | Description |
|-----------|-------------|
| `$exam` | The exam being enrolled in. **Must have `feeRules` eager-loaded** for the override lookup to avoid N+1 queries. |
| `$subjectCount` | Number of subjects the student is enrolling in |
| `$course` | Student's course code — used to resolve fee rule override |
| `$groupCode` | Student's group code — used for exact-match override |

### Returns

Total fee in rupees (integer).

---

## Usage in EnrollmentController

```php
// Eager-load rules before calling
$exam->load('feeRules');

$fee = $this->feeCalculator->calculate(
    $exam,
    count($subjectIds),
    $student->course,
    $student->group_code
);
```

---

## Fee Logic Summary

| Exam type | Rule |
|-----------|------|
| `regular` | `fee_regular` flat + fine |
| `supplementary` | 1–2 papers → `fee_supply_upto2` + fine; 3+ → `fee_regular` + fine |
| `improvement` | `fee_improvement × max(1, subjectCount)` + fine |

The `fee_fine` is **always added** on top. It defaults to 0 and is set manually by admins during the late-payment grace period.

---

## Fee Rule Override Priority

```
ExamFeeRule(course=BA, group_code=HEP)   ← highest priority
ExamFeeRule(course=BA, group_code=NULL)  ← course default
ExamFeeRule(course=NULL, group_code=NULL) ← exam-wide default
exams.fee_regular / fee_supply_upto2 / fee_improvement ← fallback
```

Only non-null fields in the matched rule override the exam defaults. If a rule has `fee_regular = null`, the exam's `fee_regular` column is used instead.
