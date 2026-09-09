#!/bin/bash
set -euo pipefail

REPO="/home/cremenib3a76cde/repositories/e-commerce-github"
DEPLOY="/home/cremenib3a76cde/public_html"

cd "$REPO"

git fetch origin main
git checkout main
git pull --ff-only origin main

mkdir -p "$DEPLOY/wp-content/themes/cremeni-store"
mkdir -p "$DEPLOY/wp-content/mu-plugins"

cp -R "$REPO/wp-content/themes/cremeni-store/." "$DEPLOY/wp-content/themes/cremeni-store/"
cp -f "$REPO/wp-content/mu-plugins/cremeni-store-bootstrap.php" "$DEPLOY/wp-content/mu-plugins/cremeni-store-bootstrap.php"

echo "[$(date '+%Y-%m-%d %H:%M:%S %z')] deploy concluido em $(git rev-parse --short HEAD)"
