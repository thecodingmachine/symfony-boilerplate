---
title: GraphQL
slug: /lists/graphql
---

In the previous chapter, we define a `search` method in a DAO.

We now have to create a GraphQL query from it:

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

It's simple as that! 😉

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

1. The enums are valid GraphQL types.
2. The `items` property with the `limit` and `offset` arguments plus the `count` property come from the `ResultIterator`.

