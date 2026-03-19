# Student Management

**Controller:** `app/Http/Controllers/Admin/StudentController.php`

---

## Routes

```
GET  /admin/students/search    → search (by hall ticket, name, or DOST ID)
GET  /admin/students/{hallTicket} → show
PUT  /admin/students/{id}      → update  [role: admin, superadmin]
```

---

## Student Show Page

The student detail view shows:

- Basic info (name, course, group, medium, scheme, semester)
- Photo and signature thumbnails (if uploaded)
- All exam enrollments with fee status
- Results and GPA summary
- Grade sheet link (if grade exists)

---

## Editing Students

Admins with `admin` or `superadmin` role can update student details (e.g. name corrections, course corrections). PII fields (`aadhaar`, `dob`) are not displayed in the admin UI.
