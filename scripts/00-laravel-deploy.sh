#!/usr/bin/env bash
set -e

echo "RUNNING: 00-laravel-deploy.sh"
composer install --no-dev --working-dir=/var/www/html
echo "RUNNING: php artisan config:cache"
php artisan config:cache
echo "RUNNING: php artisan route:cache"
php artisan route:cache
echo "RUNNING: php artisan view:cache"
php artisan view:cache
echo "RUNNING: php artisan migrate --force"
php artisan migrate --force
echo "DONE: 00-laravel-deploy.sh"
