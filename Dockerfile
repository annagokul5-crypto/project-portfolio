FROM richarvey/nginx-php-fpm:3.1.6

RUN composer global remove hirak/prestissimo --no-interaction || true


COPY . /var/www/html

RUN apk add --no-cache npm

CMD ["/start.sh"]
