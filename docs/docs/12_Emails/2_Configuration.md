---
title: Configuration
slug: /emails/configuration
---

## Symfony Mailer and Symfony Messenger

The API uses [Symfony Mailer](https://symfony.com/doc/current/mailer.html) for sending emails and
[Symfony Messenger](https://symfony.com/doc/current/messenger.html) for doing it asynchronously.

Two advantages come with this asynchronous process:

* Sending emails does not slow your code execution.
* If one consumer cannot unstack the queue messages as fast as they arrive, you can add consumers on the fly to 
increase the unstack rate.

:::note

📣&nbsp;&nbsp;In your development environment, the API and the consumer are one; the `make consume` command starts the consumer
inside the `api` service. In your remote environments (like production), the API and the consumer should be two 
different services.

:::

## Configuration

Most of the configuration comes from the following environment variables:

* `MAILER_DSN`: Data Source Name of the emails' server (format: `protocol//user:password@hostname:port`).
* `MESSENGER_TRANSPORT_DSN`: Data Source Name of the Redis service (format: `protocol//user:password@hostname:port/messages`)
* `MAIL_TITLE`: the header value to use in the default email template (the application name by default).
* `MAIL_FROM_ADDRESS`: email address of the sender (usually `no-reply@your-domain.com`).
* `MAIL_FROM_NAME`: name of the sender (i.e., `MAIL_FROM_NAME <MAIL_FROM_ADDRESS>`).