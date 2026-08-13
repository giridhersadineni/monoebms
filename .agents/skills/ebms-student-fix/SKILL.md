---
name: ebms-student-fix
description: Diagnose and fix student record issues on the EBMS platform — primarily enrollment subject fetch failures caused by course/medium/scheme/group_code mismatches.
---

# EBMS Student Fix

Diagnose and correct student data problems that prevent enrollment, paper selection, or portal access.

## When to use

- Student sees no papers / empty subject list during enrollment
- Student cannot log in or is redirected unexpectedly
- Admin reports a student's details are wrong (wrong course, medium, scheme, group)

---

## Enrollment: student sees no papers

`EnrollmentController::selectSubjects()` chains exact-match filters against the `subjects` table. A single mismatch silently returns an empty list.

### Step 1 — Run diagnostic on server

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan tinker --execute=\"
\\\$s = App\\\Models\\\Student::where('hall_ticket', 'HALL_TICKET')->first();
\\\$e = App\\\Models\\\Exam::find(EXAM_ID);
echo 'Student: course='.\\\$s->course.' | medium='.\\\$s->medium.' | scheme='.\\\$s->scheme.' | group_code='.\\\$s->group_code.PHP_EOL;
echo 'Exam: semester='.\\\$e->semester.' | exam_type='.\\\$e->exam_type.PHP_EOL;
\\\$q = App\\\Models\\\Subject::forCourse(\\\$s->course)->forSemester(\\\$e->semester)->where('medium', \\\$s->medium)->where('scheme', \\\$s->scheme);
echo 'Without group_code: '.\\\$q->count().' subjects'.PHP_EOL;
if (\\\$s->group_code) {
    echo 'With group_code ('.\\\$s->group_code.'): '.((clone \\\$q)->where('group_code', \\\$s->group_code)->count()).' subjects'.PHP_EOL;
}
\\\$variants = App\\\Models\\\Subject::forCourse(\\\$s->course)->forSemester(\\\$e->semester)->selectRaw('medium, scheme, group_code, count(*) as cnt')->groupBy('medium','scheme','group_code')->get();
echo 'Available combos: '.json_encode(\\\$variants->toArray()).PHP_EOL;
\""
```

### Step 2 — Identify the mismatch

Compare `Student` values against `Available combos`:

| Field | Where to check |
|-------|---------------|
| `course` | Must exactly match `subjects.course` |
| `medium` | Must be `TM`, `EM`, or `BM` — matches `subjects.medium` |
| `scheme` | Integer year (e.g. `2019`) — matches `subjects.scheme` |
| `group_code` | If set, only subjects with same `group_code` are returned; set to `null` if no group applies |
| Supplementary only | Student must have `result != 'P'` in `results` for a regular exam in the same semester |

### Step 3 — Fix via Admin UI

Go to **Admin → Students → [student] → Edit** (`/admin/students/{hallTicket}/edit`).

Update the mismatched field(s) in the **Academic Details** section. Fields with an amber warning directly affect enrollment paper fetching.

### Step 4 — Verify

Reload `/student/enrollments/subjects?exam_id=EXAM_ID` as the student (use Admin → Login As).

---

## Common student issues

### Student cannot log in
- Check `is_active` is `true` on the student record (Admin → Students → Edit → Other section)
- Check login credentials: hall ticket + DOB (format `YYYY-MM-DD`) or DOST ID
- Legacy students (scheme < 2025) are redirected to `students.uasckuexams.in` via SSO

### Wrong course/scheme after legacy migration
- Use Admin → Students → Edit to correct `course`, `scheme`, `medium`, `group_code`
- Cross-check against the legacy DB (`uascexams_ebms.studentsmaster`) if needed

---

## Key files

- `app/Http/Controllers/Student/EnrollmentController.php` — `selectSubjects()` method
- `app/Http/Controllers/Admin/StudentController.php` — `edit()` / `update()` methods
- `app/Http/Requests/Admin/StudentUpdateRequest.php` — validation rules for student edits
- `resources/views/admin/students/edit.blade.php` — edit form
