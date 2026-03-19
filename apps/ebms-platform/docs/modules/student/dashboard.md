# Student Dashboard

**Route:** `GET /student/dashboard`

The dashboard is the student's home screen after login. It shows:

- Quick action cards:
  - Enroll for Exam
  - My Enrollments
  - My Results
  - My Profile
- Active exam announcements (if any exams are in `NOTIFY` status for the student's course)

---

## View

`resources/views/student/dashboard.blade.php`
