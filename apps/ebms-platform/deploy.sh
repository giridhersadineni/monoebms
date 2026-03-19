#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────────────────
# deploy.sh — EBMS → ebmsnova.uasckuexams.in
#
# Usage:
#   ./deploy.sh           — deploy latest code
#   ./deploy.sh --setup   — first deploy: creates .env, migrates DB, sets doc root & PHP
#   ./deploy.sh --rollback — restore previous backup
# ─────────────────────────────────────────────────────────────────────────────
set -euo pipefail

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'

log()  { echo -e "${CYAN}▶ $*${NC}"; }
ok()   { echo -e "${GREEN}✓ $*${NC}"; }
warn() { echo -e "${YELLOW}⚠ $*${NC}"; }
die()  { echo -e "${RED}✗ $*${NC}" >&2; exit 1; }

# ── Load config ───────────────────────────────────────────────────────────────
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
CONFIG="$SCRIPT_DIR/deploy.env"
[[ -f "$CONFIG" ]] || die "deploy.env not found."
source "$CONFIG"

[[ -z "${SSH_USER:-}" ]] && die "SSH_USER not set in deploy.env"
[[ -z "${SSH_HOST:-}" ]] && die "SSH_HOST not set in deploy.env"

SSH_KEY="${SSH_KEY:-$HOME/.ssh/ebmsnova}"
SSH_PORT="${SSH_PORT:-21098}"
DEPLOY_PATH="${REMOTE_APP_PATH:-/home/uascexams/ebmsnova.uasckuexams.in}"
PHP_BIN="php"
COMPOSER_BIN="/opt/cpanel/composer/bin/composer"
SUBDOMAIN="ebmsnova"
ROOT_DOMAIN="uasckuexams.in"

# ── Ensure SSH key is in agent ────────────────────────────────────────────────
[[ -f "$SSH_KEY" ]] || die "SSH key not found: $SSH_KEY"
chmod 600 "$SSH_KEY"
if ! ssh-add -l 2>/dev/null | grep -q "$SSH_KEY"; then
    log "Loading SSH key into agent…"
    expect -c "
        spawn ssh-add $SSH_KEY
        expect \"passphrase\"
        send \"giridhersadineni\r\"
        expect eof
    " >/dev/null 2>&1 || ssh-add "$SSH_KEY"
fi

SSH_OPTS="-i $SSH_KEY -p $SSH_PORT -o StrictHostKeyChecking=accept-new -o ConnectTimeout=15"
remote()  { ssh $SSH_OPTS "$SSH_USER@$SSH_HOST" "$@"; }
remotec() { ssh $SSH_OPTS "$SSH_USER@$SSH_HOST" "cd $DEPLOY_PATH && $*"; }

# ── Flags ─────────────────────────────────────────────────────────────────────
FIRST_SETUP=false
ROLLBACK=false
for arg in "$@"; do
  case "$arg" in
    --setup)    FIRST_SETUP=true ;;
    --rollback) ROLLBACK=true ;;
  esac
done

# ─────────────────────────────────────────────────────────────────────────────
# ROLLBACK
# ─────────────────────────────────────────────────────────────────────────────
if [[ "$ROLLBACK" == true ]]; then
  log "Rolling back…"
  remote bash -s <<BASH
set -e
BACKUP=\$(ls -1t $DEPLOY_PATH/../.ebms-backups/ 2>/dev/null | head -1)
[[ -z "\$BACKUP" ]] && { echo "No backup found."; exit 1; }
echo "Restoring backup: \$BACKUP"
rsync -a --delete $DEPLOY_PATH/../.ebms-backups/\$BACKUP/ $DEPLOY_PATH/
cd $DEPLOY_PATH
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
echo "Rollback complete."
BASH
  ok "Rollback done."
  exit 0
fi

# ─────────────────────────────────────────────────────────────────────────────
# STEP 1 — Build assets
# ─────────────────────────────────────────────────────────────────────────────
echo ""
echo -e "${BOLD}━━━ Deploying EBMS → $SUBDOMAIN.$ROOT_DOMAIN ━━━${NC}"
echo ""

log "Building frontend assets…"
cd "$SCRIPT_DIR"
npm run build 2>&1 | grep -E "(built in|ERROR|✓)" || true
ok "Assets built."

# ─────────────────────────────────────────────────────────────────────────────
# STEP 2 — Backup current deploy (skip on first setup)
# ─────────────────────────────────────────────────────────────────────────────
if [[ "$FIRST_SETUP" == false ]]; then
  log "Backing up current deploy…"
  BACKUP_TS=$(date +%Y%m%d_%H%M%S)
  remote bash -s <<BASH
mkdir -p $DEPLOY_PATH/../.ebms-backups
# Keep only last 3 backups
ls -1t $DEPLOY_PATH/../.ebms-backups/ 2>/dev/null | tail -n +4 | \
  xargs -I{} rm -rf "$DEPLOY_PATH/../.ebms-backups/{}"
# Copy current (exclude storage so it's fast)
rsync -a --exclude='storage/framework/' --exclude='storage/logs/' \
  $DEPLOY_PATH/ $DEPLOY_PATH/../.ebms-backups/$BACKUP_TS/
echo "Backup: $BACKUP_TS"
BASH
  ok "Backup created."
fi

# ─────────────────────────────────────────────────────────────────────────────
# STEP 3 — Upload files
# ─────────────────────────────────────────────────────────────────────────────
log "Uploading files via rsync…"

rsync -az --progress \
  --exclude='.git/' \
  --exclude='.gitignore' \
  --exclude='node_modules/' \
  --exclude='.env' \
  --exclude='.env.local' \
  --exclude='.env.production' \
  --exclude='deploy.env' \
  --exclude='deploy.sh' \
  --exclude='docker/' \
  --exclude='docker-compose.yml' \
  --exclude='storage/logs/' \
  --exclude='storage/framework/cache/' \
  --exclude='storage/framework/sessions/' \
  --exclude='storage/framework/views/' \
  --exclude='tests/' \
  --exclude='phpunit.xml' \
  --exclude='.phpunit.result.cache' \
  -e "ssh $SSH_OPTS" \
  "$SCRIPT_DIR/" \
  "$SSH_USER@$SSH_HOST:$DEPLOY_PATH/"

ok "Files uploaded."

# ─────────────────────────────────────────────────────────────────────────────
# STEP 4 — First-time setup: doc root, PHP version, .env, key
# ─────────────────────────────────────────────────────────────────────────────
if [[ "$FIRST_SETUP" == true ]]; then

  log "Setting subdomain document root to public/ …"
  remote uapi SubDomain addsubdomain \
    domain="$SUBDOMAIN" \
    rootdomain="$ROOT_DOMAIN" \
    dir="ebmsnova.uasckuexams.in/public" 2>&1 | grep -E "(result|error|message)" || true
  ok "Document root → ebmsnova.uasckuexams.in/public"

  log "Creating production .env…"
  remote bash -s <<BASH
set -e
if [[ -f $DEPLOY_PATH/.env ]]; then
  echo ".env already exists, skipping."
  exit 0
fi
cat > $DEPLOY_PATH/.env <<'ENV'
APP_NAME="UASC Exams"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://ebmsnova.uasckuexams.in
APP_LOCALE=en
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.uasckuexams.in
SESSION_SECURE_COOKIE=true

CACHE_STORE=file
QUEUE_CONNECTION=sync
ENV
chmod 640 $DEPLOY_PATH/.env
echo ".env created"
BASH
  ok ".env created."

  log "Generating APP_KEY…"
  remotec "$PHP_BIN artisan key:generate --force"
  ok "APP_KEY generated."

fi

# ─────────────────────────────────────────────────────────────────────────────
# STEP 5 — Composer, storage, migrate, cache
# ─────────────────────────────────────────────────────────────────────────────
log "Running server-side setup…"
remote bash -s <<BASH
set -e
cd $DEPLOY_PATH

echo "── PHP: \$($PHP_BIN -r 'echo PHP_VERSION;')"

# Ensure writable dirs exist
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache

# Composer (--ignore-platform-reqs: vendor was built on PHP 8.4 locally, server runs 8.2)
$PHP_BIN $COMPOSER_BIN install --no-dev --optimize-autoloader --ignore-platform-reqs --quiet
echo "── Composer: done"

# Storage link
$PHP_BIN artisan storage:link --quiet 2>/dev/null || true
echo "── Storage linked"

# Rebuild caches
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
echo "── Caches rebuilt"

# Permissions
chmod -R 775 storage bootstrap/cache
echo "── Permissions set"
BASH

ok "Server setup complete."

# ─────────────────────────────────────────────────────────────────────────────
echo ""
ok "━━━ Deployment successful ━━━"
echo -e "   ${BOLD}URL:${NC}  https://ebmsnova.uasckuexams.in"
echo -e "   ${BOLD}Time:${NC} $(date '+%Y-%m-%d %H:%M:%S')"
echo ""
