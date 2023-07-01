# POC Symfony 6.2 with Messenger component + Docker (LAMP, MailDev, RabbitMQ)
## Prerequisites

* The PHP version must be greater than or equal to PHP 8.2
* The SQLite 3 extension must be enabled
* The JSON extension must be enabled
* The Ctype extension must be enabled
* The date.timezone parameter must be defined in php.ini
* Docker & Docker-compose
* `Make` command. On linux, install with `sudo apt install build-essential`. On Windows, [see here](https://stackoverflow.com/questions/32127524/how-to-install-and-use-make-in-windows/54086635).


More information on [symfony website](https://symfony.com/doc/6.2/reference/requirements.html).

## Messenger POC
Messenger provides a message bus with the ability to send messages and then handle them immediately in your application or send them through transports (e.g. queues) to be handled later. To learn more deeply about it, read the [Messenger component docs](https://symfony.com/doc/6.2/messenger.htmlcomponents/messenger.html).


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

# Launch Messages service
make messenger

# For stop services
make stop
```

* For look at emails send by the smtp service, look at this url <http://localhost:1080> (maildev).
* For database management, you can look at phpmyadmin: <http://localhost:8080>.

Debug commands:

```shell
make messenger CMD="-vv"

# Retry failed messages several times (3 attempts)
make sf-cmd CMD="messenger:failed:show"
make sf-cmd CMD="messenger:failed:retry"
```

Enjoy!