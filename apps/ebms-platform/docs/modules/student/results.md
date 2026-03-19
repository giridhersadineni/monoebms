# Results & GPA

**Controller:** `app/Http/Controllers/Student/ResultController.php`

---

## Routes

```
GET /student/results           → index (list all enrollments with results)
GET /student/results/{enrollment} → show (single result detail)
```

---

## What Students See

- Subject-wise marks (internal + external + total)
- SGPA for the semester
- Cumulative CGPA (if available)
- Pass/Fail status per subject
- Division (First Class, Second Class, etc.)

---

## Data Source

Results are migrated from the legacy database via `php artisan ebms:migrate-legacy --table=results`.

Marks are stored in the `results` table. SGPA is in `gpas`, CGPA in `grades`.
