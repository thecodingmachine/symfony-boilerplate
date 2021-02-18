---
title: Web Application
slug: /graphql/web-application
---

The Symfony Boilerplate uses [graphql-request](https://github.com/prisma-labs/graphql-request) client.

It is available in a Vue component thanks to `this.$graphql`.

Queries and mutations are JavaScript files. For instance:

```js title="src/webapp/graphql/auth/me.query.js"
import { gql } from 'graphql-request'
import { MeFragment } from '@/graphql/auth/me.fragment'

export const MeQuery = gql`
  query me {
    me {
      ... on User {
        ...MeFragment
      }
    }
  }
  ${MeFragment}
`
```

:::note

📣&nbsp;&nbsp;A fragment is useful is you want to fetch the same data in many queries and mutations.

:::