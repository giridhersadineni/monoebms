# Mini Agile Scrum Board

## Sprint
- Name: `Security Foundation + Student Migration Start`
- Duration: `2 weeks`
- Goal: `Stabilize secure platform baseline and start student feature migration from legacy portal.`

## Backlog
- `EBMS-001` Build student enrollment API parity layer in `apps/ebms-platform`
- `EBMS-002` Migrate challan/payment workflow with audit events
- `EBMS-003` Implement result read parity checks against legacy DB
- `EBMS-004` Add role-policy matrix for backoffice actions
- `EBMS-005` Add postexam maker-checker workflow skeleton
- `EBMS-006` Add staging synthetic checks for subdomain redirects

## In Progress
- `EBMS-007` Harden legacy high-risk endpoints (input validation + prepared queries)
- `EBMS-029` Start student enrollment API parity implementation

## Review / QA
- `EBMS-009` Validate Nginx single-domain routing template in staging
- `EBMS-010` Review security docs/runbook completeness with stakeholders

## Done
- `EBMS-011` Create Laravel modular monolith scaffold (`apps/ebms-platform`)
- `EBMS-012` Add module route groups (`/student`, `/backoffice`, `/postexam`)
- `EBMS-013` Implement module auth endpoints and guards
- `EBMS-014` Add security middleware (trace IDs + secure headers)
- `EBMS-015` Implement JSON error contract and exception rendering
- `EBMS-016` Add audit model/service and audit log channel
- `EBMS-017` Add secure signed upload flow for student module
- `EBMS-018` Remove hardcoded DB creds from legacy config files
- `EBMS-019` Add rewrite docs set and docs changelog
- `EBMS-020` Add platform-specific skills (`codex`, `claude-code`, `antigravity`)
- `EBMS-021` Add unit + feature tests expansion (auth contract, guard isolation, middleware context, upload and audit behavior)
- `EBMS-022` Add Docker run setup for app (`docker-compose`, Dockerfile, helper scripts)
- `EBMS-023` Add agent-friendly architecture section in root README
- `EBMS-024` Revise repo skills with frontmatter and platform-specific execution workflows
- `EBMS-025` Fix Docker app runtime mismatch by moving image to PHP 8.4
- `EBMS-031` Implement legacy schema migration (`students`, `exam_enrollments`) and student auth provider (HallTicket/DOB login)


## Defects (Found 2026-02-18)

### Critical
- `EBMS-BUG-001` **Root URL redirects to HTTPS instead of HTTP in local Docker**
  - **Steps:** `curl -v http://localhost:8000/` → `Location: https://localhost:8000/student`
  - **Expected:** Redirect to `http://localhost:8000/student` (HTTP) in local dev
  - **Root cause:** `URL::forceScheme('https')` via `config('app.force_https')` — default is `true` in `config/app.php` line 57. Docker compose sets `APP_FORCE_HTTPS: "false"` but root cause may be config caching or env precedence during request lifecycle vs tinker.
  - **Impact:** Browser gets `ERR_CONNECTION_CLOSED` after redirect; entire app unreachable from root URL

- `EBMS-BUG-002` **CSRF token mismatch (419) on all POST endpoints including login**
  - **Steps:** GET a CSRF cookie, then POST to `/student/auth/login` with `X-XSRF-TOKEN` header → 419
  - **Expected:** Login should succeed with proper CSRF token flow
  - **Root cause:** Module cookie path isolation requires module-scoped CSRF bootstrap; acquiring token from non-module routes does not produce a valid module login flow.
  - **Fix:** Added explicit module CSRF bootstrap endpoints (`/student/auth/csrf-cookie`, `/backoffice/auth/csrf-cookie`, `/postexam/auth/csrf-cookie`) and updated tests.
  - **Status:** ✅ Resolved on `2026-02-18`

- `EBMS-BUG-003` **Unauthenticated access to dashboards returns 500 Internal Server Error**
  - **Steps:** `curl http://localhost:8000/student/dashboard` → `500 Internal Server Error`
  - **Expected:** `401 Unauthenticated` JSON response
  - **Root cause:** `auth` middleware guest redirect defaulted to `route('login')`, which is undefined in module-only auth routing.
  - **Fix:** Added custom guest redirect behavior in `bootstrap/app.php` to return no redirect for `student/*`, `backoffice/*`, `postexam/*`, and `api/*`.
  - **Status:** ✅ Resolved on `2026-02-18` (`/student/dashboard` now returns `401` JSON without `Accept` header too)

### High
- `EBMS-BUG-004` **`GET /student`, `/backoffice`, `/postexam` return 404 Not Found**
  - **Steps:** `curl http://localhost:8000/student` → `404 Not Found` (HTML page)
  - **Expected:** Either redirect to login or return a meaningful response
  - **Root cause:** No route defined for the bare module prefix paths. Root `/` redirects to `/student` which 404s.
  - **Impact:** Users landing on module root URLs see a generic Laravel 404 page

- `EBMS-BUG-005` **Session cookies set with `Secure` flag despite `SESSION_SECURE_COOKIE=false`**
  - **Steps:** Any request to module routes → `Set-Cookie: ... secure; samesite=lax`
  - **Expected:** Cookies should NOT have `secure` flag when running over HTTP in local dev
  - **Verification update:** Re-tested on `2026-02-18`; module cookies emit with `secure=false` in local Docker.
  - **Status:** ℹ️ Not reproducible after re-validation; keep open for monitoring if it reappears.

- `EBMS-BUG-006` **7 of 19 PHPUnit tests failing in Docker**
  - **Failing tests:**
    - `SecureUploadServiceTest::it stores allowed files` — GD extension not installed in Docker image
    - `ApiContractTest::login validation errors follow standard contract` — Gets 419 instead of 422
    - `ModuleAuthTest::student login and dashboard access` — Gets 419 instead of 200
    - `ModuleAuthTest::login rejects invalid password` — Gets 419 instead of 401
    - `ModuleAuthTest::login is rate limited after repeated failures` — Gets 419 instead of 401
    - `SecureUploadTest::student can upload with signed url` — Gets 419 instead of 200
    - `SecureUploadTest::upload rejects invalid extension` — Gets 419 instead of 200
  - **Root cause:** Docker image lacked `php-gd`; POST-heavy feature tests also required deterministic CSRF handling in test runtime.
  - **Fix:** Added GD packages/extension in `docker/Dockerfile` and stabilized CSRF handling for affected feature tests.
  - **Status:** ✅ Resolved on `2026-02-18` (`21` tests passing in Docker)

### Medium
- `EBMS-BUG-007` **`X-Powered-By: PHP/8.4.18` header exposed on all responses**
  - **Steps:** `curl -sI http://localhost:8000/up` → `X-Powered-By: PHP/8.4.18`
  - **Expected:** Header should be suppressed (information disclosure per OWASP)
  - **Fix:** Add `header_remove('X-Powered-By')` in middleware or set `expose_php = Off` in `php.ini`

- `EBMS-BUG-008` **CORS `allowed_origins` set to `*` (wildcard) on API routes**
  - **Steps:** `curl -sI http://localhost:8000/api/v1/ping` → `Access-Control-Allow-Origin: *`
  - **Expected:** Restrict to known origins (e.g., the application domain)
  - **Root cause:** Default Laravel CORS config in `config/cors.php` not customized
  - **Impact:** Any website can make cross-origin API requests

- `EBMS-BUG-009` **CSP violation on `/up` health check page**
  - **Steps:** `/up` page loads `<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4">` but CSP header says `script-src 'self'`
  - **Expected:** Either update CSP to allow the CDN or remove the external script dependency
  - **Impact:** Health check page renders broken in strict CSP-enforcing browsers

### Low
- `EBMS-BUG-010` **405 error response leaks supported HTTP methods**
  - **Steps:** `curl -X DELETE http://localhost:8000/student/auth/login -H "Accept: application/json"` → `"The DELETE method is not supported for route student/auth/login. Supported methods: POST."`
  - **Expected:** Generic "Method not allowed" without listing supported methods
  - **Impact:** Minor information disclosure; attacker can enumerate valid methods

## Blocked
- None as of `2026-02-18` (auth-flow blockers `EBMS-BUG-002` and `EBMS-BUG-003` resolved)

## Next Sprint Candidates
- `EBMS-026` Student profile update migration with FormRequests
- `EBMS-027` Student exam registration migration end-to-end
- `EBMS-028` Backoffice exam planning module bootstrap
- `EBMS-030` DAST authenticated scan profile in CI

## Definition of Done (mini)
- Code merged with passing tests
- Security controls applied (validation, authz, audit)
- Docs updated including `apps/ebms-platform/docs/CHANGELOG.md`
- Manual verification notes recorded in PR
