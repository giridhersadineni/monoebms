# Repo Skills

Repo-specific skills for AI coding platforms used in `monoebms`.

## Skill files
- `codex/SKILL.md`
- `claude/SKILL.md`
- `claude-code/SKILL.md`
- `antigravity/SKILL.md`
- `google-antigravity/SKILL.md`

## Shared repo constraints
- Keep `studentsportal/` stable during strangler migration.
- Build all net-new capabilities in `apps/ebms-platform/`.
- Enforce OWASP + PII controls and avoid hardcoded secrets.
- Preserve single-domain module paths:
  - `/student/*`
  - `/backoffice/*`
  - `/postexam/*`

## Common validation commands
```bash
cd /Users/giridher/source/monoebms/apps/ebms-platform
php artisan route:list
php artisan migrate --force
php artisan test
composer audit --no-interaction
```
