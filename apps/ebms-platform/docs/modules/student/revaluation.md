# Revaluation

**Controller:** `app/Http/Controllers/Student/RevaluationController.php`

Students can apply for revaluation of answer scripts when `exam.revaluation_open = true`.

---

## Routes

```
GET  /student/revaluation/create         → selectEnrollment
POST /student/revaluation/subjects       → selectSubjects
POST /student/revaluation/confirm        → confirm
POST /student/revaluation                → store
GET  /student/revaluation/{enrollment}   → show
```

---

## Models

```php
RevaluationEnrollment → hasMany RevaluationSubject
                       → belongsTo ExamEnrollment
```

---

## Eligibility

- The exam must have `revaluation_open = true`
- The student must have a result for the enrollment
- Students can only apply for papers they appeared in
