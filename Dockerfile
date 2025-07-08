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

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs

WORKDIR /app

COPY composer.* symfony.* ./

COPY . .

RUN composer install --no-interaction --optimize-autoloader
RUN npm ci
RUN npm run build

RUN mkdir -p var/cache var/log var/sessions \
 && composer dump-env prod \
 && composer run-script --no-dev post-install-cmd \
 && chmod +x bin/console \
 && chown -R www-data:www-data var \
 && chmod -R 775 var \
 && sync

CMD ["php-fpm"]