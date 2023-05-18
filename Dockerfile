FROM php:8.1.12-fpm-alpine

RUN apk add --no-cache nano bash zip libzip-dev libjpeg-turbo-dev libpng-dev libwebp-dev freetype-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype

RUN docker-php-ext-install pdo pdo_mysql sockets zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app

COPY . .
RUN composer install
