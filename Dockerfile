FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql sockets
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer


COPY . /app

WORKDIR /app
RUN composer install
CMD php -S 0.0.0.0:8000 -t public
EXPOSE 8000