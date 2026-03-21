# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Overview

This is the **EBMS monorepo** — a Strangler Fig migration from legacy multi-domain PHP portals to a single-domain Laravel 12 modular monolith. All net-new development happens in `apps/ebms-platform/`. Legacy apps (`students.uasckuexams.in/`, `backoffice.uasckuexams.in/`, `postexams.uasckuexams.in/`) are in maintenance mode — keep them stable, do not add features.

## Primary App: `apps/ebms-platform/`

### Commands

```bash
# Install dependencies
composer install && npm install

# Local development (runs Laravel + queue + logs + Vite concurrently)
composer run dev

# Run all tests
php artisan test

# Run a single test file
php artisan test tests/Feature/Student/AuthTest.php

# Run tests with coverage (CI requires minimum 75%)
php artisan test --coverage --min=75

# Frontend assets
npm run dev        # watch mode
npm run build      # production build

# Database migrations
php artisan migrate
php artisan migrate:fresh --seed

# Legacy data migration (production only)
php artisan ebms:migrate-legacy --table=all
php artisan ebms:migrate-legacy --table=results --dry-run
php artisan ebms:migrate-legacy --table=results --exam-id=326   # filter by legacy EXAMID
php artisan ebms:migrate-legacy --table=enrollments --exam-id=326

# Production server (SSH — credentials in apps/ebms-platform/deploy.env)
# Always use --force on production for migrate
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan migrate"
```

### Docker (local dev)

```bash
docker compose up -d            # Start app, nginx, MariaDB, Redis
docker compose exec app bash    # Shell into PHP container
docker compose down
```

Local app runs at `http://localhost:8000`.

### Architecture: Three-Module Monolith

The app enforces **path-scoped isolation** between modules:

| Module | URL Prefix | Route File | Guard | Session Cookie |
|--------|------------|------------|-------|---------------|
| Student | `/student/*` | `routes/student.php` | `student` | `STUDENT_SESS` |
| Admin | `/admin/*` | `routes/admin.php` | `admin` | `ADMIN_SESS` |

Routes are registered in `bootstrap/app.php` with module prefixes and route name prefixes (`student.`, `admin.`). The `role` middleware alias maps to `RequireRole`.

### Authentication

Two custom auth providers registered in `config/auth.php`:
- **`ebms-student`** — `StudentUserProvider`: authenticates via hall ticket + DOB **or** DOST ID (legacy compatibility)
- **`ebms-admin`** — `AdminUserProvider`: authenticates via username + password from `admin_users` table

Always use `Auth::guard('student')` or `Auth::guard('admin')` — never the default guard.

### Key Models & Relationships

```
Student → hasMany(ExamEnrollment) → hasMany(ExamEnrollmentSubject)
                                  → hasOne(Result)
                                  → hasOne(Gpa)
ExamEnrollment → belongsTo(Exam)
               → belongsTo(Student)
RevaluationEnrollment → belongsTo(ExamEnrollment)
                      → hasMany(RevaluationSubject)
Student → hasOne(Grade)  ← cumulative CGPA/division
```

### Database

- **Connection:** `mariadb` (primary), `legacy` (read-only, `LEGACY_DB_*` env vars, points to `uascexams_ebms`)
- **Test DB:** SQLite in-memory (configured in `phpunit.xml`)
- The `audit_events.actor_id` is a polymorphic unsignedBigInteger (no FK constraint) — references either `students.id` or `admin_users.id`
- The `exams` table uses flat fee columns (no `fee_json`):

| Column | Type | Purpose |
|--------|------|---------|
| `fee_mode` | `varchar(20)` default `flat` | `flat` or `per_subject` |
| `fee_regular` | `unsignedInteger` nullable | Flat fee, or fallback for supply/improvement when subject count exceeds threshold |
| `fee_per_subject` | `unsignedInteger` nullable | Per-paper fee for supplementary exams (≤ 2 subjects) |
| `fee_improvement` | `unsignedInteger` nullable | Per-paper fee for improvement exams (no threshold) |
| `fee_fine` | `unsignedInteger` nullable | Late fine — 0 by default, manually set during grace period |

### Security Middleware (applied globally)

- **`SecurityHeadersMiddleware`** — Sets CSP, HSTS (HTTPS only), removes `X-Powered-By`, `Server`, `Allow` headers. Skips the `/up` health check path.
- **`AuditRequestMiddleware`** — Injects `X-Trace-Id` (UUID) on every request for audit correlation.
- Rate limiting: 5 login attempts per 60s per `IP:login_id`.

### Services

- `FeeCalculatorService` — Calculates enrollment fee via `Exam::calculateFee(int $subjectCount)`. Fee logic by exam type:
  - `regular` / `flat` mode: `fee_regular` (flat, always)
  - `supplementary` / `per_subject` mode: `fee_per_subject × count` for ≤ 2 subjects, else `fee_regular`
  - `improvement`: `fee_improvement × count` (always per-subject, no threshold)
  - `fee_fine` (default 0) is always added — set to 0 until grace period, then updated manually by admin
- `GpaCalculatorService` — SGPA/CGPA calculation
- `ChallanPdfService` — Generates challan PDF using dompdf

### Frontend

Blade templates with inline styles (CSS variables from `resources/css/app.css`). No JS framework — vanilla JS only where needed, using `nonce="{{ $csp_nonce ?? '' }}"` on all `<script>` tags for CSP compliance. Tailwind CSS 4.0 utility classes used for responsive breakpoints (`sm:`, `hidden sm:block`, etc.).

### Legacy Data Migration (`ebms:migrate-legacy`)

`app/Console/Commands/MigrateLegacyData.php` — migrates from legacy `uascexams_ebms` DB:
- Uses in-memory maps (`$examMap`, `$subjectMap`, `$studentMap`, `$enrollmentMap`) rebuilt via `refreshMaps()` before each step
- `ensure*()` methods (`ensureExam`, `ensureStudent`, `ensureEnrollment`, `ensureSubject`) create missing records rather than skipping rows
- Legacy `INT` column (MySQL reserved word) accessed as `$row->{'INT'}`
- `0000-00-00` dates sanitized to `null` via `$safeDate` helper
- Run order: `subjects → exams → students → admin_users → enrollments → results → gpas → grades`
- `--exam-id=<LEGACY_EXAMID>` filters `enrollments` and `results` to a single legacy exam
- The `results` table includes 9 moderation columns inline in migration `000007`: `marks_secured`, `etotal`, `itotal`, `floatation_marks`, `float_deduct`, `fl_scriptcode`, `moderation_marks`, `ac_marks`, `is_moderated` (migration `000013` was merged in and deleted — do not recreate it)
- Legacy `examsmaster` fee columns map as: `FEE` → `fee_per_subject` (supply) or `fee_regular` (regular); `ABOVE2SUBS` → `fee_regular` (supply); `IMPROVEMENT` → `fee_improvement`; `FINE` → `fee_fine`

### Production Deployment

- Server credentials: `apps/ebms-platform/deploy.env`
- SSH: `ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171`
- App path: `/home/uascexams/ebmsnova.uasckuexams.in`
- Pass `--force` to `php artisan migrate` on production only when running non-interactively (e.g. via SSH one-liner); confirm manually when possible
- **Deploy order matters**: upload route files BEFORE uploading layout/view files that reference those routes. A layout referencing an unregistered route causes ERR_TOO_MANY_REDIRECTS across the entire admin portal.
- After every deploy run: `php artisan optimize:clear` to flush route/view/config caches
- Frontend: run `npm run build` locally and scp `public/build/` to server — Node.js is not available on the server

### Known Open Issues (production)

- `Route [login] not defined` — the `Authenticate` middleware redirects unauthenticated requests to `route('login')`, but under the `admin.` prefix the correct route is `admin.login`. Needs a custom `redirectTo` in the auth middleware or a `withExceptions` redirect override.
- `getMonthNameAttribute(): Argument #1 must be of type string, null given` — legacy-migrated exams have a numeric `month` stored as a string but some rows have null. Seen on `admin/exams/index`. Pre-existing.

## Constraints

- Single domain, module paths `/student/*` and `/admin/*` (future: `/backoffice/*`, `/postexam/*`) must be preserved
- OWASP controls and PII handling required — `aadhaar`, `dob`, `dost_id`, `email` are `$hidden` on models
- No hardcoded secrets; use env vars
- `studentsportal/` legacy app must remain stable during migration

## AI Agent Skills

Agent-specific skill files live in `skills/<platform>/SKILL.md`. Each file is a self-contained prompt fragment loaded at session start.

### Available Skills

| Platform | Path | Purpose |
|----------|------|---------|
| Claude (claude.ai) | `skills/claude/SKILL.md` | Claude workflows — migration & security tasks |
| Claude Code (CLI) | `skills/claude-code/SKILL.md` | Claude Code CLI workflows |
| Codex | `skills/codex/SKILL.md` | OpenAI Codex workflows |
| Antigravity | `skills/antigravity/SKILL.md` | Antigravity agent workflows |
| Google Antigravity | `skills/google-antigravity/SKILL.md` | Google Antigravity agent workflows |

### How to load a skill

- **Claude / Claude Code**: The file is referenced via `skills:` in `.claude/settings.json` or read manually at session start.
- **Codex**: Pass the file content as a system prompt prefix.
- **Other platforms**: Prepend the SKILL.md content to the agent system prompt before the task prompt.

### Shared enforcement rules (all skills)

1. Net-new code only in `apps/ebms-platform/`.
2. Respect module path boundaries: `/student/*`, `/admin/*` (future: `/backoffice/*`, `/postexam/*`).
3. Never hardcode secrets — use env vars.
4. Keep `studentsportal/` stable (hotfixes/security only).
5. After every change run `php artisan route:list && php artisan test`.

### Adding or updating a skill

1. Create/edit `skills/<platform>/SKILL.md`.
2. Keep the YAML front-matter (`name`, `description`) in sync.
3. Update the table above.
4. Commit the skill file alongside any code it references.
