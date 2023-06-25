# POC Symfony 6.2 with Messenger component
## Prerequisites

* The PHP version must be greater than or equal to PHP 8.2
* The SQLite 3 extension must be enabled
* The JSON extension must be enabled
* The Ctype extension must be enabled
* The date.timezone parameter must be defined in php.ini
* Docker & Docker-compose

More information on [symfony website](https://symfony.com/doc/6.2/reference/requirements.html).

## Messenger POC
Messenger provides a message bus with the ability to send messages and then handle them immediately in your application or send them through transports (e.g. queues) to be handled later. To learn more deeply about it, read the [Messenger component docs](https://symfony.com/doc/6.2/messenger.htmlcomponents/messenger.html).


## Installation
Command lines:

```bash
docker-compose up -d
docker-compose exec app composer install

# (optional) Copy and edit configuration values ".env.local"

docker-compose exec app symfony console doctrine:database:create --if-not-exists
docker-compose exec app symfony console doctrine:migrations:migrate -n
docker-compose exec app symfony console doctrine:fixtures:load -n
sudo chown -R $USER:www-data .
```


## Usage
Just execute this command to run the built-in web server _(require [symfony installer](https://symfony.com/download))_ and access the application in your browser at <http://localhost:8000>:

```bash
docker-compose up -d

# Launch Messages service
docker-compose exec app symfony console messenger:consume async

# For stop services
docker-compose stop
```

* For look at emails send by the smtp service, look at this url <http://localhost:1080> (maildev).
* For database management, you can look at phpmyadmin: <http://localhost:8080>.

Debug commands:

```shell
docker-compose exec app symfony console messenger:consume async -vv

# Retry failed messages several times (3 attempts)
docker-compose exec app symfony console messenger:failed:show
docker-compose exec app symfony console messenger:failed:retry
```

Enjoy!