---
title: API
slug: /graphql/api
---

The Symfony Boilerplate uses [GraphQLite](https://graphqlite.thecodingmachine.io/) to quickly build a GraphQL API based
on your models and use cases.

As the documentation of this PHP library covers a lot of aspects, consider reading it to have a better understanding
of its functionalities.

## Queries

A GraphQL query is like a REST `GET` request.

Thanks to the `@Query` annotation, you may transform any method in the `App\UseCase` namespace into a valid GraphQL
endpoint.

```php title="App\UseCase\HelloWorld.php"
use TheCodingMachine\GraphQLite\Annotations\Query;

/**
 * @Query
 */
public function helloWorld(): string 
{
    return "Hello world";
}
```

Of course, this is a simple example. More complex examples are available in the source code of the application and in
the next chapters.

## Mutations

A GraphQL mutation is like a REST `POST` request.

Thanks to the `@Mutation` annotation, you may transform any method in the `App\UseCase` namespace into a valid GraphQL
endpoint.

```php title="App\UseCase\CreateFoo.php"
use TheCodingMachine\GraphQLite\Annotations\Mutation;

/**
 * @Mutation
 */
public function createFoo(string $bar): Foo 
{
    $foo = new Foo($bar);
    $this->fooDao->save($foo);
    
    return $foo;
}
```

Of course, this is a simple example. More complex examples are available in the source code of the application and in
the next chapters.

## Models

Thanks to `@Type` and `@SourceField` annotations, you may convert any PHP class in the `App\Domain\Model` namespace to
a valid GraphQL type:

```php title="src/api/src/Domain/Model/User.php"
use TheCodingMachine\GraphQLite\Annotations\SourceField;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type
 * @SourceField(name="id", outputType="ID")
 * @SourceField(name="firstName")
 * @SourceField(name="lastName")
 * @SourceField(name="email")
 * @SourceField(name="locale")
 * @SourceField(name="profilePicture")
 * @SourceField(name="role")
 * @SourceField(name="activated")
 */
class User extends BaseUser {}
```

The `@SourceField` annotation will look for methods named `{name}()`, `is{Name}()` and `get{Name}()` across the current
class or the parents classes.

:::note

📣&nbsp;&nbsp;Identifiers should have the `outputType="ID"` parameter.

:::

To allow [GraphQLite](https://graphqlite.thecodingmachine.io/) to automatically inject an instance of
`User` in a query or mutation, i.e:

```php
/**
 * @Mutation
 */
public function updateUser(User $user, string $firstName, string $lastName): User {}
```

You have to create a [GraphQLite](https://graphqlite.thecodingmachine.io/) factory in the DAO:

```php title="src/api/src/Domain/Dao/UserDao.php"
use TheCodingMachine\GraphQLite\Annotations\Factory;
use TheCodingMachine\GraphQLite\Annotations\HideParameter;

/**
 * @Factory
 * @HideParameter(for="$lazyLoading")
 */
public function getById(string $id, bool $lazyLoading = false): User
{
    return parent::getById($id, $lazyLoading);
}
```

In your GraphQL client, you may now call this mutation by using the `id` parameter of the factory:

```title="GraphQL"
mutation updateUser(
    $id: String!
    $firstName: String!
    $lastName: String!
) {
    updateUser(
        user: { id: $id }
        firstName: $firstName
        lastName: $lastName
    ) {
        id
        firstName
        lastName
    }
}
```

## Enums

Classes from the namespace `App\Domain\Enum` which extends `MyCLabs\Enum\Enum` are automatically converted to valid
GraphQL types.

For instance:

```php title="src/api/src/Domain/Enum/Role.php"
use MyCLabs\Enum\Enum;

/**
 * @method static Role ADMINISTRATOR()
 * @method static Role USER()
 */
final class Role extends Enum
{
    private const ADMINISTRATOR = 'ADMINISTRATOR';
    private const USER          = 'USER';
}  
```

```php
/**
 * @Mutation 
 */
public function updateUserRole(User $user, Role $role): User {}
```

```title="GraphQL"
mutation updateUserRole(
    $id: String!
    $role: Role! # "ADMINISTRATOR" or "USER"
) {
    updateUserRole(
        user: { id: $id }
        role: $role
    ) {
        id
        role
    }
}
```

