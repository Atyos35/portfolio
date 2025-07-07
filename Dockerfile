# syntax=docker/dockerfile:1

FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y --no-install-recommends \
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

ENV APP_ENV=prod
ENV SYMFONY_SKIP_AUTO_RUN=1

COPY . .

RUN composer install --no-dev --no-interaction --optimize-autoloader

RUN mkdir -p var/cache var/log && \
    composer dump-env prod && \
    composer run-script --no-dev post-install-cmd && \
    chmod +x bin/console && sync

CMD ["php-fpm"]