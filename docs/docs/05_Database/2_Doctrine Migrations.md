---
title: Doctrine Migrations
slug: /database/doctrine-migrations
---


[TDBM](https://github.com/thecodingmachine/tdbm) integrates well with Symfony, as you are able to use the 
[DoctrineMigrationsBundle](https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html).

[TDBM](https://github.com/thecodingmachine/tdbm) provides wrappers around this library for:

1. Building your database structure with fluid schemas (i.e., `$x->foo()->bar()->baz()`).
2. Defining your GraphQL types and their fields.

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

## Create a migration

```bash title="console"
php bin/console doctrine:migrations:generate
```

This command will generate a new empty migration in the *src/api/migrations* folder.

Add a meaningful description:

```php
public function getDescription() : string
{
    return 'Create X, Y and Z tables.';
}
```

And throw the following exception the `down` method:

```php
public function down(Schema $schema) : void
{
    throw new RuntimeException('Never rollback a migration!');
}
```

You may now update the `up` method. For instance:

```php
use TheCodingMachine\FluidSchema\TdbmFluidSchema;

public function up(Schema $schema): void
{
    $db = new TdbmFluidSchema($schema);

    $db->table('users')
        ->column('id')->guid()->primaryKey()->comment('@UUID')->graphqlField()
        ->column('first_name')->string(255)->notNull()->graphqlField()
        ->column('last_name')->string(255)->notNull()->graphqlField()
        ->column('email')->string(255)->notNull()->unique()->graphqlField()
        ->column('password')->string(255)->null()->default(null)
        ->column('locale')->string(2)->notNull()->graphqlField()
        ->column('profile_picture')->string(255)->null()->default(null)->graphqlField()
        ->column('role')->string(255)->notNull()->graphqlField();

    $db->table('reset_password_tokens')
        ->column('id')->guid()->primaryKey()->comment('@UUID')->graphqlField()
        ->column('user_id')->references('users')->notNull()->unique()
        ->column('token')->string(255)->notNull()->unique()
        ->column('valid_until')->datetimeImmutable()->notNull();
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

## Apply migrations

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

📣&nbsp;&nbsp;Reminder: you should **only** do that if a remote environment like your production did not already 
apply the migration.

:::