# Subject Model

**File:** `app/Models/Subject.php`

Represents an examination paper (subject) in the college's curriculum.

---

## Key Columns

| Column | Description |
|--------|-------------|
| `code` | Paper code e.g. `ENG101` |
| `name` | Full paper name |
| `course` | `BA`, `BCOM`, `BSC` |
| `group_code` | Specialization group (nullable) |
| `semester` | Which semester this paper belongs to |
| `medium` | `english` / `telugu` |
| `scheme` | Academic scheme year |
| `paper_type` | `compulsory` / `elective` |
| `elective_group` | Groups elective options (for display) |

---

## Scopes

```php
Subject::forCourse('BA')         // WHERE course = 'BA'
Subject::forSemester(1)          // WHERE semester = 1
Subject::compulsory()            // WHERE paper_type = 'compulsory'
Subject::elective()              // WHERE paper_type = 'elective'
```

---

## Enrollment Usage

During enrollment, subjects are filtered by the student's `course`, `group_code`, `medium`, and `scheme`:

```php
Subject::forCourse($student->course)
    ->forSemester($exam->semester)
    ->where('medium', $student->medium)
    ->where('scheme', $student->scheme)
    ->where('group_code', $student->group_code)
```
