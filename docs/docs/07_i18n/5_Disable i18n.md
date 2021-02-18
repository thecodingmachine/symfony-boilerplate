---
title: Disable i18n
slug: /i18n/disable-i18n
---

Consider not disabling i18n as you may need it in the future.

Moreover, it's often a good practice to centralize your application texts.

If you only need one locale, you may remove the web application's locale selection (*src/webapp/components/layouts/Header.vue*)
and set the `DEFAULT_LOCALE` environment variable with your unique locale 
(see [default locale](/docs/i18n/default-locale) chapter).