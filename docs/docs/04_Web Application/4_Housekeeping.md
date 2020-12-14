---
title: Housekeeping
slug: /webapp/housekeeping
---

This documentation will help you to keep everything up-to-date.

Please read it carefully, as those actions will ensure your project is still relevant year after year.

👉&nbsp;&nbsp;The sooner, the better!

## Nuxt.js

From time to time, check for new releases of your main packages: 

1. Update the corresponding versions in your *src/webapp/package.json* file.
2. Remove the file *src/webapp/yarn.lock* and the folder *src/webapp/node_modules*.
3. Recreate the `webapp` service with `docker-compose up -d --force webapp`.