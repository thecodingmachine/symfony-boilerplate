---
title: Send Emails
slug: /emails/send-emails
---

The Symfony Boilerplate provides the abstract class `CreateEmail`.
This class has a method `create`, which takes the following arguments:

```php title="src/api/src/UseCase/CreateEmail.php"
protected function create(User $user, string $subjectId, string $template, array $context): TemplatedEmail
```

* A recipient - an instance of `User`.
* A translation key for the email's subject (see [i18n](/docs/i18n/api) guide for more details).
* A template's path (more on that in the next chapter).
* The template's context (i.e., the data of the template).

:::note

📣&nbsp;&nbsp;This class does only create an `Email` object (it does not send it.).

::: 

You should extend this class to create your emails, for instance:

```php title="src/api/src/UseCase/Foo/CreateFooEmail.php"
final class CreateFooEmail extends CreateEmail
{
    public function createEmail(
        User $user,
        string $foo,
        string $bar
    ): TemplatedEmail {
        return $this->create(
            $user,
            'foo.subject',
            'emails/foo.html.twig', # Don't forget to prefix the path with "emails/".
            [
                'foo' => $foo,
                'bar' => $bar,
            ]
        );
    }
}
```

In the use case sending this email, you can inject both the previous class and the `MailerInterface`, i.e.:

```php title="src/api/src/UseCase/Foo/DoFoo.php"
private MailerInterface $mailer;
private CreateFooEmail $createFooEmail;

public function __construct(
    MailerInterface $mailer,
    CreateFooEmail $createFooEmail
) {
    $this->mailer = $mailer;
    $this->createFooEmail = $createFooEmail;
}
```

You can then use them as below:

```php title="src/api/src/UseCase/Foo/DoFoo.php"
$email = $this->createFooEmail->createEmail($user, $foo, $bar);
$this->mailer->send($email);
```

:::note

📣&nbsp;&nbsp;Always put your classes extending `CreateEmail` on the same level as the use case requiring it.

:::