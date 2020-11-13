# Web application

A Nuxt.js frontend which is rendered from server-side and acts as an SPA (Single Page Application) once loaded.

**📣&nbsp;&nbsp;All commands have to be run in the `webapp` service (`make webapp`).**

## Yarn

We recommend using [Yarn](https://yarnpkg.com/) as package manager.

When installing a JavaScript dependency, ask yourself if it is a `dev` dependency or not:

```
yarn add [package] [--dev]
```

As we're using Nuxt.js, make sure to choose the package with Nuxt.js support (aka module) if available.

### Hot Reloading

The `webapp` service run the command `yarn dev`. 

This command watch for file changes, recompile those files and automatically refresh your browser.

This command may also show ESLint errors and warnings; you can fix them using `yarn lint:fix`.
