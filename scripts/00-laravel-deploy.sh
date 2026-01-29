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
php artisan migrate --force

echo "STEP: clear caches"
php artisan optimize:clear

echo "STEP: optimize caches"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "DONE: 00-laravel-deploy.sh"
