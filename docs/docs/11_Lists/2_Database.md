---
title: Database
slug: /lists/database
---

## DAO

The first step is to write a query in a DAO class. For instance:

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

Here we have:

* A random filter, the `$search` string.
* A predictable filter, the `$role` enum.
* A sort direction and a sort column.

All the arguments are optional (`= null`).

:::note

📣&nbsp;&nbsp;If a parameter's value is `null`, [TDBM](https://github.com/thecodingmachine/tdbm)
automatically removes the corresponding conditions in the first argument of the `find` method.

:::

## ResultIterator

Thanks to the `ResultIterator` class, you may retrieve a precise scope of the resulting data:

```php
$result = $userDao->search();
$result->take($offset, $limit);
```

:::note

📣&nbsp;&nbsp;Most of the time, you won't have to explicitly call the `take` method 
thanks to [GraphQLite](https://graphqlite.thecodingmachine.io/). More on that in the next chapter.

:::

## Enum

The folder *src/api/src/Domain/Enum* contains our enums.

Each enum's key (i.e., `FIRST_NAME`) is a GraphQL value, while each enum's value (i.e., `first_name`)
is a valid SQL expression.

Most enums are for:

* Sort by values.
* Business data.