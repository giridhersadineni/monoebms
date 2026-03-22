---
name: monoebms-claude
description: Use this skill when requests explicitly target Claude workflows in monoebms, including references to Claude, .agents/claude.md, or Claude-specific implementation/reporting style for migration and security tasks.
---

# monoebms Claude Skill

## Start here
1. Read `/Users/giridher/source/monoebms/AGENTS.md`.
2. Read `/Users/giridher/source/monoebms/.agents/claude.md`.
3. Confirm whether the request is a legacy hotfix or a net-new platform change.

## Execution rules
1. Build net-new functionality only in `apps/ebms-platform/`.
2. Touch `studentsportal/` only for high-severity hotfixes and security fixes.
3. Preserve module path boundaries:
- `/student/*`
- `/backoffice/*`
- `/postexam/*`
4. Keep module auth/guard/session isolation and CSRF bootstrap endpoints intact.
5. Keep API error shape: `{ code, message, trace_id, details }`.
6. Never hardcode secrets.

## Minimum validation
- `cd /Users/giridher/source/monoebms/apps/ebms-platform`
- `php artisan route:list`
- `php artisan test`
- `composer audit --no-interaction`

## Handoff format
1. List absolute file paths changed.
2. Report validation commands with pass/fail.
3. State unresolved risks or blockers explicitly.
