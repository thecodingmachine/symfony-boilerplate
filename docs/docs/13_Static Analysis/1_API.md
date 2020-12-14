---
title: API
slug: /static-analysis/api
---

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

## PHP_CodeSniffer

[PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) is both a linting and a formatting tool.

The boilerplate adds the rules from the [Doctrine Coding Standard](https://github.com/doctrine/coding-standard).

It will parse your PHP source code, catch common mistakes plus format it.

It's quite strict, but that's for your good! 😁

This command will try to fix and format your source code:

```bash title="console"
composer csfix
```

This command will verify your source code:

```bash title="console"
composer cscheck
```

## PHPStan

[PHPStan](https://github.com/phpstan/phpstan) is a static analysis tool that focuses on finding errors in your 
code without actually running it.

In the boilerplate, we set it to the maximum level.

```bash title="console"
composer phpstan
```

## Deptrac

[Deptrac](https://github.com/sensiolabs-de/deptrac) is a static code analysis tool that enforces rules for 
dependencies between software layers in your PHP projects.

In other words, il will ensure that:
 
* You don't call the classes from the `Instrastructure` namespace outside of itself.
* You don't call the classes from the `UseCase` namespace in the `Domain` namespace.

```bash title="console"
composer deptrac
```

## YAML configuration

Symfony provides a linter that checks common errors in YAML files:

```bash title="console"
composer yaml-lint
```

## Composer normalize

[Composer normalize](https://github.com/ergebnis/composer-normalize) normalizes your *composer.json* file's structure:

```bash title="console"
COMPOSER_MEMORY_LIMIT=-1 composer normalize
```