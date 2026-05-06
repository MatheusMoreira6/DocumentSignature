FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install zip mbstring curl pdo pdo_pgsql \
    && git config --system --add safe.directory /var/www/html \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_HOME=/tmp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html