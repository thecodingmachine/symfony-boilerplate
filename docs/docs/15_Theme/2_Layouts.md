---
title: Layouts
slug: /theme/layouts
---

The Symfony Boilerplate provides many layouts you may use to design your pages.

:::note

📣&nbsp;&nbsp;A layout is a sort of wrapper or skeleton around a page.

:::

## Default

The default layout is in use when you don't specify any layout for your page.

It centralizes the content of the page in the center.

![Default layout](/img/default_layout.png)

## Card

The card layout is for pages with a single `<b-card>`.

```js title="Vue component <script> block"
export default {
  layout: 'card',
}
```

![Card layout](/img/card_layout.png)

## Dashboard

The dashboard layout works great for back-office pages.

```js title="Vue component <script> block"
export default {
  layout: 'dashboard',
}
```

![Dashboard layout](/img/dashboard_layout.png)

## Empty

The empty layout is for pages that have a template of their own 
(and you don't want to create a dedicated layout for them). 

```js title="Vue component <script> block"
export default {
  layout: 'empty',
}
```

![Empty layout](/img/empty_layout.png)

## Error

The error layout is not actually a layout, but a special page for errors. It uses the empty layout.