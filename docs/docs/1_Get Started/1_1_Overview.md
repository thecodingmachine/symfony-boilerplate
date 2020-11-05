---
title: Overview
slug: /
---

The Symfony Boilerplate provides a dummy application with core concepts and functionalities to help you build 
a modern web application.

Many services compose this boilerplate.

## Application layer

This layer has two services:

1. The GraphQL API, built with Symfony 5 and [GraphQLite](https://graphqlite.thecodingmachine.io/).
2. The Web Application, built with Nuxt.js.

## Data layer

This layer has 3 services:

1. MySQL for hosting session and business data.
2. Redis for asynchronous tasks (e.g., emails).
3. [MinIO](https://min.io/) for storing files (e.g., uploads).

:::note

In production, you may externalize them to the equivalent services from your provider.

:::

## Additional services

These services are mostly useful in development:

1. [Traefik](https://doc.traefik.io/traefik/), a reverse proxy.
2. [MailHog](https://github.com/mailhog/MailHog), an email trapper with a web UI.
3. phpMyAdmin, a web UI for displaying your database's data.