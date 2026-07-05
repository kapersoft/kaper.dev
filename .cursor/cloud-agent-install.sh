#!/usr/bin/env bash
set -euo pipefail

composer install --no-interaction

if [[ ! -f .env ]]; then
  cp .env.example .env
  php artisan key:generate --no-interaction
fi

if [[ ! -f database/database.sqlite ]]; then
  touch database/database.sqlite
fi

php artisan migrate --no-interaction
