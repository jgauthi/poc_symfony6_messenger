# POC Symfony 6.2 with Messenger component
## Prerequisites

* The PHP version must be greater than or equal to PHP 8.2
* The SQLite 3 extension must be enabled
* The JSON extension must be enabled
* The Ctype extension must be enabled
* The date.timezone parameter must be defined in php.ini

More information on [symfony website](https://symfony.com/doc/6.2/reference/requirements.html).

## Messenger POC
Messenger provides a message bus with the ability to send messages and then handle them immediately in your application or send them through transports (e.g. queues) to be handled later. To learn more deeply about it, read the [Messenger component docs](https://symfony.com/doc/6.2/messenger.htmlcomponents/messenger.html).


## Installation
Command lines:

```bash
# clone current repot
composer install

# (optional) Copy and edit configuration values ".env.local"

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n
```


## Usage
Just execute this command to run the built-in web server _(require [symfony installer](https://symfony.com/download))_ and access the application in your browser at <http://localhost:8000>:

```bash
# Edit "MAILER_DSN" in .env.local with SMTP service (example: mailtrap)

# Install fixtures (only for dev environnement), start server
symfony console doctrine:fixtures:load -n
symfony server:start

# Launch Messages service
symfony console messenger:consume async
```

Debug commands:

```shell
php bin/console messenger:consume async -vv

# Retry failed messages several times (3 attempts)
php bin/console messenger:failed:show
php bin/console messenger:failed:retry
```

Enjoy!