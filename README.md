**Caution:** this is still work in progress.

---

# Companies and products

> Replace this title and the following description with your project name and description.

An example of a web application built with Nuxt.js, Symfony 5 and GraphQL.

## Setup

### Prerequisites

#### Linux

Install the latest version of [Docker](https://docs.docker.com/install/) and 
[Docker Compose](https://docs.docker.com/compose/install/).

#### MacOS

We strongly recommend installing [Vagrant](https://www.vagrantup.com/) and 
[VirtualBox](https://www.virtualbox.org/).

Indeed, Docker has currently huge performance issues on MacOS and using
Vagrant allows us to abstract the MacOS filesystem bringing an almost Linux-like experience regarding performances.

#### Windows

We strongly advise to use a Linux-like terminal in order to run the `Makefile` commands.
Vagrant might also be an interesting solution regarding performances.

If not possible, you may also directly run the commands specified in the `Makefile`. 
For instance, instead of running `make up`, run `docker-compose up -d`.

### MacOS and Windows specific

Update your `hosts` file with the following entry:

```
127.0.0.1   *.localhost
```

On MacOs, run `sudo nano /etc/hosts` to edit it.

On Windows, edit the file `C:\Windows\System32\drivers\etc\hosts` with administrative privileges.

If you're using Vagrant, check there is no application running 
on port 80 (like Apache or another virtual machine).

If OK, run `make vagrant`, then `vagrant up` and finally `vagrant ssh` 
in order to connect to the virtual machine. From here you'll be able to run all the next commands like
the Linux users!

> Update the variable `PROJECT_NAME` from the `Makefile` with your own project name.
> Only use alphanumeric characters (no spaces, distinguish words with `_` or `-`).

### Starting the Docker Compose stack

Copy the file `.env.dist` to a file named `.env`. For instance:

```
cp .env.dist .env
```

> Edit the `.env.dist` by updating the default values of `DOMAIN`, `MYSQL_DATABASE` and `APP_SECRET`
> environment variables.

Next make sure there is no application running on port 80 (Vagrant users can skip this check).

**Vagrant users: the first time your start the Docker Compose stack, you have to comment the following 
environment variables from the `api` service in the `docker-compose.yml` file: `STARTUP_COMMANDS_2`, `STARTUP_COMMAND_3` 
and `STARTUP_COMMAND_4`. Indeed, `composer install` fails miserably but there is a workaround. See below.**

Good? You may now start all the Docker containers with the following commands:

```
make up
```

It may take some time as each container will also setup itself, for instance by
installing dependencies (PHP, JavaScript etc.), compiling sources (JavaScript) 
or run migrations for setting up the database structure.

Next time you run this command, the containers should be ready faster as most of the 
setting up will already be done.

**Vagrant users: enter the `api` service with `make api`. Here run `composer install --prefer-source` (and prepare some coffee). 
When done, exit the container, uncomment the previous environments variables and run `make up` again.**

Once everything is ready, the following endpoints should be available:

* http://traefik.companies-and-products.localhost (Reverse proxy, entrypoint of all the HTTP requests)
* http://companies-and-products.localhost (Web application)
* http://api.companies-and-products.localhost (API)
* http://phpmyadmin.companies-and-products.localhost (phpMyAdmin, a web interface for your MySQL database)
* http://minio.companies-and-products.localhost (S3 compatible storage)
* http://mailhog.companies-and-products.loclahost (Emails catcher)

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

You should ignore globally some folders like those generated by your IDE and Vagrant.

If not already done, you need to tell Git where to find your global `.gitignore` file.

For instance, on Linux/MacOS/Windows git bash:

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

* [Webapp](src/webapp/README.md)
* [API](src/api/README.md)
* [Housekeeping - or how to keep everything up-to-date](HOUSEKEEPING.md)

### How to stop the stack?

As simple as the `make up` command, run `make down` to stop the entire Docker Compose stack.

If you're a Vagrant user, you may also stop the virtual machine with `vagrant halt`.

If you're not going to work on the project for while, you may also destroy 
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
