---
title: Web Application
slug: /static-analysis/web-application
---

:::note

📣&nbsp;&nbsp;All commands have to be run in the `webapp` service (`make webapp`).

:::

## ESLint

[ESLint](https://eslint.org/) is both a linting and a formatting tool.

It also comes with [Prettier](https://prettier.io/) that provides more rules.

It will parse your JavaScript and CSS source code, catch common mistakes plus format it.

This command will try to fix and format your source code:

```bash title="console"
yarn lint:fix
```

This command will verify your source code:

```bash title="console"
yarn lint
```

:::note

📣&nbsp;&nbsp;In your development environment, hot reloading automatically runs the lint command.

:::