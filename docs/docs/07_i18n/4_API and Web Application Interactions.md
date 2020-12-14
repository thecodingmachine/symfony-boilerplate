---
title: API and Web Application Interactions
slug: /i18n/api-and-web-application-interactions
---

There are three ways for communicating the user locale from the web application to the API:

1. Requests with `Accept-Language` HTTP header for validation error messages.
2. Query parameters when you cannot set the previous HTTP header (links, for instance).
3. Update the user's locale via a GraphQL request if authenticated.
4. Update the user's locale on login if the web application locale is not the same as the locale from the API

## HTTP Header

Use case: translating the validation error messages.

Each time the user changes its locale on the web application, the *src/webapp/plugins/i18n.js* plugin will 
update the HTTP header `Accept-Language` for the next GraphQL requests with the new value.

The `LocaleSubscriber` class from the API reads the value of this HTTP header to set the locale on its side.

## Query Parameters

In some use cases, you cannot set an HTTP header. For instance, when the user clicks on a link, you will have to use
query parameters:

```
http://foo.bar/?locale=fr
```

In the Symfony Boilerplate, we use this solution for XLSX exports.

## Authenticated user's locale

As explained before, whenever an authenticated user changes the locale on the web application, we run the 
`updateLocale` GraphQL mutation. 

In the API, the `UpdateLocale` use case updates the `locale` property of this user.

:::note

📣&nbsp;&nbsp;This property helps to know in which locale the API has to translate emails for this user.

:::

We also call the `updateLocale` GraphQL mutation on page *src/webapp/pages/login.vue*
in the specific scenario where the web application locale is not the same as the user's locale from the API.