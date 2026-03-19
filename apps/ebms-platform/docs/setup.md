# Local Setup

## Prerequisites

- PHP 8.4
- Composer
- Node.js 20+
- Docker + Docker Compose (recommended)

---

## With Docker

```bash
cd apps/ebms-platform

# Copy env
cp .env.example .env

# Start containers (app, nginx, MariaDB, Redis)
docker compose up -d

# Install dependencies inside container
docker compose exec app composer install
docker compose exec app npm install

# Generate app key
docker compose exec app php artisan key:generate

# Run migrations
docker compose exec app php artisan migrate --seed

# Build frontend
docker compose exec app npm run build
```

App is available at **http://localhost:8000**.

To open a shell inside the container:
```bash
docker compose exec app bash
```

---

## Without Docker (bare metal)

> Requires MariaDB 10.6+ running locally.

```bash
cd apps/ebms-platform
cp .env.example .env

# Edit DB credentials in .env
composer install
npm install
php artisan key:generate
php artisan migrate --seed

# Dev server with HMR
composer run dev
```

---

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_URL` | Full URL including scheme, e.g. `http://localhost:8000` |
| `DB_CONNECTION` | `mariadb` |
| `DB_DATABASE` | Database name |
| `LEGACY_DB_HOST` | Legacy MariaDB host (read-only) |
| `LEGACY_DB_DATABASE` | `uascexams_ebms` |
| `SESSION_SECURE_COOKIE` | `false` in local dev, `true` on HTTPS production |

---

## Running Tests

Tests use an in-memory SQLite database — no separate test DB needed.

```bash
php artisan test

# Single file
php artisan test tests/Unit/FeeCalculatorTest.php

# With coverage
php artisan test --coverage --min=75
```

---

## Code Quality

```bash
# Static analysis (PHPStan level 5)
./vendor/bin/phpstan analyse

# Code style (Laravel Pint)
./vendor/bin/pint
```
