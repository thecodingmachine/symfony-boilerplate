---
title: API
slug: /tests/api
---

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

## Pest

[Pest](https://pestphp.com/) is a wrapper around [PHPUnit](https://phpunit.de/).

In the Symfony Boilerplate, we focus on testing the use cases.

### Usage

```bash title="console"
composer pest
```

It executes all tests and display the result and the code coverage in your terminal.

An HTML output is also available under the *src/api/coverage* folder.
Do not hesitate to take a look at it!

You can also run tests per group, for instance:

```bash title="console"
pest --group=user,company
```

### Configuration

Most of the configuration occurs in the *src/api/phpunit.xml.dist* file. Here, we:

* Override some environment variables.
* Exclude PHP files from code coverage.

### Good to know

* We use the `mysql_test` service as a database; before running tests, the PHP script *src/api/tests/bootstrap.php* 
destroys the database, re-creates it, and applies migrations.
* A rollback occurs after each test; if you need test data for all tests from a file, you may use the `beforeEach`
method. See, for instance, the file *src/api/tests/UseCase/User/GetUsersTest.php*.
* The Symfony mailer does not send emails.
* The `Storages` do not send files to the `minio` service but use the memory instead.

### Testing a use case

Let's say you have the following use case:

```php title="src/api/src/UseCase/Foo/CreateFoo.php"
final class CreateUser
{
    private FooDao $fooDao;
    private CreateBar $createBar;

    public function __construct(
        FooDao $fooDao,
        CreateBar $createBar
    ) {
        $this->fooDao    = $fooDao;
        $this->createBar = $createBar;
    }

    /**
     * @throws InvalidModel
     *
     * @Mutation
     */
    public function createFoo(
        string $bar,
        string $baz
    ): Foo {
        $bar = this->createBar($bar);
        $foo = new Foo(
            $bar,
            $baz
        );

        $this->fooDao->save($foo);

        return $foo;
    }
}
```

In the Pest file *src/api/tests/UseCase/Foo/CreateFooTest.php*, you should focus on testing the creation of a `Foo`
(violations, etc.).

In other words, do not test the use case `CreateBar`, as it should have its dedicated test.

```php title="src/api/tests/UseCase/Foo/CreateFooTest.php"
it(
    'creates a foo',
    function (
        string $bar,
        string $baz
    ): void {
        $createFoo = self::$container->get(CreateFoo::class);
        assert($createFoo instanceof CreateFoo);

        $foo = $createFoo->createFo(
            $bar,
            $baz
        );

        assertEquals($bar, $foo->getBar());
        assertEquals($baZ, $foo->getBaz());
    }
)
    ->with([
        ['foo', 'foo'],
        ['bar', 'bar'],
    ])
    ->group('foo');

it(
    'throws an exception if invalid foo',
    function (
        string $bar,
        string $baz
    ): void {
        $createFoo = self::$container->get(CreateFoo::class);
        assert($createFoo instanceof CreateFoo);

        $createFoo->createFo(
            $bar,
            $baz
        );
    }
)
    ->with([
        // Blank bar.
        ['', 'foo'],
        // Bar > 255.
        [DummyValues::CHAR256, 'foo'],
    ])
    ->throws(InvalidModel::class)
    ->group('foo');
```

:::note

📣&nbsp;&nbsp;You must import a use case somewhere in your source code; otherwise Pest (or PHPUnit) will not be able to 
inject it in your tests.

:::

## Behat

🚧