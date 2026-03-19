# Enrollment Management

**Controller:** `app/Http/Controllers/Admin/EnrollmentController.php`

---

## Routes

```
GET  /admin/enrollments              → index (filterable by exam, fee status, hall ticket)
GET  /admin/enrollments/{id}         → show
POST /admin/enrollments/{id}/fee     → markFeePaid
```

---

## Marking Fee as Paid

After a student pays at the bank, an admin marks the enrollment as paid:

```
POST /admin/enrollments/{id}/fee
```

This sets `fee_paid_status = 'paid'` on the enrollment.

---

## Filtering

The index page supports filtering by:
- `exam_id` — show enrollments for a specific exam
- `fee_status` — `paid` / `unpaid`
- Hall ticket search
