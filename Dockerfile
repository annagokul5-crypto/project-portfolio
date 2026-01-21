FROM richarvey/nginx-php-fpm:3.1.6

COPY . /var/www/html

RUN apk add --no-cache npm

CMD ["/start.sh"]
