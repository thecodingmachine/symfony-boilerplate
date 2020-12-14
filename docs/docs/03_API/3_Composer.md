---
title: Composer
slug: /api/composer
---

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

When installing a PHP dependency, ask yourself if it is a `dev` dependency or not:

```
composer require [--dev] [package]
COMPOSER_MEMORY_LIMIT=-1 composer normalize
```

As we're using Symfony, make sure to choose the package with Symfony support (aka bundle) if available.

:::note

📣&nbsp;&nbsp;Vagrant users might encounter some issues with Composer. 
A workaround solution is to add the flag `--prefer-source` to your Composer command.

:::