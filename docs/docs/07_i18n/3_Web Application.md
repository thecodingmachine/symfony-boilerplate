---
title: Web Application
slug: /i18n/web-application
---

For the web application, i18n has two goals:

1. Translate the user interface.
2. Tell the API which locale the user has selected (for validations errors, etc.).

We use the [nuxt/i18n](https://i18n.nuxtjs.org/) module, and we configured it in 
the *src/webapp/nuxt.config.js* file.

## Basic Usage

```html title="Vue component <template> block"
<button>
  {{ $t('a_translation_key') }}
</button>
```

```js title="Vue component <script> block"
this.$t('a_translation_key')
```

## Locales Folder

Folder *src/webapp/locales* contains one JavaScript file per locale.

Each of these files exports an object with translation keys and the associated texts.

For instance:

```js title="src/webapp/locales/en.js"
export default {
  foo: {
    baz: 'Hello',
  },
}
```

:::note

📣&nbsp;&nbsp;All files should have the same organization (same translations keys, identical sorting, etc.).

:::

## Browser Language Detection

The first time the user lands on your application's root pages (`/x`, but not `/x/y`), it automatically detects the 
browser language and sets the cookie `i18n_redirected` with the corresponding locale. 

If your application does not support the browser language, it uses the default locale instead.

Next time the user lands on your application, it will use cookie `i18n_redirected`'s value to translate the 
user interface to the correct locale automatically (and redirect the user to the right path - see below).

## Routing

The router prefixes all routes with the locale. For instance, the login page is available on the paths `/en/login` and
`/fr/login`.

Wherever you need to push a route in your router, use the global method `localePath({ name: 'route_name' })`.

For instance:

```html title="Vue component <template> bock"
<b-link :to="localePath({ name: 'reset-password', query: { email: form.email } })">
  {{ $t('pages.login.form.forgot_password_link') }}
</b-link>
```

```js title="Vue component <script> block"
this.$router.push(this.localePath({ name: 'index' }))
```

If you don't know the name of your route, take a look at the router file Nuxt.js generates:

```js title="src/webapp/.nuxt/router.js"
{
  path: "/fr/update-password/:id?/:token",
  component: _8474331c,
  name: "update-password-id-token___fr"
}
```

The name of the route here is `update-password-id-token`.

:::note

📣&nbsp;&nbsp;If a route has parameters, you have to give them to the `localePath` method.

:::

## Update Locale

The file *src/webapp/components/layouts/Header.vue* provides an example of how to update the locale:

```html title="Vue component <template> bock"
<b-nav-item-dropdown right>
  <template #button-content>
    {{ currentLocale }}
  </template>
  <b-dropdown-item
    v-for="locale in availableLocales"
    :key="locale.code"
    :active="locale.code === currentLocale"
    :to="switchLocalePath(locale.code)"
  >
    {{ locale.code }}
  </b-dropdown-item>
</b-nav-item-dropdown>
```

```js title="Vue component <script> block"
export default {
  computed: {
    availableLocales() {
      return this.$i18n.locales.filter((i) => i.code !== this.$i18n.locale)
    },
    currentLocale() {
      return this.$i18n.locale
    },
  },
}
```

Here calling `switchLocalePath` will update the current locale in the `i18n` store (from nuxt-i18n), 
plus the value of the cookie `i18_redirected`. It will also redirect the user to the correct route.

Our custom plugin *src/webapp/plugins/i18n.js* hooks itself on these events for:

1. Updating the HTTP header `Accept-Language` for next GraphQL requests (more on that in the next chapter).
2. Updating the user's locale in the database if authenticated.

:::note

📣&nbsp;&nbsp;We configured nuxt-i18n to lazy load the locales files, meaning it will only load the current locale file 
instead of all locales files. You may have to refresh a page in development to see changes made in the corresponding
locale file.

:::