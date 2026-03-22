---
name: monoebms-codex
description: Use this skill when Codex is implementing or reviewing code in monoebms, especially migration work from studentsportal to apps/ebms-platform with mandatory OWASP and operational guardrails.
---

# monoebms Codex Skill

## Use this skill when
- The task touches `monoebms` application code or architecture.
- The task involves migration from `studentsportal/` to `apps/ebms-platform/`.
- The task involves security hardening, auth/session behavior, uploads, or audit logging.

## Repo map
- Legacy runtime: `studentsportal/`
- New platform: `apps/ebms-platform/`
- Rewrite docs: `apps/ebms-platform/docs/rewrite/`
- Docs changelog: `apps/ebms-platform/docs/CHANGELOG.md`

## Execution workflow
1. Classify scope.
- If this is net-new functionality, implement in `apps/ebms-platform/`.
- If this is legacy hotfix/security patch, apply minimal targeted changes in `studentsportal/`.

2. Keep module boundaries.
- Student: `/student/*`
- Backoffice: `/backoffice/*`
- Postexam: `/postexam/*`

3. Apply required security controls.
- Parameterized data access only.
- FormRequest validation for write operations.
- JSON error contract with `trace_id`.
- Module-scoped guards and secure session config.
- Security headers middleware.
- Audit events for identity/finance/enrollment/result actions.
- Signed uploads with server-side MIME/extension checks.

4. Validate before handoff.
- `php artisan route:list`
- `php artisan migrate --force`
- `php artisan test`
- `composer audit --no-interaction`

5. Update documentation if behavior changed.
- Update `apps/ebms-platform/docs/rewrite/*` as needed.
- Append `apps/ebms-platform/docs/CHANGELOG.md` for docs changes.

## Do not
- Add planned business features to `studentsportal/`.
- Commit secrets or hardcoded credentials.
- Skip tests for security-sensitive changes.
