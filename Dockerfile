FROM php:8.3-fpm AS base

# Install system dependencies
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
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    opcache \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copie application (composer.* d'abord pour cache build)
COPY composer.* symfony.* ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN mkdir -p var/cache var/log && \
    composer dump-env prod && \
    composer run-script --no-dev post-install-cmd && \
    chmod +x bin/console && sync

CMD ["php-fpm"]

# --------- Dev ---------
FROM frankenphp_base AS frankenphp_dev

ENV APP_ENV=dev XDEBUG_MODE=off

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions xdebug

COPY --link frankenphp/conf.d/20-app.dev.ini $PHP_INI_DIR/app.conf.d/

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

# --------- Prod ---------
FROM frankenphp_base AS frankenphp_prod

ENV APP_ENV=prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN rm -f /usr/local/bin/docker-entrypoint \
       /etc/caddy/Caddyfile \
       /etc/caddy/worker.Caddyfile

COPY --link composer.* symfony.* ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

COPY --link . ./
RUN rm -rf frankenphp

RUN mkdir -p var/cache var/log && \
    composer dump-env prod && \
    composer run-script --no-dev post-install-cmd && \
    chmod +x bin/console && sync

CMD ["php-fpm"]
