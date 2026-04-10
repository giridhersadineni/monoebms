# EBMS Platform

Examination Branch Management System for University Arts & Science College, Hanamkonda (Autonomous).

This app is the primary active product inside the `monoebms` monorepo. It is a Laravel 12 modular monolith that incrementally replaces the older multi-domain PHP portals with a single-domain application.

## What This App Is

- Net-new development happens in `apps/ebms-platform/`
- Legacy portals remain in maintenance mode and should stay stable
- The current production target is `https://ebmsnova.uasckuexams.in`
- Local development is designed around Docker and standard Laravel tooling

## Module Boundaries

The application is path-scoped into two modules:

| Module | Path Prefix | Route File | Auth Guard |
|---|---|---|---|
| Student | `/student/*` | `routes/student.php` | `student` |
| Admin | `/admin/*` | `routes/admin.php` | `admin` |

Routing and guest/user redirects are configured in `bootstrap/app.php`.

## Core Domain

The main business entities are:

- `Student`
- `Exam`
- `ExamEnrollment`
- `ExamEnrollmentSubject`
- `Result`
- `Gpa`
- `Grade`
- `RevaluationEnrollment`
- `RevaluationSubject`

At a high level:

```text
Student -> ExamEnrollment -> ExamEnrollmentSubject
        -> Result
        -> Gpa
Student -> Grade
```

The most important user-facing workflows are:

- Student login, profile, enrollment, challan, result viewing, revaluation
- Admin login, student management, enrollment management, exam setup, paper management, fee rules, grade sheets, feature flags

## Authentication

The app uses two custom auth providers:

- `ebms-student`: hall ticket + DOB, with DOST ID fallback for legacy compatibility
- `ebms-admin`: username + password from `admin_users`

Always use explicit guards:

```php
Auth::guard('student')
Auth::guard('admin')
```

Do not rely on the default guard.

## Security Posture

Global middleware adds baseline security and request tracing:

- `SecurityHeadersMiddleware`
- `AuditRequestMiddleware`

Current protections include:

- CSP with per-request nonce for scripts
- HSTS on HTTPS requests
- `X-Trace-Id` correlation header on every request
- Removal of `X-Powered-By`, `Server`, and `Allow` headers where applicable
- Login throttling at 5 attempts per 60 seconds

Models also hide sensitive fields such as `aadhaar`, `dob`, and `dost_id`.

## Fees, Results, and GPA

Fee calculation currently lives in `Exam::calculateFee()` and is wrapped by `FeeCalculatorService`.

Behavior by exam type:

- Regular exams: flat fee
- Supplementary exams: reduced fee up to 2 papers, regular fee beyond that
- Improvement exams: per-subject fee with no threshold
- Fine is always added on top when configured

Academic computation is centered in `GpaCalculatorService`, including:

- grade derivation
- SGPA calculation
- CGPA/division calculation
- floatation handling

## Legacy Migration

Legacy data migration is handled by:

```bash
php artisan ebms:migrate-legacy
```

The implementation lives in `app/Console/Commands/MigrateLegacyData.php` and migrates data from the read-only legacy database into the new schema.

Important characteristics:

- chunked processing for large tables
- in-memory lookup maps refreshed between phases
- `ensure*()` helpers create missing exams, students, subjects, and enrollments instead of silently skipping bad rows
- support for filtering by legacy `EXAMID`
- support for dry runs

This command is operationally important and should be treated carefully in production.

## Local Development

### Prerequisites

- PHP 8.2+
- Composer
- Node.js / npm
- Docker Desktop if using the provided containers

### Common Commands

```bash
# Install dependencies
composer install
npm install

# Local development
composer run dev

# Frontend assets
npm run dev
npm run build

# Database
php artisan migrate
php artisan migrate:fresh --seed

# Tests
php artisan test
php artisan test tests/Feature/Student/AuthTest.php
php artisan test --coverage --min=75
```

### Docker

```bash
docker compose up -d
docker compose exec app bash
docker compose down
```

The local app is intended to run at `http://localhost:8000`.

## Testing

The project includes:

- Feature tests for student/admin auth and enrollment flows
- Unit tests for fee and GPA logic
- Browser tests via Laravel Dusk

Tests are configured to use SQLite in memory through `phpunit.xml`.

## Deployment Notes

Production deployment is still fairly manual and order-sensitive.

Key rules:

- Build frontend assets locally with `npm run build`
- Upload route files before Blade/layout changes that reference those routes
- Run `php artisan migrate --force` when deploying migrations non-interactively
- Always run `php artisan optimize:clear` after deploy

See `docs/deployment.md` for the full procedure.

## Known Caveats

- Some documentation still reflects older fee-column naming and should be cross-checked against current code
- The legacy migration command is intentionally forgiving, which is useful operationally but can create fallback records if source data is incomplete
- Some repo-level AI skill references described outside this folder do not match the current on-disk layout

## Documentation Map

More detailed docs live in `docs/`:

- `docs/architecture.md`
- `docs/database.md`
- `docs/deployment.md`
- `docs/setup.md`
- `docs/modules/`
- `docs/models/`
- `docs/services/`
- `docs/legacy/`

## Recommended Validation Before Handoff

When PHP is available in your environment, the minimum sanity checks are:

```bash
php artisan route:list
php artisan test
composer audit --no-interaction
```
