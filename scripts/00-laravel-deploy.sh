#!/usr/bin/env bash
set -e
set -x

echo "START: 00-laravel-deploy.sh"
cd /var/www/html

echo "STEP: ensure permissions + folders"
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
chown -R nginx:nginx storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "STEP: migrate"
php artisan migrate --force || true

echo "STEP: clear caches"
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "STEP: optimize caches"
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "DONE: 00-laravel-deploy.sh"
