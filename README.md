# POC Symfony 6.2 with Messenger component
## Prerequisites

* The PHP version must be greater than or equal to PHP 8.2
* The SQLite 3 extension must be enabled
* The JSON extension must be enabled
* The Ctype extension must be enabled
* The date.timezone parameter must be defined in php.ini

More information on [symfony website](https://symfony.com/doc/6.2/reference/requirements.html).

## Features developed
Messenger showcase.

**Current study:** Please review, edit and commit them: these files are yours.

symfony/messenger  instructions:

* You're ready to use the Messenger component. You can define your own message buses
  or start using the default one right now by injecting the message_bus service
  or type-hinting Symfony\Component\Messenger\MessageBusInterface in your code.

* To send messages to a transport and handle them asynchronously:

	1. Update the MESSENGER_TRANSPORT_DSN env var in .env if needed
	   and framework.messenger.transports.async in config/packages/messenger.yaml;
	2. (if using Doctrine) Generate a Doctrine migration bin/console doctrine:migration:diff
	   and execute it bin/console doctrine:migration:migrate
	3. Route your message classes to the async transport in config/packages/messenger.yaml.

* Read the documentation at https://symfony.com/doc/current/messenger.html

## Installation
Command lines:

```bash
# clone current repot
composer install

# (optional) Copy and edit configuration values ".env.local"

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n

# Optional
php bin/console doctrine:fixtures:load -n
```

For the asset symlink install, launch a terminal on administrator in windows environment.

## Usage
Just execute this command to run the built-in web server _(require [symfony installer](https://symfony.com/download))_ and access the application in your browser at <http://localhost:8000>:

```bash
# Dev env
symfony server:start

# Test env
APP_ENV=test php -d variables_order=EGPCS -S 127.0.0.1:8000 -t public/
```

Alternatively, you can [configure a web server](https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) like Nginx or Apache to run the application.

Your commit is checked by several dev tools (like phpstan, php cs fixer...). These tools were managed by [Grumphp](https://github.com/phpro/grumphp), you can edit configuration on file [grumphp.yml](./grumphp.yml) or check manually with the command: `./vendor/bin/grumphp run`.