FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd opcache pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY php/php.ini /usr/local/etc/php/conf.d/ebms.ini

WORKDIR /var/www/html
