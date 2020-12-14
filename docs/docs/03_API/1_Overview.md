---
title: Overview
slug: /api
---

We built the API with [Symfony 5](https://symfony.com/), [TDBM](https://github.com/thecodingmachine/tdbm), and 
[GraphQLite](https://graphqlite.thecodingmachine.io/).

The API is the backbone of the boilerplate:

* It centralizes most of the logic (use cases, validations, access control).
* Most of the data from and to the browser and other services transit through it.

## Architecture

The API's source code architecture is quite close to other architectures like Clean Architecture, but with shortcuts
to simplify it.

On the contrary to a classic MVC architecture, the API's architecture will help you with:

* Testing.
* Separation of concerns.
* Composition.

We separate the source code into three namespaces:

* `App\Domain`.
* `App\UseCase`.
* `App\Infrastructure`.

:::note

📣&nbsp;&nbsp;The boilerplate provides the [Deptrac](https://github.com/sensiolabs-de/deptrac) tool to enforce where you
should put your classes. More on that subject in the [Static Analysis](/docs/static-analysis/api) chapter.

:::

### Domain

The `App\Domain` namespace is where you'll find:

* Your `Models` (from the database, from the storage, etc.)
* Your `DAOs`, your `Storages`.
* Your `Enumerators`.
* Your base `Throwables`.

:::note

📣&nbsp;&nbsp;The `App\Domain` namespace can only call classes from its namespace.

:::

### UseCase

The `App\UseCase` namespace is where you'll find your... uses cases! 😁

What's a use case?

Let's say you want to create a `User` and send a reset password email.

You'll create two classes:

1. *src/api/src/UseCase/User/CreateUser.php*
2. *src/api/src/UseCase/User/ResetPassword/ResetPassword.php*

Why two? Because of composition!

Indeed, your `ResetPassword` use case might be useful for another scenario.

In your `CreateUser` use case, you'll write the logic for creating a user and saving it in the database.

Then you'll call your `ResetPassword` use case.

:::note

📣&nbsp;&nbsp;While developing, it's essential to think use case first.

:::

:::note

📣&nbsp;&nbsp;The use cases are also our GraphQL endpoints. See the [GraphQL](/docs/graphql) guide.

:::

:::note

📣&nbsp;&nbsp;The `App\Usecase` namespace can only call classes from its namespace and the `App\Domain` namespace.

:::

### Infrastructure

The `App\Infrastructure` namespace is where you'll find any other classes.

For instance:

* Your REST controllers.
* Your Symfony Commands.
* Etc.

:::note

📣&nbsp;&nbsp;The `App\Infrastructure` namespace can call classes from all namespaces.

:::