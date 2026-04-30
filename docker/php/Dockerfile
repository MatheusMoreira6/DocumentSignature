FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    && docker-php-ext-install mbstring curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html