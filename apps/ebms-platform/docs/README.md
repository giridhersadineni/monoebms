# EBMS Platform

**Examination Branch Management System** — University Arts & Science College, Hanamkonda (Autonomous)

A Laravel 12 modular monolith that replaces three legacy PHP portals with a single-domain, module-isolated application. All active development happens in `apps/ebms-platform/`.

---

## Quick Links

| | |
|---|---|
| **Local app** | `http://localhost:8000` (Docker) |
| **Production** | `https://ebmsnova.uasckuexams.in` |
| **Student portal** | `/student/*` |
| **Admin portal** | `/admin/*` |

---

## Stack

| Layer | Technology |
|-------|-----------|
| Runtime | PHP 8.4 |
| Framework | Laravel 12 |
| Database | MariaDB (primary) · SQLite (tests) |
| Frontend | Vite 7 · Tailwind CSS 4.0 · Blade |
| PDF | barryvdh/laravel-dompdf |
| Container | Docker + Docker Compose |
| CI | GitHub Actions |
| Testing | PHPUnit 11 |

---

## Repository Layout

```
monoebms/
├── apps/
│   └── ebms-platform/          ← primary Laravel app (this repo)
│       ├── app/
│       │   ├── Console/Commands/
│       │   ├── Http/Controllers/
│       │   │   ├── Admin/
│       │   │   └── Student/
│       │   ├── Models/
│       │   ├── Services/
│       │   └── ...
│       ├── database/migrations/
│       ├── resources/views/
│       │   ├── admin/
│       │   ├── student/
│       │   └── layouts/
│       ├── routes/
│       │   ├── admin.php
│       │   └── student.php
│       └── docs/               ← you are here
├── students.uasckuexams.in/    ← legacy portal (maintenance only)
├── backoffice.uasckuexams.in/  ← legacy portal (maintenance only)
└── postexams.uasckuexams.in/   ← legacy portal (maintenance only)
```

> **Legacy portals** are in maintenance mode — do not add features.

---

## Common Commands

```bash
# Install
composer install && npm install

# Local dev (Laravel + queue + Vite concurrently)
composer run dev

# Run all tests
php artisan test

# Run tests with coverage (CI min 75%)
php artisan test --coverage --min=75

# Build frontend assets (required before deploy)
npm run build

# Migrate
php artisan migrate
php artisan migrate:fresh --seed

# Cache clear
php artisan optimize:clear
```

---

## Modules at a Glance

```
/student/*   → Student module   (Auth: hall ticket + DOB or DOST ID)
/admin/*     → Admin module     (Auth: username + password)
```

Each module has its own route file, auth guard, and session cookie. See [Architecture](architecture.md) for details.
