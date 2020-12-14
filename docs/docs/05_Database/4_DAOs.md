---
title: DAOs
slug: /database/daos
---

DAOs are classes where you can create, read, update, and delete data in your database according to a model.

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

## Generate

```bash title="console"
php bin/console tdbm:generate
```

This command will regenerate the [TDBM](https://github.com/thecodingmachine/tdbm) 
models and DAOs.

Like models, [TDBM](https://github.com/thecodingmachine/tdbm) generates two kinds of classes. For instance:

* `BaseUserDao`.
* `UserDao` that extends `BaseDao`.

The first class contains most of the methods allowing you to interact with the corresponding table in the database.

You cannot modify it, but instead, edit the second class as [TDBM](https://github.com/thecodingmachine/tdbm) 
does not override it.

## Save / Update

The base DAO provides a method `save` which takes the model instance as parameter:

```php
$foo = new Foo($bar);
$this->fooDao->save($foo);

$foo->setBaz($baz);
$this->fooDao->save($foo);
```

:::note

📣&nbsp;&nbsp;[TDBM](https://github.com/thecodingmachine/tdbm) generates the primary key.

:::


## Delete

The base DAO provides a method `delete` which takes the model instance as parameter:

```
$this->foodDao->delete($foo);
```

:::note

📣&nbsp;&nbsp;This method also takes a boolean argument for cascade deletion. However, it would be best to handle such 
scenarios with Use Cases composition.

:::

## Get By

The base DAO provides methods for retrieving a model from the primary key and unique properties:

```php
$foo = $this->fooDao->getById($id);
```

## Find All

The base DAO provides the method `findAll`:

```php
$foos = $this->fooDao->findAll();
```

:::note

📣&nbsp;&nbsp;This method returns a `ResultIterator`, a sort of array of instances. See the [Lists](/docs/lists)
guide for more details.

:::

## Find

The base DAO provides the method `find`:

```php
$foos = $this->fooDao->find(
    [
        // "WHERE".
        'bar LIKE :bar OR baz = :baz',
    ],
    [
        // Arguments of "WHERE".
        'bar' => '%' . $bar . '%',
        'baz' => $baz,
    ],
    // Sort column and direction.
    'id ASC'
);
```

:::note

📣&nbsp;&nbsp;If a parameter's value is `null`, [TDBM](https://github.com/thecodingmachine/tdbm)
automatically removes the corresponding conditions in the first argument.

:::

:::note

📣&nbsp;&nbsp;This method returns a `ResultIterator`, a sort of array of instances. See the [Lists](/docs/lists)
chapter for more details.

:::