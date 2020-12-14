---
title: Development Data
slug: /database/development-data
---

:::note

📣&nbsp;&nbsp;All commands have to be run in the `api` service (`make api`).

:::

The `DevFixturesCommand` class provides a Symfony command for initializing your
development database with dummy data:

```bash title="console"
php bin/console app:fixtures:dev
```

It uses the class `AppFixtures` for that task. You should edit it according to your needs.