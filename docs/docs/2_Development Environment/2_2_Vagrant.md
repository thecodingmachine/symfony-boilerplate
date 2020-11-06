---
title: Vagrant
slug: /development-environment/vagrant
---

On macOS and Windows, Docker currently has substantial performance issues.

[Vagrant](https://www.vagrantup.com/) will help you to have an almost Linux-like experience regarding performances.

## Usage

* 📦 `make vagrant`: creates the *Vagrantfile*.
* 🚀 `vagrant up`: installs and starts the virtual machine.
* 🚇 `vagrant ssh`: connects to the virtual machine.
* 🚦 `vagrant halt`: stops the virtual machine.
* 💣 `vagrant destroy`: destroys the virtual machine.

:::note

📣 On Windows, we strongly advise using a Linux-like terminal to run the `make vagrant` command. 

:::

:::note

📣 With `vagrant ssh`, you may run the *Makefile*'s instructions like a Linux user.

:::

## Configuration

Vagrant's configuration consists of three files:

* *Makefile*
* *scripts/create-vagrantfile-from-template.sh*
* *Vagrantfile* (and its template *Vagrantfile.template*)

The *Makefile* contains the variables `VAGRANT_BOX`, `VAGRANT_PROJECT_NAME`, `VAGRANT_MEMORY`, `VAGRANT_CPUS`, 
and `VAGRANT_DOCKER_COMPOSE_VERSION`.

The command `make vagrant` reads these variables and uses them as arguments 
when calling the *create-vagrantfile-from-template.sh* script.

The later replaces placeholders from the *Vagrantfile.template* by the variables' values and creates a new *Vagrantfile*.

:::note

📣 You should never commit the *Vagrantfile*.

:::