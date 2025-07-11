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

RUN curl -fsSL https://nodejs.org/dist/v22.14.0/node-v22.14.0-linux-arm64.tar.xz -o node.tar.xz \
 && mkdir -p /usr/local/lib/nodejs \
 && tar -xJf node.tar.xz -C /usr/local/lib/nodejs \
 && rm node.tar.xz \
 && ln -s /usr/local/lib/nodejs/node-v22.14.0-linux-arm64/bin/node /usr/bin/node \
 && ln -s /usr/local/lib/nodejs/node-v22.14.0-linux-arm64/bin/npm /usr/bin/npm \
 && ln -s /usr/local/lib/nodejs/node-v22.14.0-linux-arm64/bin/npx /usr/bin/npx

WORKDIR /app

COPY composer.* symfony.* ./

COPY . .

RUN composer install --no-interaction --optimize-autoloader
RUN php bin/console doctrine:migrations:migrate --no-interaction || true
RUN composer dump-autoload --optimize
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