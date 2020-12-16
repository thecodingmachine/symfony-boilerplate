---
title: Web Application
slug: /lists/web-application
---

:::note

📣&nbsp;&nbsp;The Symfony Boilerplate uses [BootstrapVue](https://bootstrap-vue.org/) as templating framework.
However, most of the explanations from this chapter should work with most frameworks with little adjustments.

:::

## List Mixin

The Symfony Boilerplate provides the `List` mixin. This mixin contains useful data and methods to help you build a list.

:::note

📣&nbsp;&nbsp;A mixin content merges with the content of your Vue component.

:::

```js title="Vue component <script> block"
import { List } from '@/mixins/list'

export default {
  mixins: [List],
}
```

## Initialization

The initialization of your page occurs in the `asyncData` attribute of your Vue Component:

```js title="Vue component <script> block"
import { List, defaultItemsPerPage, calculateOffset } from '@/mixins/list'
import { UsersQuery } from '@/graphql/users/users.query'

export default {
  mixins: [List],
  // On page access, Nuxt.js always server-renders a Vue component 
  // with an "asyncData" property.
  async asyncData(context) {
    // You don't have access to "this" but context instead.
    try {
      const result = await context.app.$graphql.request(UsersQuery, {
        // Most of the values come either from the query parameters
        // (i.e., ?foo=bar,baz=2) or a default value.
        search: context.route.query.search || '',
        role: context.route.query.role || null,
      })

      return {
        items: result.users.items,
      }
    } catch (e) {
      context.error(e)
    }
  },
}
```

:::note

📣&nbsp;&nbsp;`asyncData` merges at runtime with the `data` of your Vue component.

:::

:::note

📣&nbsp;&nbsp;Don't use `null` as value for your scalar parameters as it will make your page reload. Prefer, 
for instance, an empty string. 

:::

```html title="Vue component <template> block"
<b-table
  :items="items"
></b-table>
```

## Fields / Headers

You do that in the `data` attribute of your Vue component:

```js title="Vue component <script> block"
data() {
  return {
    fields: [
      { key: 'id', label: '#' },
      {
        key: 'firstName',
        label: this.$t('common.first_name'),
      },
      { key: 'lastName', label: this.$t('common.last_name') },
      { key: 'email', label: this.$t('common.email') },
      { key: 'locale', label: this.$t('common.locale') },
      { key: 'role', label: this.$t('common.role') },
    ],
  }
},
```

:::note

📣&nbsp;&nbsp;The key value have to have the same name as the corresponding GraphQL field.

:::

```html title="Vue component <template> block"
<b-table
  :items="items"
  :fields="fields"
></b-table>
```

## Data

By default, the `<b-table>` component automatically renders the data thanks to its `items` and `fields` attributes.

However, you may want to customize the output. For instance:

```html title="Vue component <template> block"
<b-table
  :items="items"
  :fields="fields"
>
    <!-- Template for "role" column. -->
    <template #cell(role)="data">
      {{ roleTranslationFromEnum(data.item.role) }}
    </template>
</b-table>
``` 

## Search

There are two kinds of search:

1. Live search.
2. On click search.

First, you have to prepare your data:

```js title="Vue component <script> block"
data() {
  return {
    filters: {
      search: this.$route.query.search || '',
      role: this.$route.query.role || null,
    },
    // ...
  }
},
```

:::note

📣&nbsp;&nbsp;Don't use `null` as value for your scalar parameters as it will make your page reload. Prefer, 
for instance, an empty string. 

:::

### Live Search

For `<b-input type="text">` component, you should use both the `:debounce` and `@update` attributes:

```html title="Vue component <template> block"
<b-form @submit.stop.prevent>
    <b-input
      v-model="filters.search"
      type="text"
      autofocus
      trim
      :debounce="debounce"
      @update="onSearch"
    ></b-input>
</b-form>
```

:::note

📣&nbsp;&nbsp;The default `debounce` value comes from the `List` mixin.

:::

For `<b-form-select>` component, you should use the `@change` attribute:


```html title="Vue component <template> block"
<b-form @submit.stop.prevent>
    <b-form-select
      v-model="filters.role"
      :options="rolesAsSelectOptions(true)"
      @change="onSearch"
    >
    </b-form-select>
</b-form>
```

### On Click Search

```html title="Vue component <template> block"
<b-form @submit.stop.prevent="onSearch">
    <b-button
        variant="primary"
        type="submit"
        >{{ $t('common.search') }}</b-button
      >
</b-form>
```

### Search Logic

The `List` mixin provides the `onSearch` method. It resets the current page to 1 and call the `doSearch` method.
You have to implement this last method:

```js title="Vue component <script> block"
methods: {
  async doSearch() {
    // Updates the query parameters for the current page.
    // For instance, if filter.search="foo", your page URL becomes
    // "?search=foo". It also works with sort by and sort order value,
    // plus pagination. See below.
    this.updateRouter()
    try {
      const result = await this.$graphql.request(UsersQuery, {
        search: this.filters.search,
        role: this.filters.role,
      })
      this.items = result.users.items
    } catch (e) {
      this.$nuxt.error(e)
    }
  },
}
```

## Sort

The browser could handle the sorting, but it does not make sense in the Symfony Boilerplate, as the API is able to handle
this task for us.

Yet, we have to prepare the UI.

First let's start by updating the data:

```js title="Vue component <script> block"
import { EMAIL, FIRST_NAME, LAST_NAME } from '@/enums/filters/users-sort-by'

data() {
  return {
    // We add the "sortable" attribute to the columns
    // we want to sort or not.
    fields: [
      { key: 'id', label: '#', sortable: false },
      {
        key: 'firstName',
        label: this.$t('common.first_name'),
        sortable: true,
      },
      { key: 'lastName', label: this.$t('common.last_name'), sortable: true },
      { key: 'email', label: this.$t('common.email'), sortable: true },
      { key: 'locale', label: this.$t('common.locale'), sortable: false },
      { key: 'role', label: this.$t('common.role'), sortable: false },
    ],
    // We define the property "sortByMap" where:
    // 1. Key = field / header name.
    // 2. Value = sort value we want to send to the API.
    sortByMap: {
      firstName: FIRST_NAME,
      lastName: LAST_NAME,
      email: EMAIL,
     },
  }
},
```

:::note

📣&nbsp;&nbsp;It is best to create a dedicated enum in *src/webapp/enums/filters* for sort by values. 

:::

Once the data is ready, we have to update the `<b-table>` component:

```html title="Vue component <template> block"
<b-table
  :items="items"
  :fields="fields"
  :no-local-sorting="true"
  :sort-by="boostrapTableSortBy"
  :sort-desc="isDesc"
  @sort-changed="onSort"
></b-table>
```

* `:no-local-sorting` disables browser-side sorting.
* `:sort-by` uses the computed value `boostrapTableSortBy` from the `List` mixin. This computed value uses itself our
`sortByMap`.
* `:sort-desc` uses the computed value `isDesc` from the `List` mixin.
* `@sort-changed` uses the method `onSort` from the `List` mixin. This method sets the `this.sortBy` and `this.sortOrder`
values from the mixin, reset the current page to 1, before calling your `doSearch` method.

We may now update the `asyncData` and `doSearch` methods:

```js title="Vue component <script> block"
async asyncData(context) {
  try {
    const result = await context.app.$graphql.request(UsersQuery, {
      search: context.route.query.search || '',
      role: context.route.query.role || null,
      sortBy: context.route.query.sortBy || null,
      sortOrder: context.route.query.sortOrder || null,
    })
    return {
      items: result.users.items,
    }
  } catch (e) {
    context.error(e)
  }
},
methods: {
  async doSearch() {
    this.updateRouter()
    try {
      const result = await this.$graphql.request(UsersQuery, {
        search: this.filters.search,
        role: this.filters.role,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder,
      })
      this.items = result.users.items
    } catch (e) {
      this.$nuxt.error(e)
    }
  },
}
```

## Paginate

The `<b-pagination>` component is a great way to handle pagination for a list:

```html title="Vue component <template> block"
<b-pagination
    v-model="currentPage"
    :per-page="itemsPerPage"
    :total-rows="count"
    align="center"
    @change="onPaginate"
    @click.native="$scrollToTop"
/>
```

* `currentPage` is a data from the `List` mixin.
* `:per-page` uses the `itemsPerPage` data from the `List` mixin. You may override its value in your component.
* `:total-rows` uses the `count` data from the `List` mixin. You update this value in your `doSearch` method.
* `@change` uses the method `onPaginate` from the `List` mixin. It sets the current page and calls your `doSearch` method.
* `@click.native="$scrollToTop` calls the plugin `scrollToTop` that... scrolls to the top of the page when the user
clicks on the pagination buttons.

```js title="Vue component <script> block"
import { defaultItemsPerPage, calculateOffset } from '@/mixins/list'

async asyncData(context) {
  try {
    const result = await context.app.$graphql.request(UsersQuery, {
      search: context.route.query.search || '',
      role: context.route.query.role || null,
      sortBy: context.route.query.sortBy || null,
      sortOrder: context.route.query.sortOrder || null,
      // defaultItemsPerPage and calculateOffset comes
      // from the List mixin. You may uses your own
      // default items per page if necessary.
      limit: defaultItemsPerPage,
      offset: calculateOffset(
        context.route.query.page || 1,
        defaultItemsPerPage
      ),
    })
    return {
      items: result.users.items,
      // We retrieve the current item counts from 
      // the GraphQL response.
      count: result.users.count,
    }
  } catch (e) {
    context.error(e)
  }
},
methods: {
  async doSearch() {
    this.updateRouter()
    try {
      const result = await this.$graphql.request(UsersQuery, {
        search: this.filters.search,
        role: this.filters.role,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder,
        // this.itemsPerPage is a data that comes from the List mixin.
        // You may change this default limit with your own.
        limit: this.itemsPerPage,
        // this.offset is a computed value that
        // comes from the List mixin.
        offset: this.offset,
      })
      this.items = result.users.items
      // We retrieve the current item counts from 
      // the GraphQL response
      this.count = result.users.count
    } catch (e) {
      this.$nuxt.error(e)
    }
  },
}
```

## Loading State

```html title="Vue component <template> block"
<b-pagination
    :busy="isLoading"
/>
```

* `:busy` displays a loader is `true`. It uses the `isLoading` data from the `List` mixin.

```js title="Vue component <script> block"
methods: {
  async doSearch() {
    // Enables loading.
    this.isLoading = true
    this.updateRouter()
    try {
      const result = await this.$graphql.request(UsersQuery, {
        search: this.filters.search,
        role: this.filters.role,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder,
        limit: this.itemsPerPage,
        offset: this.offset,
      })
      this.items = result.users.items
      this.count = result.users.count
      // Disables loading.
      this.isLoading = false
    } catch (e) {
      this.$nuxt.error(e)
    }
  },
}
```

