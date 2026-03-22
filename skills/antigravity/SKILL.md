---
name: monoebms-antigravity
description: Use this skill when Antigravity agents execute monoebms migration or reliability tasks requiring security-first implementation, operational artifacts, and repeatable validation.
---

# monoebms Antigravity Skill

## Use this skill when
- Running automation-oriented migration tasks for EBMS.
- Updating platform security, infra routing, test automation, or rollout readiness.

## Priority outcomes
1. Security-first behavior in `apps/ebms-platform`.
2. Single-domain readiness (`/student`, `/backoffice`, `/postexam`).
3. Operationally safe migration from legacy runtime.

## Agent workflow
1. Read current standards first.
- `apps/ebms-platform/docs/rewrite/01-secure-coding-standard.md`
- `apps/ebms-platform/docs/rewrite/02-risk-register.md`

2. Implement in platform paths by default.
- App code: `apps/ebms-platform/app/Modules/*`
- Shared controls: `apps/ebms-platform/app/Shared/*`
- Routes: `apps/ebms-platform/routes/modules/*`

3. Keep operational assets in sync.
- Nginx/domain templates: `apps/ebms-platform/infra/nginx/`
- Pipeline checks: `apps/ebms-platform/.github/workflows/`
- Migration/runbook docs: `apps/ebms-platform/docs/rewrite/`

4. Preserve migration hygiene.
- Keep legacy edits minimal and security-driven only.
- Keep generated inventories in `apps/ebms-platform/docs/rewrite/`.

5. Validate and publish outcomes.
- Run route, migration, test, and dependency audit checks.
- Include touched files and pass/fail status in final summary.

## Required controls
- Module guard isolation.
- JSON error contract with trace IDs.
- Security headers.
- Audit logging with redaction.
- Signed and validated upload flows.
