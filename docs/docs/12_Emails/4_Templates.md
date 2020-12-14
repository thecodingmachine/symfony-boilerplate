---
title: Templates
slug: /emails/templates
---

The *src/api/templates/emails* folder contains the Twig templates of the emails.

By default, all templates should extend the *src/api/templates/emails/emails.html.twig* template. 

In other words, the templates should only contain the body of the email, while the *emails.html.twig* template contains 
the wrapping elements (header, footer, etc.).

:::note

📣&nbsp;&nbsp;Of course, you may update the *emails.html.twig* template.

:::

Symfony provides the [Inky](https://get.foundation/emails/docs/inky.html) framework that converts simple HTML tags into 
the complex table HTML required for emails. For more details, you may also take a look at the 
[Symfony documentation](https://symfony.com/doc/current/mailer.html#inky-email-templating-language).

If you want to add new CSS rules, put them in the *src/api/assets/css/emails.css*.