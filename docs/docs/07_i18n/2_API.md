---
title: API
slug: /i18n/api
---

For the API, i18n has three goals:

1. Translate the validation error messages.
2. Translate the emails.
3. Translate the spreadsheets.

The [symfony/translation](https://symfony.com/doc/current/translation.html) package helps us for that task.

:::note

📣&nbsp;&nbsp;It requires the PHP extension `intl`, already configured in the `api` service.

:::

## Basic Usage

Symfony provides a `TranslatorInterface` you can inject in your class, i.e.:

```php
# A class.
private TranslatorInterface $translator;

public function __construct(
    TranslatorInterface $translator
) {
    $this->translator = $translator;
}
```

You can then use it as below:

```php
$translatedSubject = $this->translator
    ->trans(
        'translation_key',
        [],
        'the_domain', // More on that later.
        'the_locale'
    );
```

:::note

📣&nbsp;&nbsp;Most of the time, you don't have to use the `TranslatorInterface` as either Symfony or the parent class call it for you.

:::

## Translations Folder

Folder *src/api/translations* contains one YAML file per locale and domain.

A domain is a sort of scope. For instance, *src/api/translations/emails.en.yaml* and *src/api/translations/emails.fr.yaml* 
are both for the `email` domain (used for translating emails!).

Each of these files contains translation keys and the associated texts.

For instance:

```yaml title="src/api/translations/foo.en.yaml"
foo:
  baz: "Hello"
```

:::note

📣&nbsp;&nbsp;All files from the same domain should have the same organization (same translations keys, identical sorting, etc.).

:::

## Validation

Let's say you have a class with the following validation annotations:

```php title="src/api/src/Domain/Model/User.php"
/**
 * @Field
 * @Assert\NotBlank(message="not_blank")
 * @Assert\Length(max=255, maxMessage="max_length_255")
 */
public function getFirstName(): string
{
    return parent::getFirstName();
}
```

The `message` property of each `Assert` annotation is a translation key from the `validators` domain:

```yaml title="src/api/translations/validators.en.yaml"
not_blank: "This value should not be blank."
max_length_255: "This value is too long. It should have 255 characters or less."
```

```yaml title="src/api/translations/validators.fr.yaml"
not_blank: "Cette valeur ne doit pas être vide."
max_length_255: "Cette valeur est trop longue. Elle doit avoir 255 caractères ou moins."
```

The web application implements a mechanism for setting the correct locale to translate these validation error
messages (see [interactions between the web application and the API chapter](/docs/i18n/api-and-web-application-interactions)).

## Emails

Emails translation uses the `emails` domain. The corresponding YAML files are:

* *src/api/translations/emails.en.yaml*
* *src/api/translations/emails.fr.yaml*

Let's take a look at the `CreateEmail` use case:

```php title="src/api/src/UseCase/CreateEmail.php"
protected function create(User $user, string $subjectId, string $template, array $context): TemplatedEmail
```

The method `create` takes, among other arguments, a `User` instance and the translation key 
of the email's subject. The `User` has a `locale` property used for translating both the email's subject and its content.

See the [Emails](/docs/emails) guide for more details on how to extend this use case.

The Twig templates of your emails should look like this:

```twig title="src/api/templates/emails/foo.html.twig"
{% extends 'emails/email.html.twig' %}
{% trans_default_domain domain %}

{% block body %}
    {% apply inky_to_html|inline_css(source('@css/foundation-emails.css'), source('@css/emails.css')) %}
        <h1>{% trans into locale %}translation_key{% endtrans %}</h1>
   {% endapply %}
{% endblock %}
```

The `CreateEmail` use case will provide both `domain` and `locale` values.

## Spreadsheets

Spreadsheets translation uses the `spreadsheets` domain. The corresponding YAML files are:
                                                         
* *src/api/translations/spreadsheets.en.yaml*
* *src/api/translations/spreadsheets.fr.yaml*

As you might want to translate the headers and cell values of your XLSX exports, the boilerplate provides examples
on how of do so.

Let's take a look at the `CreateXLSXExport` use case:

```php title="src/api/src/UseCase/CreateXLSXExport.php"
public function create(string $locale, array $headerIds, array $values): Xlsx
```

The method `create` takes, among other arguments, a locale. It will use it to translate the spreadsheet's headers
accordingly.

For values, you should translate them directly in your use cases before calling the `create` method.
