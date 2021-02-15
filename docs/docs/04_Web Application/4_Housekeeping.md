---
title: Housekeeping
slug: /webapp/housekeeping
---

👉&nbsp;&nbsp;The sooner, the better!

## Nuxt.js

From time to time: 

1. Remove the file *src/webapp/yarn.lock* and the folder *src/webapp/node_modules*.
2. Recreate the `webapp` service with `docker-compose up -d --force webapp`.
3. (optional) Run `yarn outdated` for additional packages to update.
4. (optional) Update their versions in the *src/webapp/package.json* file.
5. Repeat! 😄