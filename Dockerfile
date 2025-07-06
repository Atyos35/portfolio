# syntax=docker/dockerfile:1

FROM php:8.3-fpm AS base

RUN sudo apt-get update && sudo apt-get install -y --no-install-recommends \
    acl \
    file \
    gettext \
    git \
    curl \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_mysql \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.* symfony.* ./

RUN composer install --no-dev --no-interaction --optimize-autoloader

COPY . .

RUN mkdir -p var/cache var/log && \
    composer dump-env prod && \
    composer run-script --no-dev post-install-cmd && \
    chmod +x bin/console && sync

CMD ["php-fpm"]