# syntax=docker.io/docker/dockerfile:1.4
ARG IMAGE_VERSION=node:20

# This is stage copy buildings file
FROM ${IMAGE_VERSION} as dev
ENV NODE_ENV=development
USER node

FROM ${IMAGE_VERSION} as prepare-common-front-file
ENV NODE_ENV=production

COPY --link --chown=node:node ./src/ /home/node/app/src/
COPY --link --chown=node:node ./nuxt.config.ts /home/node/app/nuxt.config.ts
COPY --link --chown=node:node ./package.json /home/node/app/package.json
COPY --link --chown=node:node ./package-lock.json /home/node/app/package-lock.json
COPY --link --chown=node:node ./.eslintrc.cjs /home/node/app/.eslintrc.cjs
COPY --link --chown=node:node ./tsconfig.json /home/node/app/tsconfig.json
WORKDIR /home/node/app
RUN npm install
RUN npm run build

FROM ${IMAGE_VERSION} as stable
COPY --chown=node:node --from=prepare-common-front-file /home/node/app/.output /home/node/app/.output

CMD node .output/server/index.mjs
WORKDIR /home/node/app
USER node