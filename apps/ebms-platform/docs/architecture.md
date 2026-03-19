# Architecture

## System Overview

EBMS Platform is a **Strangler Fig migration** — legacy multi-domain PHP portals are being incrementally replaced by a single-domain Laravel 12 modular monolith.

```
https://ebmsnova.uasckuexams.in
│
├── /student/*    ← Student module
└── /admin/*      ← Admin module
```

Both modules share the same database, application code, and Docker container while remaining **path-scoped isolated** from each other.

---

## Module Structure

| Module | URL Prefix | Route File | Auth Guard | Session Cookie |
|--------|-----------|------------|------------|---------------|
| Student | `/student/*` | `routes/student.php` | `student` | `STUDENT_SESS` |
| Admin | `/admin/*` | `routes/admin.php` | `admin` | `ADMIN_SESS` |

Modules are registered in `bootstrap/app.php` with their respective prefixes and route name prefixes (`student.`, `admin.`).

### Directory Layout

```
app/Http/Controllers/
├── Admin/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── ExamController.php
│   ├── ExamFeeRuleController.php
│   ├── EnrollmentController.php
│   ├── StudentController.php
│   ├── CourseController.php
│   ├── SubjectController.php
│   ├── GradeSheetController.php
│   └── DFormController.php
└── Student/
    ├── AuthController.php
    ├── DashboardController.php
    ├── EnrollmentController.php
    ├── ChallanController.php
    ├── ProfileController.php
    ├── ResultController.php
    └── RevaluationController.php
```

---

## Authentication

### Student Auth

**Provider:** `App\Auth\StudentUserProvider` (registered as `ebms-student`)

Students authenticate via **either**:
1. Hall Ticket Number + Date of Birth
2. Hall Ticket Number + DOST ID (legacy compatibility)

```php
// Always use the named guard
Auth::guard('student')->user()
Auth::guard('student')->check()
```

### Admin Auth

**Provider:** `App\Auth\AdminUserProvider` (registered as `ebms-admin`)

Admins authenticate via username + password against the `admin_users` table.

```php
Auth::guard('admin')->user()
```

> **Never** use the default `Auth` facade without specifying a guard — the default guard is unused and will always return `null`.

### Rate Limiting

Login endpoints are throttled to **5 attempts per 60 seconds** per `IP:login_id`.

---

## Security Middleware

Applied globally to all requests:

### `SecurityHeadersMiddleware`

Sets response headers on every request:

| Header | Value |
|--------|-------|
| `Content-Security-Policy` | Nonce-based script/style policy |
| `Strict-Transport-Security` | HTTPS only (skipped on HTTP) |
| `X-Content-Type-Options` | `nosniff` |
| `X-Frame-Options` | `DENY` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |

Removes: `X-Powered-By`, `Server`, `Allow`.

Skips the `/up` health check path.

### `AuditRequestMiddleware`

Injects an `X-Trace-Id` UUID on every request for audit correlation. All audit events reference this trace ID.

### CSP and Inline Scripts

All Blade templates must use the `$csp_nonce` variable on `<script>` tags:

```blade
<script nonce="{{ $csp_nonce ?? '' }}">
  // your JS here
</script>
```

`blob:` URLs are **not** allowed by CSP. Use `FileReader.readAsDataURL()` for in-browser file handling.

---

## Frontend

- **CSS framework**: Tailwind CSS 4.0 (utility classes, `resources/css/app.css`)
- **JS**: Vanilla JS only — no framework. All scripts in nonce-tagged `<script>` blocks or via `@push('scripts')`.
- **Build**: Vite 7 (`npm run build` locally; upload `public/build/` to server — Node is not available on production).
- **Fonts** (admin): Figtree, JetBrains Mono, Crimson Pro — loaded from Google Fonts.

---

## Request Lifecycle

```
Request
  └── SecurityHeadersMiddleware (headers + CSP nonce)
  └── AuditRequestMiddleware (X-Trace-Id)
  └── Rate limiter (login routes only)
  └── Module route group (admin.* / student.*)
      └── auth:admin | auth:student middleware
          └── Controller → Service → Model → DB
              └── View (Blade)
```
