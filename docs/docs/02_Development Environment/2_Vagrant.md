---
title: Vagrant
slug: /development-environment/vagrant
---

On macOS and Windows, Docker currently has substantial performance issues.

[Vagrant](https://www.vagrantup.com/) will help you to have an almost Linux-like experience regarding performances.

## Usage

* 📦&nbsp;&nbsp;`make vagrant` - creates the *Vagrantfile*.
* 🚀&nbsp;&nbsp;`vagrant up` - installs and starts the virtual machine.
* 🚇&nbsp;&nbsp;`vagrant ssh` - connects to the virtual machine.
* 🚦&nbsp;&nbsp;`vagrant halt` - stops the virtual machine.
* 💣&nbsp;&nbsp;`vagrant destroy` - destroys the virtual machine.

:::note

📣&nbsp;&nbsp;On Windows, consider using a Linux-like terminal to run the `make vagrant` command. 

:::

:::note

📣&nbsp;&nbsp;With `vagrant ssh`, you may run the *Makefile*'s instructions like a Linux user.

:::

## Configuration

In the *.env* file contains the following variables for Vagrant:

* `VAGRANT_BOX` - the VM to use.
* `VAGRANT_PROJECT_NAME` - the project name: only use alphanumeric characters (no spaces, distinguish words with `_` or `-`).
* `VAGRANT_MEMORY` - the memory to allocate to the VM.
* `VAGRANT_CPUS` - the CPUs to allocate to the VM.
* `VAGRANT_DOCKER_COMPOSE_VERSION` - the version of Docker Compose to use.

The command `make vagrant` reads these variables and uses them as arguments 
when calling the *scripts/create-vagrantfile-from-template.sh* script.

This script replaces placeholders from the *Vagrantfile.template* by the variables' values and creates a new *Vagrantfile*.

:::note

📣&nbsp;&nbsp;You should never commit the *Vagrantfile*.

:::