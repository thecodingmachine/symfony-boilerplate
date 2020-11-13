---
title: Database
slug: /api/database
---

The boilerplate uses [TDBM](https://github.com/thecodingmachine/tdbm) as ORM. It is an alternative to Doctrine or Eloquent, 
yet it is pretty close.

The main difference is that with TDBM, you write your database's tables first before generating your models.

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

## Migrations

TDBM integrates well with Symfony, as you are able to use the 
[DoctrineMigrationsBundle](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html).

TDBM provides wrappers around this library for:

1. Building your database structure with fluid schemas (i.e., `$x->foo()->bar()->baz()`).
2. Defining your GraphQL types and their fields.

### Create a migration

```bash title="console"
php bin/console doctrine:migrations:generate
```

This command will generate a new empty migration in the *src/api/migrations* folder.

Add a meaningful description:

```php title="Migration"
public function getDescription() : string
{
    return 'Create X, Y and Z tables.';
}
```

And throw the following exception the `down` method:

```php title="Migration"
public function down(Schema $schema) : void
{
    throw new RuntimeException('Never rollback a migration!');
}
```

You may now update the `up` method. For instance:

```php title="src/api/migrations/Version20200424093138.php"
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use RuntimeException;
use TheCodingMachine\FluidSchema\TdbmFluidSchema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424093138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create companies and users tables.';
    }

    public function up(Schema $schema): void
    {
        $db = new TdbmFluidSchema($schema);

        $db->table('companies')
            ->column('id')->guid()->primaryKey()->comment('@UUID')->graphqlField()
            ->column('name')->string(255)->notNull()->graphqlField();

        $db->table('users')
            ->column('id')->guid()->primaryKey()->comment('@UUID')->graphqlField()
            ->column('company_id')->references('company_id')->notNull()->graphqlField()
            ->column('first_name')->string(255)->notNull()->graphqlField()
            ->column('last_name')->string(255)->notNull()->graphqlField()
            ->column('email')->string(255)->notNull()->->unique()->graphqlField()
            ->column('password')->string(255)->null()->default(null);
    }

    public function down(Schema $schema): void
    {
        throw new RuntimeException('Never rollback a migration!');
    }
}
```

:::note

📣&nbsp;&nbsp;A table name should be plural.

:::

If you're updating an existing table, it would be better to edit the corresponding migration instead of creating 
a new migration.

:::note

📣&nbsp;&nbsp;You should **only** do that if a remote environment like your production did not already apply the migration.

:::

### Apply migrations

```bash title="console"
php bin/console doctrine:migrations:migrate -n
```

This command will apply the new migrations to the database.

:::note

📣&nbsp;&nbsp;In development, the `api` service does it on startup.

:::

If you've edited an existing migration, you'll have to reset the database first:

```bash title="console"
php bin/console doctrine:database:drop -n --force &&
php bin/console doctrine:database:create -n &&
php bin/console doctrine:migrations:migrate -n
```

:::note

📣&nbsp;&nbsp;Reminder: you should **only** do that if a remote environment like your production did not already apply the migration.

:::

### Generate PHP classes

```bash title="console"
php bin/console tdbm:generate
```

This command will regenerate the TDBM `Models` and `DAOs`.

## Models

`Models` come in two kinds of classes. For instance:

1. *src/api/src/Domain/Model/Generated/BaseUser.php*
2. *src/api/src/Domain/Model/User.php*

TDBM generates the first class, and it contains the default getters and setters.

You cannot modify it, but instead, edit the second class as TDBM does not override it.

The second class is where you should put your validation annotations. More on that subject in the [Validation](/docs/guides/validation)
guide.

:::note

📣&nbsp;&nbsp;There are other kinds of classes in the *src/api/src/Domain/Model* folder (`Storables` for instance), but
they are not related to TDBM.

:::

### Create an instance

Let's say you have a model `Foo` with the following properties:

* `bar`, non-nullable.
* `baz`, nullable.

```php
$foo = new Foo($bar);
$foo->setBaz($baz);
```

:::note

📣&nbsp;&nbsp;A constructor of a model requires non-nullable values.

:::

## DAOs

Like `Models`, TDBM generates two kinds of classes. For instance:

1. *src/api/src/Domain/Dao/Generated/BaseUserDao.php*
2. *src/api/src/Domain/Dao/UserDao.php*

The first class contains most of the methods allowing you to interact with the corresponding table in the database.

You cannot modify it, but instead, edit the second class as TDBM does not override it.

### Save / update

The base `DAO` provides a method `save` which takes the `Model` instance as parameter:

```php
$foo = new Foo($bar);
$this->fooDao->save($foo);

$foo->setBaz($baz);
$this->fooDao->save($foo);
```

:::note

📣&nbsp;&nbsp;TDBM generates the primary key.

:::

As you might validate this `Model` instance, you should override this method in your `DAO`:

```php title="src/api/src/Domain/Dao/FooDao.php"
namespace App\Domain\Dao;

use App\Domain\Dao\Generated\BaseFooDao;
use App\Domain\Model\Foo;
use App\Domain\Throwable\InvalidModel;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TheCodingMachine\TDBM\TDBMService;

class FooDao extends BaseFoorDao
{
    private ValidatorInterface $validator;

    public function __construct(TDBMService $tdbmService, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        parent::__construct($tdbmService);
    }

    /**
     * @throws InvalidModel
     */
    public function validate(Foo $foo): void
    {
        $violations = $this->validator->validate($foo);
        InvalidModel::throwException($violations);
    }

    /**
     * @throws InvalidModel
     */
    public function save(Foo $foo): void
    {
        $this->validate($user);
        parent::save($user);
    }
}
```

:::note

📣&nbsp;&nbsp;Now, wherever you call the `save` method, your DAO will validate the `Model` data.

:::

### Delete

The base `DAO` provides a method `delete` which takes the `Model` instance as parameter:

```
$this->foodDao->delete($foo);
```

:::note

📣&nbsp;&nbsp;This method also takes a boolean argument for cascade deletion. However, it would be best to handle such
a scenario using composition (see *src/api/src/UseCase/Company/DeleteCompany.php*).

:::

### Get by

The base `DAO` provides methods for retrieving a model from the primary key and unique properties:

```php
$foo = $this->fooDao->getById($id);
```

### Find all

The base `DAO` provides the method `findAll`:

```php
$foos = $this->fooDao->findAll();
```

:::note

📣&nbsp;&nbsp;This method returns a `ResultIterator`, a sort of array of instances. See the [Lists](/docs/guides/lists)
guide for more details.

:::

### Find

The base `DAO` provides the method `find`:

```php
$foos = $this->fooDao->find(
    [
        'bar LIKE :bar OR baz = :baz',
    ],
    [
        'bar' => '%' . $bar . '%',
        'baz' => $baz,
    ],
    'id ASC'
);
```

The first argument is the `WHERE` clause.

The second argument is the parameters of the `WHERE` clause.

:::note

📣&nbsp;&nbsp;If a parameter's value is `null`, TDBM automatically removes the corresponding conditions in the first
argument.

:::

The third argument is the sort order and direction.

:::note

📣&nbsp;&nbsp;This method returns a `ResultIterator`, a sort of array of instances. See the [Lists](/docs/guides/lists)
guide for more details.

:::

## Development data

The *src/api/src/Infrastructure/Command/DevFixturesCommand.php* class provides a Symfony command for initializing your
development database with dummy data:

```bash title="console"
php bin/console app:fixtures:dev
```

It uses the class *src/api/src/Infrastructure/Fixtures/AppFixtures.php* for that task.

You should edit according to your needs.