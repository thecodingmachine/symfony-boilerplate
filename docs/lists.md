# Lists

This documentation will explain how to create lists, using GraphQL requests and Vue.js mixins.

## API

### Use case and result iterator

It starts by creating a use case. 
For instance, [src/api/src/UseCase/User/GetUsers](../src/api/src/UseCase/User/GetUsers.php).

Your use case have to return a `ResultIterator` to paginate efficiently your results.
Let's take a look at the GraphQL query for the [GetUsers](../src/api/src/UseCase/User/GetUsers.php) use case:

```graphql
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

For "sort by" and "sort order" values, we use enumerators 
(see [src/api/src/Domain/Enum/Filter](../src/api/src/Domain/Enum/Filter)).

GraphQLite recognizes an enumerator as a valid value for your GraphQL request.

Each enumerator's key (i.e., `FIRST_NAME`) is a GraphQL value, while each enumerator's value (i.e., `first_name`)
is valid SQL expression. 

See `search` method in [src/api/src/Domain/Dao/UserDao](../src/api/src/Domain/Dao/UserDao.php) for an example of usage.

## Web application

IF default TEXT filters === null, it will refresh the page the first time!!

CLIENT need to try catch and call this.$nuxt.error
SERVER need to try catch and call context.error

---

[Back to top](#lists) - [Home](../README.md)