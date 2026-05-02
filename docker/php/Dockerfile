FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install mbstring curl pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html