---
title: Assets
slug: /files/assets
---

The application bundles assets, i.e., files like images, CSS, PDFs, etc.

## Web Application

The *src/webapp/assets* folder contains these static files. The Nuxt.js server serves them to the users.

For instance, if you want to display an image to your users:

```html title="Vue component <template> block"
<img :src="fooImageURL" />
```

```js title="Vue component <script> block"
import fooImage from '@/assets/images/foo_image.png'

export default {
  data() {
    return {
      fooImageURL: fooImage,
    }
  },
}
```

## API

The *src/api/assets* folder contains static files, mostly for the emails. The Apache server from the Docker image 
serves them to the users.