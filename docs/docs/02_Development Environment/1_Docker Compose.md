---
title: Docker Compose
slug: /development-environment/docker-compose
---

[Docker](https://docs.docker.com) and [Docker Compose](https://docs.docker.com/compose/) are the core technologies that orchestrate the
services of the boilerplate.

They will help you set up a complete development environment close to your target production infrastructure.

## Usage

* 🚀&nbsp;&nbsp;`make up` - starts the Docker Compose stack.
* 🚦&nbsp;&nbsp;`make down` - stops the Docker Compose stack.
* 🚇&nbsp;&nbsp;`make api` - runs `bash` in the `api` service.
* 🚇&nbsp;&nbsp;`make webapp` - runs `bash` in the `webapp` service.
* 📚&nbsp;&nbsp;`docker-compose logs -f` - displays the logs of **all** your services.
* 📘&nbsp;&nbsp;`docker-compose logs -f [SERVICE_NAME]` - displays the logs of one service.

## Configuration

Your development environment mostly consists of two files:

* *docker-compose.yml*
* *.env* file (and its template *.env.dist*)

The *docker-compose.yml* file lists all the services of the boilerplate and their configuration.
The services use mostly environment variables to configure themselves.
Most of the time, you will set their values directly in the *docker-compose.yml* file.

However, you don't want to commit your secrets to your Git repository. Also, you may want to reuse some values across
different services.

Docker Compose provides an easy way for such scenarios; it can read the values from the *.env* file.

For instance:

```.env title=".env"
FOO=hello
```

```yaml title="docker-compose.yml"
service_foo:
    environment:
      FOO: "$FOO"
```

Becomes at runtime (e.g., when running a Docker Compose command):

```yaml
service_foo:
    environment:
      FOO: "hello"
```

:::note

📣&nbsp;&nbsp;When adding a new variable in the *.env* file, don't forget to update the template *.env.dist* with it.
It will help other developers to notice this change and update their *.env* files accordingly.

:::

:::note

📣&nbsp;&nbsp;You should never commit the *.env* file as it may contain secrets; always use dummy values for your secrets 
in the *.env.dist* template.

:::

## Add a new service

The existing services might not be enough for your use cases.
You may therefore add new services to your *docker-compose.yml* file.

### Application Layer

```yaml title="docker-compose.yml"
services:

    your_service_name:
        image: an_image:a_tag
        labels:
            - traefik.enable=true
            - traefik.http.routers.your_service_name_router.rule=Host(`your_service_subdomain.$DOMAIN`)
            # If your service runs on another port than 80.
            # - traefik.http.routers.your_service_name_router.service=your_service_name_service
            # - traefik.http.services.your_service_name_service.loadbalancer.server.port=3000
        environment:
            FOO: "BAR"
        volumes:
            - src/your_service_source_code_folder:/path/in/the/docker/container    
```

:::note

📣&nbsp;&nbsp;Always add a service source code in the *src* folder.

:::

### Data Layer

```yaml title="docker-compose.yml"
services:

    your_service_name:
        image: an_image:a_tag
        environment:
            FOO: "BAR"
        volumes:
            - your_service_name_data:/path/in/the/docker/container

volumes:

      your_service_name_data:
        driver: local
```

## Extend a Docker Image

You might need to extend a Docker image for installing one or more packages.

For instance, let's say you want to install the `pdftk` package for the API:

```dockerfile title="src/api/Dockerfile"
FROM thecodingmachine/php:7.4-v4-apache AS extended

# Always use the root user for installing packages.
USER root

# Install PDFtk.
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y -qq --no-install-recommends pdftk &&\
    # Print versions of PDFtk.
    pdftk --version

# Go back to the default Docker image user.
USER docker

FROM extended
# Your production Docker image instructions.
```

```yaml title="docker-compose.yml"
api:
  #image: thecodingmachine/php:7.4-v4-apache
  build:
    context: "./src/api"
    target: "extended"
```

```makefile title="Makefile"
# Start the Docker Compose stack.
up:
    docker-compose up --build -d
```