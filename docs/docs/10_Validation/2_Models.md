---
title: Models
slug: /validation/models
---

Most of the time, you want to validate a model's data.

In the Symfony Boilerplate, we distinguish three kinds of models:

1. The [TDBM](https://github.com/thecodingmachine/tdbm) models for business data.
2. The `Storable` models for uploads.
3. The `Proxy` models for data that do not fit in previous scenarios.

## TDBM Models

### Migrations

The first stone for validating your [TDBM](https://github.com/thecodingmachine/tdbm) models occurs in 
the Doctrine migrations.

:::note

📣&nbsp;&nbsp;See the [Database](/docs/database/doctrine-migrations) chapter for more details about Doctrine migrations.

:::

For instance, let's take a look at the Doctrine migration for the `users` table:

```php
public function up(Schema $schema): void
{
    $db = new TdbmFluidSchema($schema);
    
    $db->table('users')
        ->column('id')->guid()->primaryKey()->comment('@UUID')
        ->column('first_name')->string(255)->notNull()
        ->column('last_name')->string(255)->notNull()
        ->column('email')->string(255)->notNull()->unique()
        ->column('password')->string(255)->null()->default(null);
}
```

Here we are already defining rules:

* Scalar values (string, int, etc.).
* Nullable or not.
* Default values.
* Unique values.

### PHP Classes

Of course, most of these rules are not user-friendly nor developer-friendly as they occur on the database level.

Yet, after applying this migration, [TDBM](https://github.com/thecodingmachine/tdbm) is able to generate two PHP classes:

* `BaseUser`.
* `User` that extends `BaseUser`.

Let's take a look at the constructor's signature from the `BaseUser` class:

```php title="src/api/src/Domain/Model/Generated/BaseUser.php"
public function __construct(string $firstName, string $lastName, string $email, string $locale, string $role);
```

Here we can see that non-nullable properties are **mandatory**. Also, all the properties have an **explicit type**.

For getters and setters, it works the same:

```php title="src/api/src/Domain/Model/Generated/BaseUser.php"
public function getFirstName(): string;
public function setFirstName(string $firstName): void;
```

Overall, it greatly improves the developer experience as you cannot put a wrong type nor miss a mandatory property when
creating/updating an instance of a [TDBM](https://github.com/thecodingmachine/tdbm) model. 😉

### Annotations

That being said, it's still not enough. For instance, how to make sure a value is unique? Or a string is not superior to
256 characters?

You could let the database tell you about these issues, but that's usually done in a non developer-friendly way.

Thankfully, the [Symfony Validation](https://symfony.com/doc/current/validation.html) bundle provides most of the rules
(aka constraints) you may want to apply to a [TDBM](https://github.com/thecodingmachine/tdbm) model's property.

You may also add your own rules.

:::note

📣&nbsp;&nbsp;See the [Constraints](https://symfony.com/doc/current/validation.html#constraints) chapter of the official
documentation for the list of available rules.

:::

:::note

📣&nbsp;&nbsp;The folder *src/api/src/Domain/Constraint* contains our custom-made constraints.

:::

As we cannot modify the `BaseUser` class, we have to override the getters in the `User` class.

For instance, let's take a look at the `email` property getter:

```php title="src/api/src/Domain/Model/User.php"
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\NotBlank(message="not_blank")
 * @Assert\Length(max=255, maxMessage="max_length_255")
 * @Assert\Email(message="invalid_email")
 */
public function getEmail(): string
{
    return parent::getEmail();
}
```

In addition to type hint (non-nullable string), we add three rules:

1. The email cannot be blank.
2. The email cannot have a length superior to 255 characters.
3. The email has to be valid.

:::note

📣&nbsp;&nbsp;The message attribute contains a translation key. See the [i18n](/docs/i18n/api) chapter for more
details.

:::

You may also add a validation annotation to the class itself:

```php title="src/api/src/Domain/Model/User.php"
use App\Domain\Constraint as DomainAssert;
use TheCodingMachine\GraphQLite\Annotations\Type;

/*
 * @Type
 * @DomainAssert\Unicity(table="users", column="email", message="user.email_not_unique")
 */
class User extends BaseUser {}
```

In this scenario, we use our custom-made `Unicity` constraint that verify if a value does not already exist in the
database.

### DAOs

In addition to the model classes, [TDBM](https://github.com/thecodingmachine/tdbm) also generates the DAO classes.

Like the models, there are two of them:

* `BaseUserDao`.
* `UserDao` that extends `BaseDao`.

In the later, we have to inject a `ValidatorInterface`:

```php title="src/api/src/Domain/Dao/UserDao.php"
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TheCodingMachine\TDBM\TDBMService;

class UserDao extends BaseUserDao
{
    private ValidatorInterface $validator;

    public function __construct(TDBMService $tdbmService, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        parent::__construct($tdbmService);
    }
}
```

The `ValidatorInterface` provides the method `validate` that returns the list of all violations according to the model
constraints:

```php
/** User $user */
$violations = $this->validator->validate($user);
```

By convention, it's great to add a `validate` method in your DAOs:

```php title="src/api/src/Domain/Dao/UserDao.php"
use App\Domain\Throwable\InvalidModel;

/**
 * @throws InvalidModel
 */
public function validate(User $user): void
{
    $violations = $this->validator->validate($user);
    InvalidModel::throwException($violations);
}
```

This method throws an exception if there are any violations in the model.

Last but not least, you should override the `save` method from the base DAO:

```php title="src/api/src/Domain/Dao/UserDao.php"
/**
 * @throws InvalidModel
 */
public function save(User $user): void
{
    $this->validate($user);
    parent::save($user);
}
```

This approach has two HUGE benefits:
 
1. You centralize the action of validating at one place.
2. You **always** validate a model before saving it in the database.

## Storable Models

:::note

📣&nbsp;&nbsp;See the [Uploads](/docs/files/uploads) chapter for more details about uploads storage.

:::

### PHP Class

A storable is a wrapper around an upload. You may want to validate its extension, size, etc.

You have to extend the `Storable` class with a custom class:

```php title="src/api/src/Domain/Model/Storable/MyStorable.php"
final class MyStorable extends Storable {}
```

Here you may override or add custom getters.

Indeed, like the [TDBM](https://github.com/thecodingmachine/tdbm) models, a storable 
may uses Symfony Validation annotations. 😉

For instance, let's say you want to validate the upload's extension:

```php title="src/api/src/Domain/Model/Storable/MyStorable.php"
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\Choice({"png", "jpg"}, message="my_storable.invalid_extensions")
 */
public function getExtension(): string
{
    return parent::getExtension();
}
```

### Storage

A storage is like a DAO but for storables.

It provides methods for validating one or more storables:

```php title="src/api/src/Domain/Storage/Storage.php"
use App\Domain\Throwable\InvalidStorable;

/**
 * @param Storable[] $storables
 *
 * @throws InvalidStorable
 */
public function validateAll(array $storables): void;

/**
 * @throws InvalidStorable
 */
public function validate(Storable $storable): void;
```

Like the `save` method from a `DAO`, its `write` and `writeAll` methods also call the validation methods:

```php title="src/api/src/Domain/Storage/Storage.php"
use App\Domain\Throwable\InvalidStorable;

/**
 * @param Storable[] $storables
 *
 * @return string[]
 *
 * @throws InvalidStorable
 */
public function writeAll(array $storables): array;

/**
 * @throws InvalidStorable
 */
public function write(Storable $storable): string;
```

## Proxy Models

Proxy models are PHP classes that does not reflect a database's table nor an upload.

In other words, they are plain old PHP objects.

However, you may use Symfony Validation annotations on these models getters and validate them 
using the `ValidatorInterface` and `InvalidModel` classes. 😉

:::note

📣&nbsp;&nbsp;Don't forget to add the `@Type` and `@Field` annotations if the model should be available in GraphQL.

:::
