FROM richarvey/nginx-php-fpm:3.1.6

# Image config (recommended for Laravel on this image)
ENV WEBROOT=/var/www/html/public
ENV RUN_SCRIPTS=1
ENV PHP_ERRORS_STDERR=1

# Node for Vite + Postgres driver for Laravel
RUN apk add --no-cache nodejs-current npm php82-pdo_pgsql php82-pgsql

WORKDIR /var/www/html
COPY . /var/www/html

# Use your nginx config (TCP fastcgi to 127.0.0.1:9000)
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Backend deps (production)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Frontend deps + build
RUN npm ci || npm install
RUN npm run build

CMD ["/start.sh"]
