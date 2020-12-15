---
title: Customization
slug: /theme/customization
---

## Application Name

Both the `webapp` and `api` services read the `APP_NAME` environment variable.

Its value comes from the root *.env* file.

If you update this value, you will have to restart these services (locally by doing `make down up`).

:::note

📣&nbsp;&nbsp;Don't forget to update the file *.env.dist* if this change is definitive.

:::

## BootstrapVue

The Symfony Boilerplate uses [BootstrapVue](https://bootstrap-vue.org/) as templating framework.

Of course, you may use another templating framework, although it will require some extra work.

Most of the components from [BootstrapVue](https://bootstrap-vue.org/) come in the form of plugins.

You have to add them manually in the *src/webapp/nuxt.config.js* file:

```js title="src/webapp/nuxt.config.js"
bootstrapVue: {
    icons: true,
    css: false,
    bvCSS: false,
    componentPlugins: [
      'LayoutPlugin',
      // etc.
    },
}
``` 

## Change Colors

### Pages

The file *src/webapp/assets/css/_variables.scss* is where you may define most of the colors.

A few other `.scss` files also contain colors definition.

### Loading Bar

Its color is configurable in the  *src/webapp/nuxt.config.js* file:

```js title="src/webapp/nuxt.config.js"
loading: {
    color: '#a211fa',
    height: '3px',
},
```

### Emails

The file *src/api/assets/css/emails.css* is where you may define most of the colors. 
