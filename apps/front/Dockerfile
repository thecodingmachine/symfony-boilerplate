# syntax=docker.io/docker/dockerfile:1.4
ARG IMAGE_VERSION=node:16
ARG NODE_ENV=production
ARG APP_SOURCE_FILE='./'

# This is stage copy buildings file
FROM ${IMAGE_VERSION} as prepare-common-front-file
ENV NODE_ENV=$NODE_ENV

ARG APP_SOURCE_FILE

COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/src/ /home/node/app/src/
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/nuxt.config.ts /home/node/app/nuxt.config.ts
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/package.json /home/node/app/package.json
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/yarn.lock /home/node/app/yarn.lock
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/.eslintrc.cjs /home/node/app/.eslintrc.cjs
COPY --link --chown=docker:docker  ${APP_SOURCE_FILE}/tsconfig.json /home/node/app/tsconfig.json
WORKDIR /home/node/app
RUN ls
RUN yarn install
RUN yarn build

FROM ${IMAGE_VERSION}
COPY --chown=docker:docker --from=prepare-common-front-file /home/node/app /home/node/app

CMD node .output/server/index.mjs