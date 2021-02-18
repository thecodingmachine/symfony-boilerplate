<p align="center">
    <img src="https://thecodingmachine.github.io/symfony-boilerplate/img/logo.svg" alt="Symfony Boilerplate" width="250" height="250" />
</p>
<h3 align="center">Symfony Boilerplate</h3>
<p align="center"><a href="https://thecodingmachine.github.io/symfony-boilerplate">Documentation</a></p>

---

This is a template of a *README*. Adapt it according to the comments and your needs.

---

# Symfony Boilerplate

> Replace this title and the following description with your project name and description.

A web application built with Nuxt.js, Symfony 5, and GraphQL.

## Setup

### Prerequisites

#### Linux

Install the latest version of [Docker](https://docs.docker.com/install/) and 
[Docker Compose](https://docs.docker.com/compose/install/).

#### macOS

Consider installing [Vagrant](https://www.vagrantup.com/) and [VirtualBox](https://www.virtualbox.org/).

Indeed, Docker currently has substantial performance issues on macOS, and using Vagrant allows us to have an almost 
Linux-like experience regarding performances.

#### Windows

Consider using a Linux-like terminal to run the [Makefile](Makefile) commands. 
Vagrant might also be a solution regarding performances.

If not possible, you may also directly run the commands specified in the [Makefile](Makefile). 
For instance, instead of running `make up`, run `docker-compose up -d`.

### Hosts

Update your `hosts` file with the following entries:

```
127.0.0.1   traefik.symfony-boilerplate.localhost
127.0.0.1   symfony-boilerplate.localhost
127.0.0.1   api.symfony-boilerplate.localhost
127.0.0.1   phpmyadmin.symfony-boilerplate.localhost
127.0.0.1   minio.symfony-boilerplate.localhost
127.0.0.1   mailhog.symfony-boilerplate.localhost
```

> Update the domain with the one used in your project.

On Linux and macOS, run `sudo nano /etc/hosts` to edit it.

On Windows, edit the file `C:\Windows\System32\drivers\etc\hosts` with administrative privileges.

### First start

Copy the file [.env.dist](.env.dist) to a file named `.env`. For instance:

```
cp .env.dist .env
```

> Edit the [.env.dist](.env.dist) by updating the default values of `DOMAIN`, `MYSQL_DATABASE` and `APP_SECRET`
> environment variables.

---

#### Vagrant user

"Comment" the `STARTUP_COMMAND_3` and `STARTUP_COMMAND_4` environment variables from the `api` service 
in the [docker-compose.yml](docker-compose.yml) file.

Next, run:

```
docker-compose up webapp api
```

📣&nbsp;&nbsp;This command start the `webapp` and `api` service. While booting, these services install the JavaScript
and PHP dependencies. We cannot do that directly in the Vagrant VM as `yarn` and `composer install` fail miserably the
first time.

Once the services have installed the dependencies, you may stop them with:

```
CTRL+C
docker-compose down
```

Don't forget to uncomment the previous environments variables from the `api` service 
in the [docker-compose.yml](docker-compose.yml) file.

Next, check there is no application running on port 80 (like Apache or another virtual machine).

If OK, run `make vagrant`, then `vagrant up`, and finally `vagrant ssh` to connect to the virtual machine. 
From here, you'll be able to run all the next commands like Linux users!

> Update the variable `VAGRANT_PROJECT_NAME` from the [.env](.env) and [.env.dist](.env.dist) files with 
> your project name. Only use alphanumeric characters (no spaces, distinguish words with `_` or `-`).

---

Next, make sure there is no application running on port 80 (Vagrant users can skip this check).

Good? You may now start all the Docker containers with the following commands:

```
make up
```

It may take some time as each container will also set up itself, such as installing dependencies (PHP, JavaScript, etc.), 
compiling sources (JavaScript), or running migrations to set up the database structure.

**📣&nbsp;&nbsp;In some cases, the `api` service will try to run the migrations before the `mysql` service is ready. 
If so, restart the `api` service with `docker-compose up -d api`.**

The containers will be ready faster next time you run this command as the first run is doing most of the setup.

Once everything is ready, the following endpoints should be available:

* http://traefik.symfony-boilerplate.localhost (Reverse proxy, the entry point of all the HTTP requests)
* http://symfony-boilerplate.localhost (Web application)
* http://api.symfony-boilerplate.localhost (API)
* http://phpmyadmin.symfony-boilerplate.localhost (phpMyAdmin, a web interface for your MySQL database)
* http://minio.symfony-boilerplate.localhost (S3 compatible storage)
* http://mailhog.symfony-boilerplate.localhost (Emails catcher)

> Update the domain with the one used in your project.

You may now enter the `api` service and load the development data:

```
make api
php bin/console app:fixtures:dev
exit
```

**Last but not least, start the message consumer with:**
 
```
make consume
```

## What's next?

### Configuring Git

Git should ignore globally some folders like those generated by your IDE and Vagrant.

If not already done, you should tell Git where to find your global `.gitignore` file.

For instance, on Linux/macOS/Windows git bash:

```
git config --global core.excludesfile '~/.gitignore'
```

Windows cmd:

```
git config --global core.excludesfile "%USERPROFILE%\.gitignore"
```

Windows PowerShell:

```
git config --global core.excludesfile "$Env:USERPROFILE\.gitignore"
```

Then create the global `.gitignore` file according to the location specified previously.

You may now edit it, according to your environment, with:

```
# IDE
.idea
.vscode
# MacOS
.DS_Store
# Vagrant
.vagrant
```

### Documentations

Make sure you have read the following documentations:

**Day-to-day guidelines**

* [Web application guidelines](src/webapp/README.md)
* [API guidelines](src/api/README.md)

**In-depth explanations**

* See [docs](docs) folder.

### How to stop the stack?

As simple as the `make up` command, run `make down` to stop the entire Docker Compose stack.

If you're a Vagrant user, you may also stop the virtual machine with `vagrant halt`.

If you're not going to work on the project for a while, you may also destroy 
the virtual machine using `vagrant destroy`.

### How to view the logs of the Docker containers?

All aggregated logs:

```
docker-compose logs -f
```

Logs of one service:

```
docker-compose logs -f SERVICE_NAME
```

For instance, if you want the logs of the `api` service:

```
docker-compose logs -f api
```
