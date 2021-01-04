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

Let's update the Symfony recipes:

```bash title="console"
composer recipes
```

* `doctrine/doctrine-bundle`
* `phpunit/phpunit`
* `symfony/framework-bundle`
* `symfony/messenger`

Take a look at the following commit for a better understanding of the changes: 
https://github.com/thecodingmachine/symfony-boilerplate/commit/c64dda19a44a49b75056eb34fea07c854f7fe7dc


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

### Development Environment

In the *Makefile*, update the value of `VAGRANT_DOCKER_COMPOSE_VERSION` from `1.27.3` to `1.27.4`.

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