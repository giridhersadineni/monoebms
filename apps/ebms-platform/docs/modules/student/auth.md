# Student Authentication

**Controller:** `app/Http/Controllers/Student/AuthController.php`

---

## Login Methods

Students can log in with either:

1. **Hall Ticket + Date of Birth**
2. **Hall Ticket + DOST ID** (legacy compatibility)

---

## Routes

```
GET  /student/login       → showLogin
POST /student/login       → login  (throttle: 5/min per IP:hall_ticket)
POST /student/logout      → logout
```

---

## Guard

```php
Auth::guard('student')
```

Provider: `App\Auth\StudentUserProvider` (registered as `ebms-student` in `config/auth.php`)

Session cookie: `STUDENT_SESS`

---

## Rate Limiting

Login attempts are throttled to **5 per 60 seconds** per `IP:hall_ticket` combination. Exceeding the limit returns HTTP 429.
