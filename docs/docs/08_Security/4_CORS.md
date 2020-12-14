---
title: CORS
slug: /security/CORS
---

CORS is the mechanism defining what can interact with the API via HTTP requests.

The application uses the [nelmio/cors-bundle](https://github.com/nelmio/NelmioCorsBundle) package for that task.
We configured this bundle in the configuration file *src/api/config/packages/nelmio_cors.yaml*.

The current configuration only authorizes HTTP requests from the main domain (and the API subdomain).

:::note

📣&nbsp;&nbsp;Never use `*` as `CORS_ALLOW_ORIGIN` because it opens your API to the world. As there is no CSRF protection, a
malicious hacker will be able to hijack the connexion of one of your authenticated users to do bad things. Also, make sure
you don't have XSS vulnerabilities. 

:::