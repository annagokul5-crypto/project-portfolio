FROM richarvey/nginx-php-fpm:3.1.6

# Install Node.js + npm for Vite build
RUN apk add --no-cache nodejs-current npm

WORKDIR /var/www/html
COPY . /var/www/html

CMD ["/start.sh"]
