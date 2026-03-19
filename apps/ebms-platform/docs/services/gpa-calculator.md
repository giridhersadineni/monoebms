# GpaCalculatorService

**File:** `app/Services/GpaCalculatorService.php`

Calculates SGPA (Semester GPA) and CGPA (Cumulative GPA) for students based on their results.

---

## SGPA Calculation

SGPA for a single exam is computed from the `results` table:

```
SGPA = Σ(grade_points × credits) / Σ(credits)
```

Grade points are derived from `ac_marks` (actual credit marks after moderation).

---

## CGPA Calculation

CGPA aggregates all `gpas` records for a student:

```
CGPA = Σ(sgpa × credits_earned) / Σ(credits_earned)
```

Stored in the `grades` table along with the division classification.

---

## Division Classification

| CGPA Range | Division |
|-----------|---------|
| ≥ 7.5 | First Class With Distinction |
| ≥ 6.0 | First Class |
| ≥ 5.0 | Second Class |
| ≥ 4.0 | Pass Class |
| < 4.0 | Fail |

---

## Triggering Recalculation

GPA records are generated via the admin Grade Sheet interface (`/admin/gradesheets/{student}/generate`) and require `superadmin` role.

Results must be imported first via `php artisan ebms:migrate-legacy --table=results`.
