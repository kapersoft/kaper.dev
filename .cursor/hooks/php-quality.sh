#!/usr/bin/env bash
set -euo pipefail

input=$(cat)

file_path=$(echo "$input" | jq -r '.file_path // empty')
workspace_root=$(echo "$input" | jq -r '.workspace_roots[0] // empty')

if [[ -z "$file_path" ]]; then
  exit 0
fi

if [[ "$file_path" != *.php ]]; then
  exit 0
fi

if [[ -n "$workspace_root" ]]; then
  cd "$workspace_root"
fi

if [[ ! -f "$file_path" ]]; then
  echo "php-quality: file not found: $file_path" >&2
  exit 0
fi

if [[ ! -x vendor/bin/rector || ! -x vendor/bin/pint || ! -x vendor/bin/phpstan ]]; then
  echo "php-quality: vendor binaries missing; run composer install" >&2
  exit 0
fi

failed=0

run_tool() {
  local label=$1
  shift

  echo "php-quality: running $label on $file_path" >&2

  if ! "$@"; then
    echo "php-quality: $label failed for $file_path" >&2
    failed=1
  fi
}

run_tool rector vendor/bin/rector process "$file_path" --ansi
run_tool pint vendor/bin/pint "$file_path"
run_tool phpstan vendor/bin/phpstan analyse "$file_path" --memory-limit=512M

exit "$failed"
