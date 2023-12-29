# POC Symfony 6.4 with Messenger component + Docker (LAMP with Supervisor, MailDev, RabbitMQ)
Messenger provides a message bus with the ability to send messages and then handle them immediately in your application or send them through transports (e.g. queues) to be handled later. To learn more deeply about it, read the [Messenger component docs](https://symfony.com/doc/6.2/messenger.htmlcomponents/messenger.html).

The messages are automatically consumed (send) by a worker from [supervisor](http://supervisord.org). Without it, you need to launch manually the command `make messenger` (or `php bin/console messenger:consume async` in local symfony server).


## Prerequisites

* Docker v24+ & Docker compose v2
* `Make` command. On linux, install with `sudo apt install build-essential`. On Windows, [see here](https://stackoverflow.com/questions/32127524/how-to-install-and-use-make-in-windows/54086635).
* These ports must be available for docker: `8000, 8080, 1025, 1080, 15672` _(you can change it on `docker-compose.override.yml` file after install)_.

More information on [symfony website](https://symfony.com/doc/6.2/reference/requirements.html).


## Installation
Command lines:

```bash
make install db-install

# (optional) Copy and edit configuration values ".env.local"
```


## Usage
Use docker for execute the built-in web server and access the application in your browser at <http://localhost:8000>:

```bash
make up

# Launch Messages service (optional: only needed if supervisor is not launch)
# make messenger

# For stop services
make stop
```

* For look at emails send by the smtp service, look at this url <http://localhost:1080> (maildev).
* For database management, you can look at phpmyadmin: <http://localhost:8080>.
* You can edit the var `COMPOSE_FILE` in [.env](.env) for add / remove containers from .docker/compose folder.

Debug commands:

```shell
make messenger CMD="-vv"

# Retry failed messages several times (3 attempts)
make sf-cmd CMD="messenger:failed:show"
make sf-cmd CMD="messenger:failed:retry"
```

Enjoy!