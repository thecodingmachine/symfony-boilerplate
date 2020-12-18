---
title: CHANGELOG
slug: /changelog
---

## 0.2.0

### API

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

Update Symfony from version `5.1` to `5.2` by following 
the [Upgrading a Minor Version](https://symfony.com/doc/current/setup/upgrade_minor.html) guide.

Once done, run the following commands:

```bash title="console"
composer update
php bin/console tdbm:generate
composer require ecodev/graphql-upload ^5
```

And delete the *src/api/src/Domain/ResultIterator* folder.

In order to find the last outdated dependencies, run `composer outdated --direct`:

---

`fzaninotto/faker`:

Replace this dependency with `fakerphp/faker`:

```bash title="console"
composer remove fzaninotto/faker
composer require --dev fakerphp/faker
```

---

`sensiolabs-de/deptrac-shim`:

In the `require-dev` section from the *src/api/composer.json* file, replace:

```json title="src/api/composer.json"
"sensiolabs-de/deptrac-shim": "^0.8.2",
```

By:

```json title="src/api/composer.json"
"sensiolabs-de/deptrac-shim": "^0.10.2",
```

And run `composer update`.

---

`league/flysystem-aws-s3-v3` & `league/flysystem-memory`:

At the time of writing, the Symfony Bundle is not yet ready for version `2.0.0`:

* https://github.com/thephpleague/flysystem-bundle/pull/59

---

Now most of the dependencies are up-to-date.

Yet, we have to update the Symfony recipes:

```bash title="console"
composer recipes
```

---

`doctrine/doctrine-bundle`:

```bash title="console"
composer recipes:install doctrine/doctrine-bundle --force -v
```

Once done, replace the content of the following files:

```yaml title="src/api/config/packages/doctrine.yaml"
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '13'
#    orm:
#        auto_generate_proxy_classes: true
#        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
#        auto_mapping: true
#        mappings:
#            App:
#                is_bundle: false
#                type: annotation
#                dir: '%kernel.project_dir%/src/Entity'
#                prefix: 'App\Entity'
#                alias: App

```

```xml title="src/api/phpunit.xml.dist"
<?xml version="1.0" encoding="UTF-8"?>

<!-- https://phpunit.readthedocs.io/en/latest/configuration.html -->
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         backupGlobals="false"
         colors="true"
         bootstrap="tests/bootstrap.php"
>

    <php>
        <ini name="error_reporting" value="-1" />
        <server name="APP_ENV" value="test" force="true" />
        <server name="SHELL_VERBOSITY" value="-1" />
        <server name="KERNEL_CLASS" value="App\Kernel" />
        <server name="STORAGE_PUBLIC_SOURCE" value="public.storage.memory" force="true" />
        <server name="STORAGE_PRIVATE_SOURCE" value="private.storage.memory" force="true" />
    </php>

    <testsuites>
        <testsuite name="Project Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>

    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src</directory>
            <exclude>
                <directory>src/Kernel.php</directory>
                <directory>src/Domain/Model/Generated</directory>
                <directory>src/Domain/Dao/Generated</directory>
                <directory>src/Domain/ResultIterator/Generated</directory>
                <directory>src/Infrastructure/Command/DevFixturesCommand.php</directory>
                <directory>src/Infrastructure/Command/InitializeMinIOStorageCommand.php</directory>
                <directory>src/Infrastructure/EventSubscriber</directory>
                <directory>src/Infrastructure/Fixtures</directory>
                <directory>src/Infrastructure/S3</directory>
            </exclude>
        </whitelist>
    </filter>

    <logging>
        <log type="coverage-html" target="coverage"/>
        <log type="coverage-text" target="php://stdout"/>
    </logging>
</phpunit>
```

And remove the *src/api/src/Entity* and *src/api/src/Repository* folders.

---

`phpunit/phpunit`:

```bash title="console"
composer recipes:install phpunit/phpunit --force -v
```

Once done, replace the content of the following files:

```xml title="src/api/phpunit.xml.dist"
<?xml version="1.0" encoding="UTF-8"?>

<!-- https://phpunit.readthedocs.io/en/latest/configuration.html -->
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         backupGlobals="false"
         colors="true"
         bootstrap="tests/bootstrap.php"
>

    <php>
        <ini name="error_reporting" value="-1" />
        <server name="APP_ENV" value="test" force="true" />
        <server name="SHELL_VERBOSITY" value="-1" />
        <server name="KERNEL_CLASS" value="App\Kernel" />
        <server name="STORAGE_PUBLIC_SOURCE" value="public.storage.memory" force="true" />
        <server name="STORAGE_PRIVATE_SOURCE" value="private.storage.memory" force="true" />
    </php>

    <testsuites>
        <testsuite name="Project Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>

    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src</directory>
            <exclude>
                <directory>src/Kernel.php</directory>
                <directory>src/Domain/Model/Generated</directory>
                <directory>src/Domain/Dao/Generated</directory>
                <directory>src/Domain/ResultIterator/Generated</directory>
                <directory>src/Infrastructure/Command/DevFixturesCommand.php</directory>
                <directory>src/Infrastructure/Command/InitializeMinIOStorageCommand.php</directory>
                <directory>src/Infrastructure/EventSubscriber</directory>
                <directory>src/Infrastructure/Fixtures</directory>
                <directory>src/Infrastructure/S3</directory>
            </exclude>
        </whitelist>
    </filter>

    <logging>
        <log type="coverage-html" target="coverage"/>
        <log type="coverage-text" target="php://stdout"/>
    </logging>
</phpunit>
```

```php title="src/api/tests/bootstrap.php"
<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

/** @var Process[] $processes */
$processes = [
    // Delete the "tests" database.
    new Process(['php', 'bin/console', 'doctrine:database:drop', '-n', '--force', '--if-exists']),
    // Create the "tests" database.
    new Process(['php', 'bin/console', 'doctrine:database:create', '-n', '--if-not-exists']),
    // Initialize the "tests" database structure.
    new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '-n']),
    // Clear the cache.
    new Process(['php', 'bin/console', 'cache:clear', '--no-warmup']),
];

foreach ($processes as $process) {
    $process->run();
    if (! $process->isSuccessful()) {
        throw new RuntimeException(
            $process->getCommandLine() .
            ': ' .
            $process->getExitCode() .
            ' ' .
            $process->getExitCodeText() .
            ' ' .
            $process->getErrorOutput()
        );
    }
}
```

And remove the *src/api/.env.test* file.

---

`symfony/framework-bundle`:

```bash title="console"
composer recipes:install symfony/framework-bundle --force -v
```

Once done, replace the content of the following files:

```xml title="src/api/phpunit.xml.dist"
<?xml version="1.0" encoding="UTF-8"?>

<!-- https://phpunit.readthedocs.io/en/latest/configuration.html -->
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         backupGlobals="false"
         colors="true"
         bootstrap="tests/bootstrap.php"
>

    <php>
        <ini name="error_reporting" value="-1" />
        <server name="APP_ENV" value="test" force="true" />
        <server name="SHELL_VERBOSITY" value="-1" />
        <server name="KERNEL_CLASS" value="App\Kernel" />
        <server name="STORAGE_PUBLIC_SOURCE" value="public.storage.memory" force="true" />
        <server name="STORAGE_PRIVATE_SOURCE" value="private.storage.memory" force="true" />
    </php>

    <testsuites>
        <testsuite name="Project Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>

    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src</directory>
            <exclude>
                <directory>src/Kernel.php</directory>
                <directory>src/Domain/Model/Generated</directory>
                <directory>src/Domain/Dao/Generated</directory>
                <directory>src/Domain/ResultIterator/Generated</directory>
                <directory>src/Infrastructure/Command/DevFixturesCommand.php</directory>
                <directory>src/Infrastructure/Command/InitializeMinIOStorageCommand.php</directory>
                <directory>src/Infrastructure/EventSubscriber</directory>
                <directory>src/Infrastructure/Fixtures</directory>
                <directory>src/Infrastructure/S3</directory>
            </exclude>
        </whitelist>
    </filter>

    <logging>
        <log type="coverage-html" target="coverage"/>
        <log type="coverage-text" target="php://stdout"/>
    </logging>
</phpunit>
```

```yaml title="src/api/config/services.yaml"
# This file is the entry point to configure your own services.
# Files in the packages/ subdirectory configure your dependencies.

# Put parameters here that don't need to change on each machine where the app is deployed
# https://symfony.com/doc/current/best_practices/configuration.html#application-related-configuration
parameters:
    # Parameters are values which are used directly in the code.
    app.storage_public_source: '%env(STORAGE_PUBLIC_SOURCE)%'
    app.storage_private_source: '%env(STORAGE_PRIVATE_SOURCE)%'
    app.storage_public_bucket: '%env(STORAGE_PUBLIC_BUCKET)%'
    app.storage_private_bucket: '%env(STORAGE_PRIVATE_BUCKET)%'
    app.mail_title: '%env(APP_NAME)%'
    app.mail_from_address: '%env(MAIL_FROM_ADDRESS)%'
    app.mail_from_name: '%env(MAIL_FROM_NAME)%'
    app.mail_webapp_url: '%env(MAIL_WEBAPP_URL)%'
    app.mail_webapp_update_password_route_format: '%env(MAIL_WEBAPP_UPDATE_PASSWORD_ROUTE_FORMAT)%'

services:
    # default configuration for services in *this* file
    _defaults:
        autowire: true      # Automatically injects dependencies in your services.
        autoconfigure: true # Automatically registers your services as commands, event subscribers, etc.

    # makes classes in src/ available to be used as services
    # this creates a service per class whose id is the fully-qualified class name
    App\:
        resource: '../src/'
        exclude:
            - '../src/Domain/{Throwable,Model}'
            - '../src/Infrastructure/S3'
            - '../src/Kernel.php'
            - '../src/Tests/'

    # controllers are imported separately to make sure services can be injected
    # as action arguments even if you don't extend any base controller class
    App\Infrastructure\Controller\:
        resource: '../src/Infrastructure/Controller'
        tags: ['controller.service_arguments']

    # add more service definitions when explicit configuration is needed
    # please note that last definitions always *replace* previous ones
    Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler:
        arguments:
            - '%env(DATABASE_URL)%'

    App\Infrastructure\Command\InitializeS3StorageCommand:
        arguments:
            - '@s3.client'

    App\Infrastructure\EventListener\FileNotFoundExceptionListener:
        tags:
            - { name: kernel.event_listener, event: kernel.exception }

    App\Infrastructure\EventListener\NoBeanFoundExceptionListener:
        tags:
            - { name: kernel.event_listener, event: kernel.exception }

    App\Infrastructure\EventSubscriber\LocaleSubscriber:
        arguments:
            - '%kernel.default_locale%'

    # A special kind of formatter that logs the stack traces (otherwise, Symfony logs do not contain stack traces).
    formatterWithStackTrace:
        class: Monolog\Formatter\LineFormatter
        calls:
            - [includeStacktraces]
```

```gitignore title="src/api/.gitignore"
/coverage

###> symfony/framework-bundle ###
/.env.local
/.env.local.php
/.env.*.local
/config/secrets/prod/prod.decrypt.private.php
/public/bundles/
/var/
/vendor/
###< symfony/framework-bundle ###

###> squizlabs/php_codesniffer ###
/.phpcs-cache
/phpcs.xml
###< squizlabs/php_codesniffer ###

###> phpunit/phpunit ###
/phpunit.xml
.phpunit.result.cache
###< phpunit/phpunit ###

###> sensiolabs-de/deptrac-shim ###
/.deptrac.cache
###< sensiolabs-de/deptrac-shim ###
```

```yaml title="src/api/config/packages/framework.yaml"
# see https://symfony.com/doc/current/reference/configuration/framework.html
framework:
    secret: '%env(APP_SECRET)%'
    #csrf_protection: true
    #http_method_override: true

    # Enables session support. Note that the session will ONLY be started if you read or write from it.
    # Remove or comment this section to explicitly disable session support.
    session:
        handler_id: Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler
        cookie_secure: auto
        #cookie_samesite: lax # CORS requests and SameSite cookies issue: https://github.com/whatwg/fetch/issues/769.
        cookie_samesite: null
        cookie_domain: '%env(COOKIE_DOMAIN)%'

    #esi: true
    #fragments: true
    php_errors:
        log: true

```

And remove the *src/api/src/Controller* folder.

---

`symfony/messenger`:

```bash title="console"
composer recipes:install symfony/messenger --force -v
```

Once done, replace the content of the following file:

```yaml title="src/api/config/packages/messenger.yaml"
framework:
    messenger:
        # Uncomment this (and the failed transport below) to send failed messages to this transport for later handling.
        # failure_transport: failed

        transports:
            # https://symfony.com/doc/current/messenger.html#transport-configuration
            async: '%env(MESSENGER_TRANSPORT_DSN)%'
            # failed: 'doctrine://default?queue_name=failed'
            # sync: 'sync://'

        routing:
            # Route your messages to the transports
            # 'App\Message\YourMessage': async
            'App\UseCase\AsyncTask': async
            'Symfony\Component\Mailer\Messenger\SendEmailMessage': async

```

### Web Application

Replace the content of the following file:

```json title="src/webapp/package.json"
{
  "name": "symfony-boilerplate",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev": "nuxt",
    "build": "nuxt build",
    "start": "nuxt start",
    "export": "nuxt export",
    "serve": "nuxt serve",
    "lint:js": "eslint --ext .js,.vue --ignore-path .gitignore .",
    "lint:style": "stylelint **/*.{vue,css} --ignore-path .gitignore",
    "lint:fix": "eslint --fix --ext .js,.vue --ignore-path .gitignore .",
    "lint": "yarn lint:js && yarn lint:style"
  },
  "dependencies": {
    "@nuxtjs/toast": "^3.3.1",
    "bootstrap-vue": "^2.20.1",
    "cookie-universal-nuxt": "^2.1.4",
    "graphql": "^15.3.0",
    "graphql-tag": "^2.11.0",
    "nuxt": "^2.14.10",
    "nuxt-graphql-request": "^3.1.2",
    "nuxt-i18n": "^6.15.1",
    "nuxt-logrocket": "^1.2.10"
  },
  "devDependencies": {
    "@babel/runtime-corejs3": "^7.10.3",
    "@nuxtjs/eslint-config": "^5.0.0",
    "@nuxtjs/eslint-module": "^3.0.2",
    "@nuxtjs/stylelint-module": "^4.0.0",
    "babel-eslint": "^10.1.0",
    "core-js": "3",
    "eslint": "^7.2.0",
    "eslint-config-prettier": "^7.0.0",
    "eslint-plugin-nuxt": "^2.0.0",
    "eslint-plugin-prettier": "^3.1.4",
    "node-sass": "^5.0.0",
    "prettier": "^2.0.5",
    "sass-loader": "^10.0.2",
    "stylelint": "^13.6.1",
    "stylelint-config-prettier": "^8.0.1",
    "stylelint-config-standard": "^20.0.0"
  }
}
```

Remove the file *src/webapp/yarn.lock* and the folder *src/webapp/node_modules*,
before recreating the `webapp` service with `docker-compose up -d --force webapp`.

Enter the `webapp` service (`make webapp`) and run `yarn lint:fix`.

## 0.1.1

### API

Method `createResponseWithXLSXAttachment` from `DownloadXLSXController` class did not delete the temporary file in
case of exception:

```php title="src/api/src/Infrastructure/Controller/DownloadXLSXController.php"
$tmpFilename = Uuid::uuid4()->toString() . '.xlsx';
$xlsx->save($tmpFilename);
$fileContent = file_get_contents($tmpFilename); // Get the file content.
unlink($tmpFilename); // Delete the file.

return $this->createResponseWithAttachment(
    $filename,
    $fileContent
);
```

We now make sure it does:

```php title="src/api/src/Infrastructure/Controller/DownloadXLSXController.php"
try {
    $tmpFilename = Uuid::uuid4()->toString() . '.xlsx';
    $xlsx->save($tmpFilename);
    $fileContent = file_get_contents($tmpFilename); // Get the file content.
} finally {
    if (file_exists($tmpFilename)) {
        unlink($tmpFilename); // Delete the file.
    }
}

return $this->createResponseWithAttachment(
    $filename,
    $fileContent
);
```