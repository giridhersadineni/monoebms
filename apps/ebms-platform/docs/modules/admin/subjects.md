# Papers (Subjects)

**Controller:** `app/Http/Controllers/Admin/SubjectController.php`

---

## Routes

Registered as a resource: `Route::resource('papers', SubjectController::class)`

```
GET    /admin/papers              → index
GET    /admin/papers/create       → create
POST   /admin/papers              → store
GET    /admin/papers/{paper}      → show
GET    /admin/papers/{paper}/edit → edit
PUT    /admin/papers/{paper}      → update
DELETE /admin/papers/{paper}      → destroy
```

---

## Key Fields

| Field | Description |
|-------|-------------|
| `code` | Paper code e.g. `TL101` |
| `name` | Full paper name |
| `course` | Which course this paper belongs to |
| `group_code` | Which group (null = all groups in course) |
| `semester` | Semester number |
| `medium` | `english` / `telugu` |
| `scheme` | Academic scheme year |
| `paper_type` | `compulsory` / `elective` |
| `elective_group` | Visual grouping for elective selection UI |

---

## Bulk Import

Papers can be imported via the `ImportPapers` artisan command (`app/Console/Commands/ImportPapers.php`).
