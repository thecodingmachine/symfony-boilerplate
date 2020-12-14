---
title: Models
slug: /database/models
---

Models are the PHP representation of your database's tables.

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

## Generate

```bash title="console"
php bin/console tdbm:generate
```

This command will regenerate the [TDBM](https://github.com/thecodingmachine/tdbm) 
models (and DAOs - more on that in the next chapter).

Models come in two kinds of classes. For instance:

* `BaseUser`.
* `User` that extends `BaseUser`.

[TDBM](https://github.com/thecodingmachine/tdbm) generates the first class, and it contains the default getters and setters.

You cannot modify it, but instead, edit the second class as [TDBM](https://github.com/thecodingmachine/tdbm) 
does not override it.

:::note

📣&nbsp;&nbsp;There are other kinds of classes in the *src/api/src/Domain/Model* folder, but
they are not related to [TDBM](https://github.com/thecodingmachine/tdbm).

:::

## Create an instance

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
