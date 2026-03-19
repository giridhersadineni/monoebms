# Exam Management

**Controller:** `app/Http/Controllers/Admin/ExamController.php`

---

## Routes

```
GET    /admin/exams              → index
GET    /admin/exams/create       → create
POST   /admin/exams              → store
GET    /admin/exams/{exam}       → show
GET    /admin/exams/{exam}/edit  → edit
PUT    /admin/exams/{exam}       → update
PATCH  /admin/exams/{exam}/status       → toggleStatus
PATCH  /admin/exams/{exam}/revaluation  → toggleRevaluation
```

---

## Status Lifecycle

```
NOTIFY  →  RUNNING  →  CLOSED  →  NOTIFY  (cycle)
```

| Status | Meaning |
|--------|---------|
| `NOTIFY` | Enrollment window is open — students can enroll |
| `RUNNING` | Exam is in progress — hall tickets downloadable |
| `REVALOPEN` | Revaluation window is open |
| `CLOSED` | Exam concluded |

Toggle via the **Start Enrollment** / **Close Exam** button on the exam show page.

---

## Exam Show Page

The exam detail page includes:
- Metadata (status, type, scheme, enrollment count)
- Quick action buttons (toggle status, toggle revaluation)
- **Fee Defaults** — exam-level fee configuration
- **Fee Rules** — per-course/group overrides (see [Fee Rules](fee-rules.md))
- Recent enrollments table

---

## Creating an Exam

Required fields:
- Name
- Course (`BA` / `BCOM` / `BSC`)
- Exam type (`regular` / `supplementary` / `improvement`)
- Semester
- Month + Year
- Status (default: `NOTIFY`)
- Fee fields

Fee fields can be left blank and filled in later via Fee Rules.
