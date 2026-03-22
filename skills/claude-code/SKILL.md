---
name: monoebms-claude-code
description: Use this skill when Claude-Code is making monoebms changes and must follow strangler migration constraints, strict security controls, and clear implementation summaries.
---

# monoebms Claude-Code Skill

## Use this skill when
- Claude-Code is asked to change code under `monoebms`.
- The task affects migration, security, infra routing, or cross-module behavior.

## Baseline context
- Legacy app remains live: `studentsportal/`
- Target app for new work: `apps/ebms-platform/`
- Migration model: strangler rollout with single-domain module paths.

## Required approach
1. Keep change intent explicit.
- Confirm whether the task is `legacy hotfix` or `new platform change`.

2. Implement with boundary discipline.
- Net-new work belongs in `apps/ebms-platform/`.
- Legacy edits must be minimal and reversible.

3. Enforce secure coding standards.
- Input validation and authz on every protected route.
- No SQL string concatenation for untrusted input.
- No leakage of SQL/internal errors to clients.
- Maintain auditability for sensitive operations.

4. Keep operations aligned.
- If route/domain behavior changes, update Nginx template and docs.
- If docs change, update `apps/ebms-platform/docs/CHANGELOG.md`.

5. Verify and report.
- Run lint/tests/checks in `apps/ebms-platform`.
- Summarize with concrete file paths and validation outcomes.

## Minimum verification commands
- `php artisan route:list`
- `php artisan migrate --force`
- `php artisan test`
- `composer audit --no-interaction`

## Do not
- Broadly refactor legacy PHP while migrating unrelated features.
- Leave security assumptions undocumented.
