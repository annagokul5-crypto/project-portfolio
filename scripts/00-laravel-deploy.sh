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
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache || true

echo "STEP: composer install"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "STEP: npm install"
npm install

echo "STEP: npm run build"
npm run build

