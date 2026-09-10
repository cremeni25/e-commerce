#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR/infra"

if ! command -v docker >/dev/null 2>&1; then
  echo "ERRO: Docker não instalado." >&2
  exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
  echo "ERRO: Docker Compose não disponível." >&2
  exit 1
fi

if [ ! -f .env ]; then
  echo "ERRO: crie infra/.env a partir de infra/.env.example antes de subir a stack." >&2
  exit 1
fi

set -a
. ./.env
set +a

required=(CREMENI_DOMAIN WP_HOME WP_SITEURL WP_DB_NAME WP_DB_USER WP_DB_PASSWORD DB_ROOT_PASSWORD)
for key in "${required[@]}"; do
  if [ -z "${!key:-}" ]; then
    echo "ERRO: variável obrigatória ausente: $key" >&2
    exit 1
  fi
done

if [[ "$WP_DB_PASSWORD" == "CHANGE_ME" || "$DB_ROOT_PASSWORD" == "CHANGE_ME_TOO" ]]; then
  echo "ERRO: substitua as senhas de exemplo antes de continuar." >&2
  exit 1
fi

echo "[CREMENI] Validando configuração..."
docker compose config >/dev/null

echo "[CREMENI] Baixando imagens oficiais..."
docker compose pull

echo "[CREMENI] Subindo homologação..."
docker compose up -d

echo "[CREMENI] Estado dos serviços:"
docker compose ps

echo "[CREMENI] Homologação iniciada sem alterar DNS de produção."
