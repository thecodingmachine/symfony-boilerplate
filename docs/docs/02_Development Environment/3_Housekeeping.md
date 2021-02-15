---
title: Housekeeping
slug: /development-environment/housekeeping
---

👉&nbsp;&nbsp;The sooner, the better!

## Docker and Docker compose

Make sure you always use the latest versions of Docker and Docker Compose.

See [Docker and Docker Compose releases](https://docs.docker.com/release-notes/).

### Linux

For non-Vagrant users, follow the [Docker](https://docs.docker.com/engine/install/#server)
and [Docker Compose](https://docs.docker.com/compose/install/#install-compose-on-linux-systems) official documentation.

### Vagrant

For Vagrant users, update the variable `VAGRANT_DOCKER_COMPOSE_VERSION` from the *.env* file and run:

```bash title="console"
# If the VM is running.
vagrant halt

# Always.
vagrant destroy
make vagrant
vagrant up

# Versions check.
vagrant ssh
docker --version
docker-compose --version
```

It will re-create the Vagrant VM with the latest versions of Docker and Docker Compose.

:::note

📣&nbsp;&nbsp;Don't forget to update the *.env.dist* file for your colleagues too 😉

:::

### Docker Compose file

Each service from the *docker-compose.yml* file uses a Docker image and a version number.

By default, most of the versions should use the `X.Y` format (`X` for major updates, `Y` for minor ones).

The idea here is that running `docker-compose pull` will only update Docker images with bugs fixes.

For major and minor updates, read the patch note and the related documentation carefully before updating 
your *docker-compose.yml* file.

Releases:

* [Traefik](https://github.com/containous/traefik/releases)
* [TheCodingMachine NodeJS](https://github.com/thecodingmachine/docker-images-nodejs#images)
* [TheCodingMachine PHP](https://github.com/thecodingmachine/docker-images-php#images)
* [MySQL](https://hub.docker.com/_/mysql?tab=tags)
* [phpMyAdmin](https://github.com/phpmyadmin/phpmyadmin/releases)
* [Redis](https://hub.docker.com/r/bitnami/redis/tags)
* [MailHog](https://github.com/mailhog/MailHog/releases)
* [MinIO](https://github.com/minio/minio/releases)

## Vagrant and VirtualBox

Run `vagrant version` to see your current version and the latest one. 
Follow the printed instructions for upgrading Vagrant if required.

For VirtualBox, open the application, it should tell you to download the newer version (if any).

:::note

📣&nbsp;&nbsp;From time to time, you may also update the `VAGRANT_BOX` variable from the *.env* file (and from *env.dist*)
with a newer [Ubuntu box](https://app.vagrantup.com/bento). The update process for Vagrant users is the same as updating
the variable `VAGRANT_DOCKER_COMPOSE_VERSION`.

:::