# Deployment

## Server Details

| | |
|--|--|
| Host | `198.54.114.171` |
| SSH Port | `21098` |
| SSH Key | `~/.ssh/ebmsnova` |
| User | `uascexams` |
| App path | `/home/uascexams/ebmsnova.uasckuexams.in` |
| Domain | `https://ebmsnova.uasckuexams.in` |

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171
```

---

## Deploy Steps

### 1. Build frontend assets (always required for CSS/JS/Blade changes)

```bash
cd apps/ebms-platform && npm run build
```

Node.js is **not available on the server** — always build locally and upload.

### 2. Upload files — routes BEFORE views

> **Critical:** Upload route files before layout/view files.
> A layout referencing an unregistered route causes ERR_TOO_MANY_REDIRECTS across the entire admin portal.

Upload order:
1. Route files (`routes/*.php`)
2. PHP files (controllers, models, services, requests)
3. Blade views
4. Built assets (`public/build/manifest.json` + `public/build/assets/`)

```bash
# Example: upload a controller
scp -i ~/.ssh/ebmsnova -P 21098 \
  app/Http/Controllers/Admin/ExamController.php \
  uascexams@198.54.114.171:/home/uascexams/ebmsnova.uasckuexams.in/app/Http/Controllers/Admin/

# Create new remote directory before uploading new files
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "mkdir -p /home/uascexams/ebmsnova.uasckuexams.in/app/Http/Controllers/Admin/"
```

### 3. Run migrations (if any new migration files)

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan migrate --force"
```

Use `--force` when running non-interactively via SSH.

### 4. Clear caches (always)

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan optimize:clear"
```

---

## Checklist

After every deploy:

- [ ] Upload route files before views
- [ ] Upload new/modified PHP files
- [ ] Upload Blade views
- [ ] Build assets locally (`npm run build`) and upload `public/build/`
- [ ] Run `php artisan migrate --force` if there are new migration files
- [ ] Run `php artisan optimize:clear`
- [ ] Verify site loads and key flows work

---

## Verification

```bash
# Check routes are registered
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan route:list --name=exams 2>&1 | head -20"

# Check for fresh errors
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "tail -30 /home/uascexams/ebmsnova.uasckuexams.in/storage/logs/laravel.log"
```

---

## Common Pitfalls

| Problem | Cause | Fix |
|---------|-------|-----|
| ERR_TOO_MANY_REDIRECTS | Layout references unregistered route | Upload routes first, then views, then clear cache |
| `Route [login] not defined` | `Authenticate` middleware uses default `login` route | Pre-existing bug — not caused by normal deploys |
| Missing columns after deploy | Migration not run | `php artisan migrate --force` |
| Old hashed assets served | `manifest.json` not uploaded | Upload both `manifest.json` and new `assets/` files |

---

## Storage Symlink

The `public/storage` symlink must exist on the server for student photos to be accessible:

```bash
ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && php artisan storage:link"
```
