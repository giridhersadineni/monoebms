# Courses & Groups

**Controller:** `app/Http/Controllers/Admin/CourseController.php`

---

## Routes

```
GET    /admin/courses                        → index
GET    /admin/courses/create                 → create
POST   /admin/courses                        → store
GET    /admin/courses/{course}               → show
GET    /admin/courses/{course}/edit          → edit
PUT    /admin/courses/{course}               → update
DELETE /admin/courses/{course}               → destroy
POST   /admin/courses/{course}/groups        → storeGroup
GET    /admin/courses/{course}/groups/{group}/edit   → editGroup
PUT    /admin/courses/{course}/groups/{group}        → updateGroup
DELETE /admin/courses/{course}/groups/{group}        → destroyGroup
```

---

## Courses

| Code | Name | Semesters |
|------|------|-----------|
| BA | Bachelor of Arts | 6 |
| BCOM | Bachelor of Commerce | 6 |
| BSC | Bachelor of Science | 6 |

---

## Course Groups

Each course has specialization groups. Groups have:
- `code` — used in `students.group_code` and `subjects.group_code`
- `mediums` — JSON array of allowed medium values
- `schemes` — JSON array of allowed scheme years
- `is_active` — inactive groups are hidden from the enrollment subject picker

See [Course Model](../../models/course.md) for the full group list per course.
