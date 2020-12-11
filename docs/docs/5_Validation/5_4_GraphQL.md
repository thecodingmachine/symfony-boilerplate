---
title: GraphQL
slug: /validation/graphql
---

Thanks to [GraphQLite](https://graphqlite.thecodingmachine.io/), validation for the GraphQL API does not require
extra work (usually):

* [GraphQLite](https://graphqlite.thecodingmachine.io/) translates your models (with `@Type` and `@Field` annotations) 
into strongly typed GraphQL types.
* [GraphQLite](https://graphqlite.thecodingmachine.io/) translates your use cases' methods' signatures 
(with `@Mutation` or `@Query` annotations) into strongly typeed GraphQL mutations or queries.
* [GraphQLite](https://graphqlite.thecodingmachine.io/) resolves the `InvalidModel` and `InvalidStorable` exceptions 
into valid GraphQL responses (400 HTTP code).

However, you may have some use cases that are GraphQL mutations or queries, and these use cases do not manipulate models
(i.e., no `InvalidModel` nor `InvalidStorable` exceptions).

The solution is actually quite simple. For instance:

```php title="src/api/src/UseCase/User/ResetPassword/ResetPassword.php"
use Symfony\Component\Validator\Constraints as Assert;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\Graphqlite\Validator\Annotations\Assertion;

/**
 * @Mutation
 * @Assertion(for="email", constraint={@Assert\NotBlank(message="not_blank"), @Assert\Length(max=255, maxMessage="max_length_255"), @Assert\Email(message="invalid_email")})
 */
public function resetPassword(string $email): bool;
```

Here, [GraphQLite](https://graphqlite.thecodingmachine.io/) validates the argument `email` according to the list of
constraints.

Only caveat is that it does not work if you call your use case in PHP.