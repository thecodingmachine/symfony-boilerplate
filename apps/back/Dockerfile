# syntax=docker.io/docker/dockerfile:1.4
ARG IMAGE_VERSION=php:8.1-v4
ARG APP_ENV=prod
ARG APP_SOURCE_FILE='./'
ARG PHP_EXTENSIONS="apcu mysqli pdo_mysql intl gd xdebug"

# This is stage copy buildings file
FROM thecodingmachine/${IMAGE_VERSION}-slim-apache as prepare-common-file

ARG APP_SOURCE_FILE

COPY --link --chown=docker:docker ./001-symf.conf /etc/apache2/sites-enabled/001-symf.conf
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/bin/ /var/www/html/bin/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/.env /var/www/html/.env
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/composer.json /var/www/html/composer.json
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/composer.lock /var/www/html/composer.lock
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/symfony.lock /var/www/html/symfony.lock
ARG APP_ENV
ENV APP_ENV=${APP_ENV}
RUN if [ "$APP_ENV" != "dev" ] && [ "$APP_ENV" != "prod" ]; then exit 1 ; fi

FROM prepare-common-file as configure-prod
ENV APP_DEBUG=0
ENV TEMPLATE_PHP_INI=production
ENV PHP_EXTENSION_XDEBUG=0 \
    PHP_EXTENSION_MYSQLI=1 \
    PHP_EXTENSION_PDO=1
ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data

ENV PHP_INI_MEMORY_LIMIT="256M"

# OPCACHE

ENV PHP_INI_OPCACHE__ENABLE="1"

## SYMFONY RECOMMANDATIONS
ENV PHP_INI_OPCACHE__PRELOAD="/var/www/html/config/preload.php"
ENV PHP_INI_OPCACHE__PRELOAD_USER="www-data"
# maximum memory that OPcache can use to store compiled PHP files
ENV PHP_INI_OPCACHE__MEMORY_CONSUMPTION="256"
# maximum number of files that can be stored in the cache
ENV PHP_INI_OPCACHE__MAX_ACCELERATED_FILES="20000"
# by default OPcache checks if cached files have changed their contents since they were cached
ENV PHP_INI_OPCACHE__VALIDATE_TIMESTAMPS="0"
ENV PHP_INI_REALPATH_CACHE_SIZE="4096k"
ENV PHP_INI_REALPATH_CACHE_TTL="600"

# This gonna prepare the dev image with reducing total layer
FROM configure-prod as composer-install-prod

# composer dump-autoload --no-dev --classmap-authoritative

RUN composer install --no-dev --no-scripts --no-interaction --optimize-autoloader


FROM prepare-common-file as configure-dev
ARG APP_SOURCE_FILE
ENV APP_DEBUG=1
ENV TEMPLATE_PHP_INI=development
ENV PHP_EXTENSION_XDEBUG=1 \
    PHP_INI_OPCACHE__ENABLE="0" \
    PHP_INI_MEMORY_LIMIT="1G"


FROM configure-dev as composer-install-dev
ARG APP_SOURCE_FILE

# COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/tests/ /var/www/html/tests/ TODO ONCE WE HAVE TESTING
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/phpcs.xml.dist /var/www/html/phpcs.xml.dist
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/phpmd.xml /var/www/html/phpmd.xml
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/phpstan.neon /var/www/html/phpstan.neon
# COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/phpunit.xml.dist /var/www/html/phpunit.xml.dist TODO ONCE WE HAVE TESTING
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/.env.test /var/www/html/.env.test

RUN composer install --no-scripts --no-interaction

FROM composer-install-${APP_ENV} as composer-install-final

FROM configure-${APP_ENV} as full-files
ARG APP_SOURCE_FILE
COPY --chown=docker:docker --from=composer-install-final  /var/www/html /var/www/html
# This need to be here to prevent docker  to recompile composer layers at each build
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/config/ /var/www/html/config/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/migrations/ /var/www/html/migrations/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/phpstan/ /var/www/html/phpstan/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/public/ /var/www/html/public/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/src/ /var/www/html/src/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/migrations/ /var/www/html/migrations/
RUN composer run auto-scripts
#RUN composer run console -- cache:clear --no-interaction

FROM full-files as final-prod
RUN composer dump-autoload --no-dev --classmap-authoritative

FROM full-files as final-dev

FROM final-${APP_ENV} as final