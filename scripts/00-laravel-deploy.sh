#!/usr/bin/env bash
set -e

composer install --no-dev --working-dir=/var/www/html
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
