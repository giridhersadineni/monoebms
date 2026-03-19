# ExamFeeRule Model

**File:** `app/Models/ExamFeeRule.php`

Stores per-course or per-course-group fee overrides for an exam. When a student enrolls, the fee calculator resolves the applicable rule via a priority cascade rather than always using the exam-level defaults.

---

## Table: `exam_fee_rules`

```sql
exam_id       BIGINT FK → exams (CASCADE DELETE)
course        VARCHAR(10) NULL   -- 'BA', 'BCOM', 'BSC', or NULL (all)
group_code    VARCHAR(20) NULL   -- 'HEP', 'CA', or NULL (all in course)
fee_regular   INT UNSIGNED NULL
fee_supply_upto2 INT UNSIGNED NULL
fee_improvement  INT UNSIGNED NULL
fee_fine      INT UNSIGNED DEFAULT 0
UNIQUE (exam_id, course, group_code)
```

---

## Priority Cascade

When `Exam::calculateFee()` is called with a course and group, the resolver checks in order:

1. **Exact match** — `course = 'BA'` AND `group_code = 'HEP'`
2. **Course-only match** — `course = 'BA'` AND `group_code IS NULL`
3. **Exam-wide default** — `course IS NULL` AND `group_code IS NULL`
4. **Exam table columns** — `exams.fee_regular`, etc. (fallback when no rule exists)

Only the fields that are non-null in the matched rule override the exam defaults. Fields left `null` in a rule fall back to the exam-level column value.

---

## Relationship

```php
$rule->exam()   // BelongsTo → Exam
```

---

## Example

```php
// Set BA-wide fee
ExamFeeRule::create([
    'exam_id'     => 326,
    'course'      => 'BA',
    'group_code'  => null,
    'fee_regular' => 1200,
]);

// Override just for BA/HEP
ExamFeeRule::create([
    'exam_id'     => 326,
    'course'      => 'BA',
    'group_code'  => 'HEP',
    'fee_regular' => 1350,
]);

// BA/HEP students pay 1350, all other BA students pay 1200
```

---

## Admin UI

Fee rules are managed on the **Admin → Exam → Show** page. The add-rule form has a course select that dynamically filters the group select via JavaScript.
