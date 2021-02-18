---
title: Run
slug: /deployments/run
---

## API

In your remote environments, you will have two kind of instances:

1. A GraphQL API.
2. A consumer of messages from Redis (emails and asynchronous tasks).

For instance, if you use Docker Compose:

```yaml title="docker-compose.yml"
services:

  api:
    image: api_docker_image:docker_image_tag

  # Consumer for asynchronous tasks and emails.
  api_consumer:
    image: api_docker_image:docker_image_tag
    # The command to launch instead of the default one.
    command: php bin/console messenger:consume async
```

The API being stateless, you may also scale it without worries.