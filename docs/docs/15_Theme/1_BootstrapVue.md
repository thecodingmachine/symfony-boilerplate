---
title: BootstrapVue
slug: /theme/bootstrap-vue
---

The Symfony Boilerplate uses [BootstrapVue](https://bootstrap-vue.org/) as templating framework.

Of course, you may use another templating framework, although it will require some extra work.

## Add Plugin

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

The file *src/webapp/assets/css/_variables.scss* is where you may define most of the colors.

A few other `.scss` files also contain colors definition.

For the Nuxt.js loading bar, its color is configurable in the  *src/webapp/nuxt.config.js* file:

```js title="src/webapp/nuxt.config.js"
loading: {
    color: '#a211fa',
    height: '3px',
},
``` 
