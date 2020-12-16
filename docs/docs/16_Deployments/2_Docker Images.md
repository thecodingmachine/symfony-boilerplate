---
title: Docker Images
slug: /deployments/docker-images
---

The Symfony Boilerplate provides two Dockerfiles:

* *src/api/Dockerfile*.
* *src/webapp/Dockerfile*.

They contain all the instructions required for building the API and web application Docker images.

All you need to do is to provide a distinct `.env` file for each of them (at the root of the Dockerfiles directories).
Their content will differ according to your remote environments. 

You should create these `.env` files in your CI/CD processes, and never commit their content in your Git repository.
Prefer secrets from your CI/CD provider! 😉

These `.env` files should contain:

1. **API:** the environment variables for configuring Symfony.
2. **Web application:** the environment variables for configuring Nuxt.js.

:::note

📣&nbsp;&nbsp;You may take a look at your development *docker-compose.yml* file for the list of environment variables.

::: 

