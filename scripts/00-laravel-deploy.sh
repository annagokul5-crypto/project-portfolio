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

echo "STEP: composer install"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "STEP: npm install"
npm install

echo "STEP: npm run build"
npm run build

echo "STEP: cache clear"
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "STEP: migrate"
php artisan migrate --force

echo "STEP: optimize"
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "DONE: 00-laravel-deploy.sh"
