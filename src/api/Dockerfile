ARG PHP_EXTENSIONS="redis intl igbinary gd mysqli pdo_mysql"
FROM thecodingmachine/php:8.0-v4-slim-apache

# PHP settings.
ENV TEMPLATE_PHP_INI "production"
ENV PHP_INI_MEMORY_LIMIT="128M"

# Apache settings.
ENV APACHE_DOCUMENT_ROOT="public/"
RUN echo "\nServerTokens Prod\nServerSignature Off\n" | sudo tee -a /etc/apache2/sites-available/000-default.conf

# Copy files.
# Don't forget to create a .env file with required Symfony variables.
COPY --chown=docker:docker . .
USER docker

# App defaults (Change me)
ENV APP_NAME "Symfony Boilerplate"
ENV APP_ENV "prod"
ENV APP_DEBUG "0"
ENV MONOLOG_LOGGING_PATH "php://stderr"
ENV STORAGE_PUBLIC_SOURCE "public.storage.s3"
ENV STORAGE_PRIVATE_SOURCE "private.storage.s3"
ENV DEFAULT_LOCALE "en"

# Dump .env to an optimized .env.local.php.
RUN composer dump-env prod

# Install dependencies.
RUN composer install --no-dev --optimize-autoloader

# Clear and warm-up cache.
RUN php bin/console cache:clear
