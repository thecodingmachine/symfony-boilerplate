# Nuxt 3 Minimal Starter

Look at the [Nuxt 3 documentation](https://nuxt.com/docs/getting-started/introduction) to learn more.

# How to use

## Production

A default dockerfile is provided, the image expose the builded nuxt app on the port 80 

## Dev

Expose the port 24678 of the container, mount the root front directory into /home/node/app and run `yarn install && yarn dev -- -o` as the start command
