FROM richarvey/nginx-php-fpm:3.1.6

# Image config (recommended for Laravel on this image)
ENV WEBROOT /var/www/html/public
ENV RUN_SCRIPTS 1
ENV PHP_ERRORS_STDERR 1

# Install Node + npm (for Vite build)
RUN apk add --no-cache nodejs-current npm

WORKDIR /var/www/html
COPY . /var/www/html

# Backend deps
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Frontend deps + build (creates public/build/manifest.json)
RUN npm ci || npm install
RUN npm run build

CMD ["/start.sh"]
