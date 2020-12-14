---
title: Default Locale
slug: /i18n/default-locale
---

Both the `webapp` and `api` services read the `DEFAULT_LOCALE` environment variable.

Its value comes from the root *.env* file.

If you update this value, you will have to restart these services (locally by doing `make down up`).

:::note

📣&nbsp;&nbsp;Don't forget to update the file *.env.dist* if this change is definitive.

:::