---
title: Access Control
slug: /security/access-control
---

## Authorization Levels

### API

In Symfony, roles (i.e., `ROLE_FOO`) represent authorization levels.

In the boilerplate, we defined two hierarchical roles: administrator and user.
Hierarchical means that:

1. The administrator is the top-level permission: it has its access level and user's access levels.
2. A user has only its access level.

In other words, if you limit the access to a resource to users with the administrator authorization level, 
administrator users can access it too but not the user.

```yaml title="src/api/config/packages/security.yaml"
role_hierarchy:
    ROLE_ADMINISTATOR: ROLE_USER
```

As explained in the previous chapter, we implemented the `getRoles` method from the `UserInterface`. 
This method has to return an array of string. 

However, our users have only one authorization level attached to them (thanks to the hierarchy).
That's why we always return an array of one element:

```php title="src/api/src/Domain/Model/User.php"
public function getRoles(): array
{
    return [
        'ROLE_' . $this->getRole(),
    ];
}
```

Moreover, we create the `Role` enumerator, which lists our users'`role` property's available values:

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

These values don't have the prefix `ROLE_` because 
we don't want to store Symfony specific information in the `users` table. 

Yet, this prefix is mandatory because otherwise, Symfony will not recognize the permission.

That's why we prefix the role whenever we interact with Symfony in our code:

```php
@Security("is_granted('ROLE_ADMINISTRATOR')")
```

```php
$this->security->isGranted(Role::getSymfonyRole(Role::ADMINISTRATOR()));
```

:::note

📣&nbsp;&nbsp;A user **must have one authorization level**; otherwise authentication won't work.

:::

### Web Application

The file *src/webapp/store/auth/getters.js* from the `auth` store mimics the role hierarchy from Symfony:

```js title="src/webapp/store/auth/getters.js"
import { ADMINISTRATOR, USER } from '@/enums/roles'

function level(role) {
  switch (role) {
    case ADMINISTRATOR:
      return 2
    case USER:
      return 1
    default:
      return 0
  }
}
```

```js title="src/webapp/enums/roles.js"
export const ADMINISTRATOR = 'ADMINISTRATOR'
export const USER = 'USER'
```

## Access Control

Access control in the API is about defining what kind of users (anonymous, authenticated, administrator, etc.) 
may call (or not) an HTTP entry point.

In the API, there are three sorts :

1. Symfony's routes.
2. GraphQL mutations/queries.
3. The GraphQL fields.

### Symfony Routes' Annotations

*Restrict to authenticated users:*

```php
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/download/foo", methods={"GET"})
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
public function downloadFoo(Request $request): Response
```

*Restrict to authenticated users with a specific role:*

```php
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/download/foo", methods={"GET"})
 * @Security("is_granted('ROLE_ADMINISTRATOR')")
 */
public function downloadFoo(Request $request): Response
```

See the [security](https://symfony.com/doc/current/security.html) and 
[annotations](https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/security.html) documentations
from Symfony for more details.

### GraphQLite Annotations

[GraphQLite](https://graphqlite.thecodingmachine.io/) provides many Symfony like annotations, 
**even if they differ slightly on some occasions**. The import statements are also different.

*Restrict to authenticated users:*

```php
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

/**
 * @Mutation
 * @Logged
 */
public function updateFoo(
    string $foo
))
```

*Inject the authenticated user:*

```php
use TheCodingMachine\GraphQLite\Annotations\InjectUser;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

/**
 * @Mutation
 * @Logged
 * @InjectUser(for="$user")
 */
public function updateFoo(
    User $user,
    string $foo
)
```

*Restrict to authenticated users with a specific role:*

```php
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Security;

/**
 * @Mutation
 * @Logged
 * @Security("is_granted('ROLE_ADMINISTRATOR')")
 */
public function updateFoo(
    string $foo
)
```

:::note

📣&nbsp;&nbsp;Contrary to Symfony's routes, always put the `@Logged` annotation before the `@Security` and `@InjectUser` annotations 
on your GraphQL entry points. The web application needs to know the difference between unauthenticated (`401`) 
and access denied (`403`)!

::: 

See [GraphQLite documentation](https://graphqlite.thecodingmachine.io/docs/fine-grained-security) for more details.

### Symfony's Voters

Sometimes it is not enough to restrict access to authenticated users/users with a specific role.
For instance, when a resource is only accessible to the user owning it.

That's when Symfony's voters come in handy!

It comes in two parts:

1. The PHP class which is specifying the voter's rules.
2. The annotation we put on GraphQL mutations/queries and Symfony's routes.

#### GraphQL

For instance, let's examine the following scenario: an administrator can delete a user, but cannot delete himself:

```php title="src/api/src/UseCase/Product/UpdateProduct.php"
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Security;

/**
 * @Mutation
 * @Logged
 * @Security("is_granted('DELETE_USER', user1)")
 */
public function deleteUser(User $user1): bool
```

A voter annotation has two arguments:

1. The attribute: in our application, it's equivalent to an action, i.e., `DELETE_USER`, `GET_USER`, etc.
2. The subject: mostly the model on which we want to check ownership.

Here the annotation asks for a voter that may handle the `DELETE_USER` attribute for the `user1` subject.

By convention, we've created a voter PHP class per subject. In that case, as the subject is a `Uver`, we've made the 
`UserVoter` class.

Each voters' PHP class consist of three parts:

1. The attributes constants.
2. The method `supports`: it returns `true` if the voter supports both the given attribute and subject.
3. The method `voteOnAttribute`: only called if the `supports` method returned `true`. It contains your custom logic
for validating (or not) the access.

Take a closer look at those methods from `UserVoter` for a better understanding.

#### REST

In your Symfony's routes, you may not have access to a model directly but an `id` instead:

```php
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders/{id}/invoice", methods={"GET"})
 * @Security("is_granted('DOWNLOAD_ORDER_INVOICE', id)")
 */
public function downloadInvoice(string $id): Response;
```

In your Symfony's Voter, you have to check if the subject is either a model or a string:

```php
/**
 * @param mixed $subject
 */
protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
{
    // Some logic here...

    if ($subject instanceof Order) {
        $order = $subject;
    } else {
        $order = $this->orderDao->getById($subject);
    }

    // Some logic here...
}
```

### GraphQL Fields

Usually, you define your GraphQL types' fields in your migrations or your `Model`'s getters in the getters of your
when overriding a base `Model`'s getter. That's when you must decide if you want to expose or not the field
to your GraphQL clients.

Also, as you are developing both the clients and the API, securing the entry points should be enough. If that's not the
case, you can add the same `@Security` annotations to your getters as the ones from the mutations/queries.

### Web Application

The *src/webapp/layouts/error.vue* layout handles almost every error.

You can propagate a GraphQL error via `context.error(e)` in the `asyncData` component's attribute or `this.$nuxt.error(e)`
in your component's methods (except mixins, where you have to throw it):

```js title="Vue component <script> block"
import { MustBeLoggedAndAdministratorQuery } from '@/graphql/examples/must_be_logged_and_administrator.query'

export default {
  // Server-side call.
  async asyncData(context) {
    try {
      const result = await context.app.$graphql.request(MustBeLoggedAndAdministratorQuery)
    } catch (e) {
      context.error(e)
    }
  },
  methods: {
    // Browser call.
    async doAction() {
      try {
        const result = await this.$graphql.request(MustBeLoggedAndAdministratorQuery)
      } catch (e) {
        this.$nuxt.error(e)
      }
    },
  },
}
```

In the error layout, we check if:

* `401` status code: the user has no session in the API. Therefore, we call the `resetUser` mutation and redirect the
user to the login page. On success, the web application redirects the user to the current page thanks to the `redirect`
query parameter. 
* `404`, `403`, or anything else: we display an error page.

Some pages are also not available for the authenticated user (for instance, the login page). 
You may use the *src/webapp/middleware/redirect-if-authenticated.js* middleware to redirect the user to the home page:

```js title="Vue component <script> block"
export default {
  middleware: ['redirect-if-authenticated'],
}
```

If a page requires to be authenticated but does not query protected GraphQL entry points / Symfony routes, you may also
use the *src/webapp/middleware/redirect-if-not- authenticated.js* middleware to redirect the user to the home page:

```js title="Vue component <script> block"
export default {
  middleware: ['redirect-if-not-authenticated'],
}
```
