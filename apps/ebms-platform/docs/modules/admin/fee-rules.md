# Fee Rules (Admin)

**Controller:** `app/Http/Controllers/Admin/ExamFeeRuleController.php`

Fee rules allow setting different exam fees for different course/group combinations, overriding the exam-level defaults.

---

## Routes

```
POST   /admin/exams/{exam}/fee-rules              → store (upsert)
DELETE /admin/exams/{exam}/fee-rules/{rule}        → destroy
```

---

## Managing Rules

Fee rules are managed directly on the **Exam show page** (`/admin/exams/{exam}`).

### Add / Update a Rule

The form at the bottom of the Fee Rules section:

1. **Select Course** — `BA`, `BCOM`, `BSC`, or leave blank for all courses
2. **Select Group** — dynamically populated based on course selection, or blank for all groups
3. **Enter fee values** — only enter the fields you want to override; leave others blank to inherit exam defaults
4. Click **Save Rule**

If a rule already exists for the same `(course, group_code)` combination, it is **updated in place** (upsert).

### Delete a Rule

Click the **Delete** button on any rule row. The exam-level defaults will apply to those students after deletion.

---

## Priority Cascade Reference

When `FeeCalculatorService` calculates a fee:

```
1. ExamFeeRule where course='BA' AND group_code='HEP'   ← most specific
2. ExamFeeRule where course='BA' AND group_code IS NULL  ← course default
3. ExamFeeRule where course IS NULL AND group_code IS NULL ← exam-wide rule
4. exams.fee_regular / fee_supply_upto2 / fee_improvement  ← exam columns
```

Within a matched rule, only **non-null fields** override the exam columns. If `fee_supply_upto2` is null in the matched rule, the exam's `fee_supply_upto2` column is used.

---

## Example Setup for Exam 326

| Course | Group | Regular | Supply ≤2 |
|--------|-------|---------|-----------|
| BA | (all) | 1200 | — |
| BCOM | (all) | 1400 | — |
| BSC | (all) | 1500 | — |
| BA | HEP | 1350 | — |

With this setup:
- BA/HEP students pay **Rs.1350**
- All other BA students pay **Rs.1200**
- BCOM students pay **Rs.1400**
- BSC students pay **Rs.1500**
