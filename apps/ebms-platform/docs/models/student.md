# Student Model

**File:** `app/Models/Student.php`

Represents a student account. Used as the authenticated user for the `student` guard.

---

## PII Fields

The following fields are listed in `$hidden` and are **never serialized** to JSON/arrays:

- `aadhaar`
- `date_of_birth`
- `dost_id`
- `email` (if present)

---

## Key Attributes

| Attribute | Description |
|-----------|-------------|
| `hall_ticket` | Unique login identifier |
| `course` | `BA`, `BCOM`, or `BSC` |
| `group_code` | Specialization group e.g. `HEP`, `CA`, `MPCs` |
| `semester` | Current enrolled semester |
| `medium` | `english` or `telugu` |
| `scheme` | Academic scheme year |
| `photo_path` | Relative path on `public` disk |
| `signature_path` | Relative path on `public` disk |

---

## Accessors

```php
$student->photo_url      // Full public URL or null
$student->signature_url  // Full public URL or null
```

Photo/signature files are stored on the `public` disk at:
- `students/photos/{hall_ticket}.jpg`
- `students/signatures/{hall_ticket}.jpg`

---

## Relationships

```php
$student->enrollments()              // HasMany → ExamEnrollment
$student->revaluationEnrollments()   // HasMany → RevaluationEnrollment
$student->gpas()                     // HasMany → Gpa
$student->grade()                    // HasOne  → Grade
```

---

## Auth Usage

```php
// In student controllers
$student = Auth::guard('student')->user();

// Check authenticated
Auth::guard('student')->check()
```
