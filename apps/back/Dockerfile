# syntax=docker.io/docker/dockerfile:1.4
ARG IMAGE_VERSION=php:8.2-v4
ARG APP_ENV=prod
ARG APP_SOURCE_FILE='./'
ARG PHP_EXTENSIONS="apcu mysqli pdo_mysql intl gd xdebug"

FROM thecodingmachine/php:8.2-v4-slim-apache as dev
ENV APP_ENV=dev
ENV APP_DEBUG=1
ENV TEMPLATE_PHP_INI=development
ENV APACHE_DOCUMENT_ROOT="public/"
ENV PHP_EXTENSION_XDEBUG=1 \
    PHP_INI_OPCACHE__ENABLE="0" \
    PHP_INI_MEMORY_LIMIT="1G"

FROM dev as stable

COPY --link --chown=docker:docker ./001-symf.conf /etc/apache2/sites-enabled/001-symf.conf
COPY --link --chown=docker:docker ./bin/ /var/www/html/bin/
COPY --link --chown=docker:docker ./.env /var/www/html/.env
COPY --link --chown=docker:docker ./composer.json /var/www/html/composer.json
COPY --link --chown=docker:docker ./composer.lock /var/www/html/composer.lock
COPY --link --chown=docker:docker ./symfony.lock /var/www/html/symfony.lock

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV TEMPLATE_PHP_INI=production
ENV PHP_EXTENSION_XDEBUG=0 \
    PHP_EXTENSION_MYSQLI=1 \
    PHP_EXTENSION_PDO=1
#ENV APACHE_RUN_USER=www-data \
#    APACHE_RUN_GROUP=www-data

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

RUN composer install --no-dev --no-scripts --no-interaction --optimize-autoloader

# This need to be here to prevent docker  to recompile composer layers at each build
COPY --link --chown=1000:1000  ./templates/ /var/www/html/templates/
COPY --link --chown=1000:1000  ./public/ /var/www/html/public/
COPY --link --chown=1000:1000  ./phpstan/ /var/www/html/phpstan/
COPY --link --chown=1000:1000  ./config/ /var/www/html/config/
COPY --link --chown=1000:1000  ./migrations/ /var/www/html/migrations/
COPY --link --chown=1000:1000  ./src/ /var/www/html/src/

RUN composer dump-autoload --no-dev --classmap-authoritative
USER docker