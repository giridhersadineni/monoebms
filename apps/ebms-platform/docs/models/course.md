# Course & CourseGroup Models

## Course

**File:** `app/Models/Course.php`

Represents a degree programme (BA, BCOM, BSC).

### Relationships

```php
$course->groups()        // HasMany → CourseGroup (all, ordered by code)
$course->activeGroups()  // HasMany → CourseGroup (is_active = true)
```

### Key Columns

| Column | Description |
|--------|-------------|
| `code` | e.g. `BA`, `BCOM`, `BSC` |
| `name` | Full name e.g. "Bachelor of Arts" |
| `total_semesters` | e.g. 6 |
| `is_active` | Whether the course is active |

---

## CourseGroup

**File:** `app/Models/CourseGroup.php`

Represents a specialization group within a course.

### Relationships

```php
$group->course()   // BelongsTo → Course
```

### Key Columns

| Column | Type | Description |
|--------|------|-------------|
| `course_id` | FK | |
| `code` | varchar | e.g. `HEP`, `CA`, `MPCs` |
| `name` | varchar | Full group name |
| `mediums` | JSON array | Allowed mediums e.g. `["english", "telugu"]` |
| `schemes` | JSON array | Allowed scheme years |
| `is_active` | boolean | |

### Known Groups

| Course | Groups |
|--------|--------|
| BA | HEP, HEPo, HES, HPS, HPN, HEG, HPG, HGE, HGG, HPE, HPC, EPG, MiCA |
| BCOM | CA, FIN, GEN |
| BSC | MPCs, MPC, MPCS, CBZ, CZBt, MZC, MPE, MSCs, CSC |
