---
title: Configuration
slug: /webapp/configuration
---

General documentation on how to configure Nuxt.js. More details are available in the [Guides](../guides/i18n) section
or in the [official documentation](https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-build).

### nuxt.config.js

The *src/webapp/nuxt.config.js* file contains the configuration of Nuxt.js.

You may use environment variables in this file. They are available through 
the instruction `process.env.YOUR_ENVIRONMENT_VARIABLE_NAME`.

:::note

📣&nbsp;&nbsp;Put them under the `environment` property of your `webapp` service in your *docker-compose.yml* file.

:::

If you need the value of an environment variable in your code, put it under the `publicRuntimeConfig` or 
`privateRuntimeConfig` section of the *nuxt.config.js* file.

For instance:

```js title="src/webapp/nuxt.config.js"
publicRuntimeConfig: {
    apiURL: process.env.API_URL,
}
```

The value is available in your code thanks to `this.$config.apiURL` (or `$config.apiURL` in your `template` blocks).

:::note

📣&nbsp;&nbsp;`privateRuntimeConfig` should contain your secrets.

:::

:::note

📣&nbsp;&nbsp;Values from this section **are only available when Nuxt.js executes your code on the server.**

:::

## Yarn

:::note

📣&nbsp;&nbsp;All commands have to be run in the `webapp` service (`make webapp`).

:::

We recommend using [Yarn](https://yarnpkg.com/) as package manager.

When installing a JavaScript dependency, ask yourself if it is a `dev` dependency or not:

```
yarn add [package] [--dev]
```

As we're using Nuxt.js, make sure to choose the package with Nuxt.js support (aka module) if available.

### Hot Reloading
    
In your development environment, the `webapp` service run the command `yarn dev`. 

This command watch for file changes, recompile those files and automatically refresh your browser.

This command may also show ESLint errors and warnings; you can fix them using `yarn lint:fix`.