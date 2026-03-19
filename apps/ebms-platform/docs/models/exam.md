# Exam Model

**File:** `app/Models/Exam.php`

Represents a single examination event (e.g. "B.A I Semester Regular Nov 2025").

---

## Constants

```php
Exam::STATUS_NOTIFY     // 'NOTIFY'    — enrollment window open
Exam::STATUS_RUNNING    // 'RUNNING'   — exam in progress
Exam::STATUS_REVALOPEN  // 'REVALOPEN' — revaluation window open
Exam::STATUS_CLOSED     // 'CLOSED'    — exam concluded
```

---

## Relationships

```php
$exam->enrollments()   // HasMany → ExamEnrollment
$exam->feeRules()      // HasMany → ExamFeeRule
```

---

## Scopes

```php
Exam::open()                    // WHERE status = 'NOTIFY'
Exam::forCourse('BA')           // WHERE course = 'BA'
```

---

## Methods

### `calculateFee(int $subjectCount, ?string $course = null, ?string $groupCode = null): int`

Calculates the enrollment fee for a student based on:
1. Exam type (`regular`, `supplementary`, `improvement`)
2. Subject count
3. Course and group (resolved against `exam_fee_rules` with priority cascade)

**Priority cascade for fee values:**

```
exact course + group match  →  course-only match  →  exam-level defaults
```

```php
// Regular exam — flat fee regardless of subject count
$fee = $exam->calculateFee(6);

// With course/group override lookup
$fee = $exam->calculateFee(6, 'BA', 'HEP');
```

**Fee logic by exam type:**

| Exam type | Logic |
|-----------|-------|
| `regular` | `fee_regular` (always flat) + `fee_fine` |
| `supplementary` | ≤2 papers → `fee_supply_upto2` + fine; 3+ papers → `fee_regular` + fine |
| `improvement` | `fee_improvement × max(1, subjectCount)` + fine |

### `nextStatus(): string`

Returns the next status in the admin cycle:

```
NOTIFY → RUNNING → CLOSED → NOTIFY
```

### `canDownloadHallTicket(): bool`

Returns `true` when `status === 'RUNNING'`.

### `getMonthNameAttribute(): string`

Accessor — converts numeric month (e.g. `11`) to `November`.

---

## Accessor

```php
$exam->month_name   // "November" (from numeric string stored in DB)
```

---

## Fillable

```php
['name', 'semester', 'course', 'exam_type', 'month', 'year',
 'status', 'scheme', 'revaluation_open',
 'fee_regular', 'fee_supply_upto2', 'fee_improvement', 'fee_fine']
```
