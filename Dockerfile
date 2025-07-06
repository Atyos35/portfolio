# syntax=docker/dockerfile:1

FROM dunglas/frankenphp:1-php8.3 AS frankenphp_upstream

# --------- Base commune ---------
FROM frankenphp_upstream AS frankenphp_base

WORKDIR /app

VOLUME /app/var/

RUN apk add --no-cache \
    acl \
    file \
    gettext \
    git \
    curl \
    unzip \
    libzip-dev

RUN set -eux; \
    install-php-extensions \
        @composer \
        apcu \
        intl \
        opcache \
        zip

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

COPY --link frankenphp/conf.d/10-app.ini $PHP_INI_DIR/app.conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

ENTRYPOINT ["docker-entrypoint"]
HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

# --------- Environnement de développement ---------
FROM frankenphp_base AS frankenphp_dev

ENV APP_ENV=dev XDEBUG_MODE=off

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions xdebug

COPY --link frankenphp/conf.d/20-app.dev.ini $PHP_INI_DIR/app.conf.d/

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

# --------- Environnement de production ---------
FROM frankenphp_base AS frankenphp_prod

ENV APP_ENV=prod
ENV FRANKENPHP_CONFIG="import worker.Caddyfile"

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link frankenphp/conf.d/20-app.prod.ini $PHP_INI_DIR/app.conf.d/
COPY --link frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

COPY --link composer.* symfony.* ./

RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

COPY --link . ./
RUN rm -rf frankenphp

RUN mkdir -p var/cache var/log && \
    composer dump-env prod && \
    composer run-script --no-dev post-install-cmd && \
    chmod +x bin/console && sync