#!/usr/bin/env bash
# Bash script to prepare local SQLite DB, run migrations and seeders for this Laravel project.
# Usage: ./scripts/setup_db.sh
set -euo pipefail

echo "Starting DB setup..."

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
    echo "Copied .env.example -> .env"
  else
    echo "Warning: .env.example not found. Please create .env manually." >&2
  fi
else
  echo ".env already exists"
fi

if [ ! -d vendor ]; then
  echo "Running composer install..."
  composer install
else
  echo "Dependencies (vendor/) already present"
fi

echo "Generating APP_KEY if missing..."
php artisan key:generate --ansi || true

mkdir -p database
DB_FILE="database/database.sqlite"
if [ ! -f "$DB_FILE" ]; then
  touch "$DB_FILE"
  echo "Created $DB_FILE"
else
  echo "$DB_FILE already exists"
fi

echo "Running migrations and seeders..."
php artisan migrate --seed --force

echo "Clearing config and cache..."
php artisan config:clear
php artisan cache:clear

echo "DB setup completed successfully."
echo "You can run the app: php artisan serve"
