#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

pass() {
  echo "cloud-env-test: ok $*"
}

fail() {
  echo "cloud-env-test: FAIL $*" >&2
  exit 1
}

require_command() {
  command -v "$1" >/dev/null 2>&1 || fail "missing command: $1"
}

require_php_extension() {
  php -m | grep -qi "^$1$" || fail "missing php extension: $1"
}

echo "cloud-env-test: validating Cursor Cloud image prerequisites"

require_command php
require_command composer
require_command git
require_command sudo
require_command jq
require_command bash

php -v | grep -q 'PHP 8.5' || fail "expected PHP 8.5"

for extension in bcmath curl intl mbstring pdo_sqlite xml zip; do
  require_php_extension "$extension"
done

[[ -d /etc/ssh/sshd_config.d ]] || fail "missing /etc/ssh/sshd_config.d (install openssh-server)"
[[ -d /etc/sudoers.d ]] || fail "missing /etc/sudoers.d (install sudo)"

jq empty .cursor/environment.json || fail "invalid .cursor/environment.json"
[[ -f .cursor/Dockerfile ]] || fail "missing .cursor/Dockerfile"
[[ -x .cursor/hooks/php-quality.sh ]] || fail "php-quality hook is not executable"

pass "image prerequisites"

echo "cloud-env-test: running cloud agent install"

bash .cursor/cloud-agent-install.sh

[[ -f vendor/autoload.php ]] || fail "composer dependencies were not installed"
[[ -x vendor/bin/pest ]] || fail "pest binary missing after install"
[[ -x vendor/bin/pint ]] || fail "pint binary missing after install"
[[ -x vendor/bin/phpstan ]] || fail "phpstan binary missing after install"
[[ -x vendor/bin/rector ]] || fail "rector binary missing after install"

php artisan --version >/dev/null || fail "artisan is not runnable"
[[ -f .env ]] || fail ".env was not created"
[[ -f database/database.sqlite ]] || fail "sqlite database file was not created"

pass "application install"

echo "cloud-env-test: running php-quality hook smoke test"

hook_input=$(
  jq -n \
    --arg file_path "app/Http/Controllers/ProfileController.php" \
    --arg workspace_root "$ROOT" \
    '{file_path: $file_path, workspace_roots: [$workspace_root]}'
)

echo "$hook_input" | bash .cursor/hooks/php-quality.sh

pass "php-quality hook"

echo "cloud-env-test: running pest suite"

php artisan test --compact

pass "all cloud environment checks passed"
