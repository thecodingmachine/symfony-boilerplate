---
title: Configuration
slug: /webapp/configuration
---

General documentation on how to configure Nuxt.js. More details are available
in the [official documentation](https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-build).

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

📣&nbsp;&nbsp;`privateRuntimeConfig` should contain your secrets. Values from 
this section **are only available when Nuxt.js executes your code on the server.**

:::