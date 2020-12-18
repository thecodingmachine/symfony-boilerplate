---
title: Authentication
slug: /security/authentication
---

## GraphQL

[GraphQLite](https://graphqlite.thecodingmachine.io/) exposes three GraphQL entry points 
(you do not have to create them manually):

1. The `login` mutation: takes a `userName` and a `password`. It returns a `User` type on success.
2. The `logout` mutation.
3. The `me` query: returns a `User` type if authenticated, null otherwise.

GraphQLite hooks itself to the authentication mechanisms of Symfony, but it needs some help with that task.

For instance, the class `UserProvider`.
Its goal is to tell which class represents our users and load an instance of this class according to the session's data.

We tell Symfony that we use this custom user provider in the configuration file *src/api/config/packages/security.yaml*.

In the application, we defined that class `User` represents our users. 
It implements the `UserInterface` from Symfony.

There are many methods to implement, but the most important ones are:

* `getUsername`: the "username" value (the user's email in our case).
* `getPassword`: the salted / encoded password ([TDBM](https://github.com/thecodingmachine/tdbm) provides the implementation - see *BaseUser* class).
* `getRoles`: the Symfony permissions (e.g. `ROLE_FOO`, `ROLE_BAR`, etc.) - more on that later.

In the Symfony Boilerplate, we have already implemented those methods! 😉

## Sessions

We store the sessions' data in the MySQL database (`sessions` table). We configured this behavior in the configuration
files *src/api/config/packages/framework.yaml* and *src/api/config/services.yaml*. 

The migration *src/api/migrations/Version20200424093138.php* generates the `sessions` table.

## Cookie

On login, Symfony provides a `PHPSESSID` cookie to the browser. On logout or session expiration, it deletes this cookie.

This cookie is only available on the main domain and its subdomains. For instance, if your API URL is `https://api.foo.com`
and you call the `login` mutation from `https://foo.com`, the cookie will be available. It will not be the case 
from `https://bar.com`. 

:::note

📣&nbsp;&nbsp;You may customize the domain thanks to the `COOKIE_DOMAIN` environment variable from the `api` service.

:::

## `auth` Store

The *src/webapp/store/auth* store centralizes the data of the authenticated user on the web application

We use this store in many parts of the security process.

**The state:** *src/webapp/store/auth/state.js*

It contains a `user` object with the following values:

* `id`
* `firstName`
* `lastName`
* `email`
* `locale`
* `profilePicture`
* `role`

We initialize these values with empty strings or `null` for the profile picture.

**Getters:** *src/webapp/store/auth/getters.js*

* `isAuthenticated`: it returns `true` if the `user`'s `email` property from the state is not empty. It might return `true`
even if the user has no more session in the API, but we will see below how to handle such a case.
* `isGranted`: it returns `true` if the user has the authorization level of the given role.

It would be best to use these getters mostly for displaying (or not) an element in the UI.

**Mutations:** *src/webapp/store/auth/mutations.js*

* `setUser`: sets the state's `user` object.
* `setUserLocale`: sets the state's `user`'s `locale` property.
* `setUserProfilePicture`: sets the state's `user`'s `profilePicture` property.
* `resetUser`: resets the state's `user` object with empty strings.

**Actions:** *src/webapp/store/auth/actions.js*

* `me`: calls the `me` GraphQL query and, according to the result, 
sets the state's `user` object or resets it.

Most of the store is available through the `Auth` mixin:

```js title="Vue component <script> block"
import { Auth } from '@/mixins/auth'

export default {
  mixins: [Auth],
}
```

:::note

📣&nbsp;&nbsp;A mixin content merges with the content of your Vue component.

:::

## Authentication Workflow

On the *src/webapp/pages/login.vue* page, we call the `login` GraphQL mutation. On success, we feed the state
of the *src/webapp/store/auth* store, thanks to the `setUser` mutation.

As explained before, the API sets the `PHPSESSID` cookie in the browser. 

When in SPA mode, the browser sets the header `Cookie` with the `PHPSESSID` on each HTTP request to the API. 

However, the first time the user lands on the application, Nuxt.js server-renders the current page. It also renders pages
having the `asyncData` attribute on the server.

In the file *src/webapp/store/actions.js*,
there is an `nuxtServerInit` method, which Nuxt.js calls before server-rendering every page. 
In this function, we:

1. Set the header `Cookie` for every server-side GraphQL requests.
2. Call the `me` action to fetch (or not) the user data (useful when the user refreshes the page).
