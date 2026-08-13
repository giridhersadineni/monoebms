---
name: ebms-deploy
description: Deploy the EBMS platform (apps/ebms-platform/) to the production server over SSH. Handles build, file upload, migrations, and cache clearing in the correct order.
---

# EBMS Deploy

Deploy `apps/ebms-platform/` to the production cPanel server.

## When to use

When the user says "deploy", "push to production", "deploy to server", or similar for the EBMS platform app.

## Server details

- **SSH:** `ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171`
- **App path:** `/home/uascexams/ebmsnova.uasckuexams.in`
- **Node.js is NOT available on the server** — build assets locally and scp `public/build/`
- **The SSH key (`~/.ssh/ebmsnova`) is passphrase-protected.** Direct `ssh`/`scp` fails without loading the key into ssh-agent first. Always use this pattern at the start of every deploy:

```bash
eval $(ssh-agent -s) && \
printf '#!/bin/sh\necho "PASSPHRASE"' > /tmp/askpass.sh && chmod +x /tmp/askpass.sh && \
DISPLAY=fake SSH_ASKPASS=/tmp/askpass.sh ssh-add ~/.ssh/ebmsnova 2>/dev/null && \
echo "Key loaded"
# then run scp/ssh in the same shell — rm /tmp/askpass.sh when done
```

## Instructions

Follow these steps in order. Do not skip steps.

### 1. Identify changed files

Ask the user which files changed, or infer from the current conversation. Group them into:
- **Route files** (`routes/*.php`)
- **PHP files** (controllers, models, requests, commands, middleware, config)
- **Blade views** (`resources/views/**/*.blade.php`)
- **Frontend source** (`resources/css/`, `resources/js/`) — requires asset rebuild

### 2. Build frontend assets (if CSS/JS or Blade views changed)

```bash
cd apps/ebms-platform && npm run build
```

Check the output for the new hashed filenames (e.g. `app-XXXX.css`, `app-XXXX.js`).

### 3. Upload files — ROUTES BEFORE VIEWS

**Critical:** Always upload route files before layout/view files. A layout that references an unregistered route crashes the entire admin portal with ERR_TOO_MANY_REDIRECTS.

Upload in this order:
1. Route files (`routes/*.php`)
2. PHP files (controllers, models, requests, etc.)
3. Blade views
4. Built assets (`public/build/manifest.json`, `public/build/assets/`)

Use `scp -i ~/.ssh/ebmsnova -P 21098` for all transfers.
Remote base path: `uascexams@198.54.114.171:/home/uascexams/ebmsnova.uasckuexams.in/`

Create any new directories on the server before uploading:
```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "mkdir -p /home/uascexams/ebmsnova.uasckuexams.in/<new-dir>"
```

### 4. Run migrations (if any new migration files)

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan migrate --force"
```

Only use `--force` when running non-interactively via SSH. If able to run interactively, prefer without.

### 5. Clear caches

Always run after every deploy:

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan optimize:clear"
```

### 6. Verify

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan route:list --name=<feature> 2>&1 | head -20"
```

Check `storage/logs/laravel.log` for fresh errors if the site behaves unexpectedly:

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "tail -30 /home/uascexams/ebmsnova.uasckuexams.in/storage/logs/laravel.log"
```

## Common pitfalls

- **ERR_TOO_MANY_REDIRECTS** — a Blade layout referencing an unregistered route. Fix: upload routes first, then views, then clear cache.
- **`Route [login] not defined`** — known bug: Authenticate middleware redirects to `route('login')` but the correct name is `admin.login`. Pre-existing, not caused by normal deployments.
- **Missing columns** — if adding columns to an existing table, create a new additive migration (`000013_add_*`) rather than editing the original create migration, since it's already been run on the server.
- **Asset hashes** — after `npm run build`, the filenames change. Always upload both `manifest.json` and the new `public/build/assets/` files; the old hashed files can be left in place.
