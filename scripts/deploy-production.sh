#!/bin/bash
set -euo pipefail

REPO="/home/cremenib3a76cde/repositories/e-commerce-github"
DEPLOY="/home/cremenib3a76cde/public_html"
STATE="/home/cremenib3a76cde/.cremeni-last-deploy"
LOCK="/home/cremenib3a76cde/.cremeni-deploy.lock"

exec 9>"$LOCK"
flock -n 9 || exit 0

cd "$REPO"

git fetch origin main
REMOTE_SHA="$(git rev-parse origin/main)"
LOCAL_SHA="$(git rev-parse HEAD)"
LAST_DEPLOY=""

if [ -f "$STATE" ]; then
  LAST_DEPLOY="$(cat "$STATE")"
fi

if [ "$LOCAL_SHA" != "$REMOTE_SHA" ]; then
  git checkout main
  git pull --ff-only origin main
  LOCAL_SHA="$(git rev-parse HEAD)"
fi

if [ "$LAST_DEPLOY" = "$LOCAL_SHA" ]; then
  echo "[$(date '+%Y-%m-%d %H:%M:%S %z')] sem alterações; produção já está em ${LOCAL_SHA:0:7}"
  exit 0
fi

mkdir -p "$DEPLOY/wp-content/themes/cremeni-store"
mkdir -p "$DEPLOY/wp-content/mu-plugins"

cp -R "$REPO/wp-content/themes/cremeni-store/." "$DEPLOY/wp-content/themes/cremeni-store/"
cp -f "$REPO"/wp-content/mu-plugins/*.php "$DEPLOY/wp-content/mu-plugins/"

printf '%s\n' "$LOCAL_SHA" > "$STATE"
echo "[$(date '+%Y-%m-%d %H:%M:%S %z')] deploy concluído em ${LOCAL_SHA:0:7}"
