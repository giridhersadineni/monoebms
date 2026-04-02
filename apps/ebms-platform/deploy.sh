#!/usr/bin/env bash
# EBMS Platform deploy script
# Usage: bash deploy.sh [file1] [file2] ...
# Reads credentials from deploy.env in the same directory.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/deploy.env"

REMOTE="${SSH_USER}@${SSH_HOST}"
SSH_OPTS="-i ${SSH_KEY} -p ${SSH_PORT} -o StrictHostKeyChecking=no -o BatchMode=yes"

# Load key into agent
eval "$(ssh-agent -s)" > /dev/null
_askpass=$(mktemp /tmp/askpass.XXXXXX.sh)
printf '#!/bin/sh\nprintf "%%s" "%s"\n' "${SSH_PASS}" > "$_askpass"
chmod +x "$_askpass"
DISPLAY=fake SSH_ASKPASS="$_askpass" SSH_ASKPASS_REQUIRE=force ssh-add "${SSH_KEY}" 2>&1
rm -f "$_askpass"

# Files to deploy
if [[ $# -gt 0 ]]; then
    FILES=("$@")
else
    REPO_ROOT="$(git -C "$SCRIPT_DIR" rev-parse --show-toplevel)"
    mapfile -t GIT_FILES < <(git -C "$REPO_ROOT" diff --name-only HEAD; git -C "$REPO_ROOT" ls-files --others --exclude-standard -- 'apps/ebms-platform/')
    FILES=()
    for f in "${GIT_FILES[@]}"; do
        [[ "$f" == apps/ebms-platform/* ]] && FILES+=("$REPO_ROOT/$f")
    done
fi

if [[ ${#FILES[@]} -eq 0 ]]; then echo "No files to deploy."; exit 0; fi

echo "==> Deploying ${#FILES[@]} file(s)..."
for LOCAL_PATH in "${FILES[@]}"; do
    ABS_PATH="$(cd "$(dirname "$LOCAL_PATH")" && pwd)/$(basename "$LOCAL_PATH")"
    REL_PATH="${ABS_PATH#$SCRIPT_DIR/}"
    REMOTE_DEST="${REMOTE_APP_PATH}/${REL_PATH}"
    echo "  -> $REL_PATH"
    ssh $SSH_OPTS "$REMOTE" "mkdir -p '$(dirname "$REMOTE_DEST")'"
    scp -i "${SSH_KEY}" -P "${SSH_PORT}" -o StrictHostKeyChecking=no "$ABS_PATH" "${REMOTE}:${REMOTE_DEST}"
done

echo "==> Clearing caches..."
ssh $SSH_OPTS "$REMOTE" "cd '${REMOTE_APP_PATH}' && php artisan optimize:clear"
echo "==> Deploy complete."
