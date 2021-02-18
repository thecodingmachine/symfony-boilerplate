---
title: GraphQL
slug: /validation/graphql
---

Most of the time, validation for the GraphQL API does not require extra work:

* [GraphQLite](https://graphqlite.thecodingmachine.io/) translates your models (with `@Type` and `@SourceField` annotations) 
into strongly typed GraphQL types.
* [GraphQLite](https://graphqlite.thecodingmachine.io/) translates your use cases' methods' signatures 
(with `@Mutation` or `@Query` annotations) into strongly typed GraphQL mutations or queries.
* [GraphQLite](https://graphqlite.thecodingmachine.io/) resolves the `InvalidModel` and `InvalidStorable` exceptions 
into valid GraphQL responses (400 HTTP code).

However, you may have some use cases that are GraphQL mutations or queries, and these use cases do not manipulate models
(i.e., no `InvalidModel` nor `InvalidStorable` exceptions).

For such scenarios, [GraphQLite](https://graphqlite.thecodingmachine.io/) provides the `@Assertion` annotation:

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

Here, [GraphQLite](https://graphqlite.thecodingmachine.io/) validates the `email` argument according to the list of
constraints.

Only caveat is that it does not work if you call your use case in PHP.