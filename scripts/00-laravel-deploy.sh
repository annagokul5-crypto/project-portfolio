#!/usr/bin/env bash
set -e
set -x

echo "START: 00-laravel-deploy.sh"

echo "PWD:"
pwd

echo "LIST /var/www/html:"
ls -la /var/www/html || true

echo "CD /var/www/html"
cd /var/www/html

echo "LIST (current):"
ls -la

echo "PHP VERSION:"
php -v || true

echo "COMPOSER VERSION:"
composer -V || true

echo "STEP: ensure permissions + folders"
# Create Laravel runtime directories (needed for cache/view/session)
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# IMPORTANT: fix ownership + permissions (not only chmod)
chown -R nginx:nginx storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "STEP: composer install"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "STEP: migrate"
php artisan migrate --force

echo "STEP: npm install"
npm install

echo "STEP: npm run build"
npm run build

echo "STEP: clear caches"
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "STEP: optimize caches"
# Laravel recommends caching config/routes/views in production deploys
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "DONE: 00-laravel-deploy.sh"
