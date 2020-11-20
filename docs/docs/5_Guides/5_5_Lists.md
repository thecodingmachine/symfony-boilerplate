---
title: Lists
slug: /guides/lists
---

## API

In the API, a list means retrieving data from the database according to:

* Random filters (e.g., free search).
* Predictable filters (e.g., dropdown values).
* A sort direction on a field.
* A limit and an offset.

There are two main PHP classes for that goal:

1. The use case (and GraphQL entrypoint).
2. The DAO.

Both [TDBM](https://github.com/thecodingmachine/tdbm) and [GraphQLite](https://graphqlite.thecodingmachine.io/) 
will also help us a lot.

### Use case

Let's start by the use case. For instance, *src/api/src/UseCase/User/GetUsers.php*:

```php title="src/api/src/UseCase/User/GetUsers.php"
/**
 * @return User[]|ResultIterator
 *
 * @Query
 * @Logged
 * @Security("is_granted('ROLE_ADMINISTRATOR')")
 */
public function users(
    ?string $search = null,
    ?Role $role = null,
    ?UsersSortBy $sortBy = null,
    ?SortOrder $sortOrder = null
): ResultIterator {
    return $this->userDao->search(
        $search,
        $role,
        $sortBy,
        $sortOrder
    );
}
```

If we split this use case, we have:

**The annotations**

* `@return`: it contains both an array of your type AND a `ResultIterator`. It will make both PHPStan and GraphLite happy!
* `@Query`: it equivalent to a REST GET method.
* `@Logged` and `@Security`: access control.

**The arguments**

* `$search`: a random filter.
* `$role`: a predictable filter.
* `$sortBy`: the sort field.
* `$sortOrder`: the sort direction.

**The return value:** `ResultIterator`.

**The logic:** `UserDao`'s `search` method.

:::note

📣&nbsp;&nbsp;The predictable filter, sort field and sort direction are enumerators.

:::

:::note

📣&nbsp;&nbsp;There are no `$limit` or `$offset` arguments.

:::

Let's take a look at the GraphQL query for the `GetUsers` use case:

```graphql title="GraphQL query"
query users($search: String, $role: Role, $sortBy: UsersSortBy, $sortOrder: SortOrder, $limit: Int!, $offset: Int!) {
  users(search: $search, role: $role, sortBy: $sortBy, sortOrder: $sortOrder) {
    items(limit: $limit, offset: $offset) {
      id
      firstName
      lastName
      email
      locale
      role
    }
    count
  }
}
```

Here we can see that:

1. The enumerators are valid GraphQL types.
2. The `items` property with the `limit` and `offset` arguments plus the `count` property come from the `ResultIterator`.

### Enumerators

The folder *src/api/src/Domain/Enum* contains our enumerators.

Each enumerator's key (i.e., `FIRST_NAME`) is a GraphQL value, while each enumerator's value (i.e., `first_name`)
is a valid SQL expression.

:::note

📣&nbsp;&nbsp;The key is for your GraphQL query; it not valid, [GraphQLite](https://graphqlite.thecodingmachine.io/) 
throws an exception.

:::

:::note

📣&nbsp;&nbsp;The value is for creating SQL statements like the sort clause.

:::

### Result iterator

In the API, you will not manage the limit and offset of your data. That's the role of the `ResultIterator`.

### DAO

```php title="src/api/src/Domain/Dao/UserDao.php"
/**
 * @return User[]|ResultIterator
 */
public function search(
    ?string $search = null,
    ?Role $role = null,
    ?UsersSortBy $sortBy = null,
    ?SortOrder $sortOrder = null
): ResultIterator {
    $sortBy    = $sortBy ?: UsersSortBy::FIRST_NAME();
    $sortOrder = $sortOrder ?: SortOrder::ASC();

    return $this->find(
        [
            'first_name LIKE :search OR last_name LIKE :search OR email LIKE :search',
            'role = :role',
        ],
        [
            'search' => '%' . $search . '%',
            'role' => $role,
        ],
        $sortBy . ' ' . $sortOrder
    );
}
```

The goal of the DAO is to:

* Initialize the sort values.
* Build the SQL query.
* Retrieve the data from the database.

:::note

📣&nbsp;&nbsp;Reminder: an enumerator's value is for creating SQL statements like the sort clause.

:::

## Web application

In the API, a list means displaying data according to user's inputs.

### List mixin

The boilerplate provides the *src/webapp/mixins/list.js* mixin.

:::note

📣&nbsp;&nbsp;A mixin content merges with the content of your Vue component.

:::

This mixin contains useful data and methods to help you build a list.

### Initialization

The initialization of your page occurs in the `asyncData` attribute of your Vue Component:

```vue title="src/webapp/pages/admin/users/index.vue"
async asyncData(context) {
  try {
    const result = await context.app.$graphql.request(UsersQuery, {
      search: context.route.query.search || '',
      role: context.route.query.role || null,
      sortBy: context.route.query.sortBy || null,
      sortOrder: context.route.query.sortOrder || null,
      limit: defaultItemsPerPage,
      offset: calculateOffset(
        context.route.query.page || 1,
        defaultItemsPerPage
      ),
    })

    return {
      items: result.users.items,
      count: result.users.count,
    }
  } catch (e) {
    context.error(e)
  }
},
```

:::note

📣&nbsp;&nbsp;On page access, Nuxt.js always renders a Vue component with an `asyncData` attribute on the server; you don't have access to 
`this` but `context` instead.

:::

:::note

📣&nbsp;&nbsp;`asyncData` will merge with the `data` of your Vue component.

:::

First, we do a GraphQL query to retrieve our data.

The parameters' values may come from query parameters (i.e., `?foo=bar,baz=2`) or use a default value.

:::note

📣&nbsp;&nbsp;Don't use `null` as value for your scalar parameters as it will make your page reload. Prefer, 
for instance, an empty string. 

:::

The `limit` parameter uses the `defaultItemsPerPage` constant from the list mixin, but you may define a custom constant 
in your Vue component.

The `offset` parameter uses the `calculateOffset` method from the list mixin.

Once the GraphQL query finishes, there are two possible outcomes:

1. No error: your fills the `items` and `count` data from the list mixin.
2. An error occurs (access control mostly): you catch it and provide it to the `context` 
(see our [Security](/docs/guides/security) guide for more details).

:::note

📣&nbsp;&nbsp;You may display the data in your `<template>` block thanks to the `items`.

:::

### Filters

```vue title=""
data() {
  return {
    filters: {
      search: this.$route.query.search || '',
      role: this.$route.query.role || null,
    },
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
    sortByMap: {
      firstName: FIRST_NAME,
      lastName: LAST_NAME,
      email: EMAIL,
    },
  }
},
```

### Fields / Headers

If you are using a Bootstrap Vue's `<b-table>`, you also need to define the `fields` (i.e., the headers).

You do that in the `data` attribute of your Vue component:

```vue title=""
data() {
  return {
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
  }
},
```

```vue title="Bootstrap table"
<b-table
  :items="items"
  :fields="fields"
></b-table>
```

### Search

### Sort

### Paginate

### Full example

See *src/webapp/pages/admin/users/index.vue*.