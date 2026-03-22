---
name: monoebms-google-antigravity
description: Use this skill when requests explicitly target Google Antigravity workflows in monoebms, including references to antigravity automation, deterministic execution, reliability validation, or .agents/google-antigravity.md.
---

# monoebms Google Antigravity Skill

## Start here
1. Read `/Users/giridher/source/monoebms/AGENTS.md`.
2. Read `/Users/giridher/source/monoebms/.agents/google-antigravity.md`.
3. Identify whether the task affects platform code, infra, tests, or release operations.

## Execution rules
1. Prefer deterministic, repeatable command paths.
2. Keep net-new work in `apps/ebms-platform/`.
3. Restrict `studentsportal/` changes to security-only or critical hotfixes.
4. Preserve single-domain module routes and session isolation behavior.
5. Preserve trace IDs, security headers, and sensitive-action audit trails.
6. Never hardcode credentials or keys.

## Minimum validation
- `cd /Users/giridher/source/monoebms/apps/ebms-platform`
- `docker compose run --rm app php artisan test`
- `docker compose run --rm app php artisan route:list`
- `composer audit --no-interaction`

## Handoff format
1. What changed and why.
2. Validation evidence.
3. Remaining defects, risks, or follow-up actions.
