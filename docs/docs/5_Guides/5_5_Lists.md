---
title: Lists
slug: /guides/lists
---

## API

### Use case and result iterator

It starts by creating a use case. For instance, *src/api/src/UseCase/User/GetUsers.php*.

Your use case have to return a `ResultIterator` to paginate efficiently your results.
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

The `limit` and `offset` parameters from the `items` part do not exist in the use case parameters; 
it is the `ResultIterator` that takes these parameters.

Also, note the `count` value; that's the total of items.

We will see later how to use these values to create a pagination on the web application.

### Enumerators

For "sort by" and "sort order" values, we use enumerators (see *src/api/src/Domain/Enum/Filter* folder).

GraphQLite recognizes an enumerator as a valid value for your GraphQL request.

Each enumerator's key (i.e., `FIRST_NAME`) is a GraphQL value, while each enumerator's value (i.e., `first_name`)
is a valid SQL expression. 

For instance:

```graphql title="GraphQL query"
users(search: "foo", role: ADMINISTATOR, sortBy: LAST_NAME, sortOrder: DESC) {
  items(limit: 10, offset: 0) {
    id
    firstName
    lastName
    email
    locale
    role
  }
  count
}
```

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

## Web application

> IF default TEXT filters === null, it will refresh the page the first time!!

> CLIENT need to try catch and call this.$nuxt.error
> SERVER need to try catch and call context.error