# Load env file (mysql user)
ifneq ("$(wildcard .env)","")
	include .env
	export $(shell sed 's/=.*//' .env)
endif

# Init var
DOCKER_COMPOSE?=docker-compose
EXEC?=$(DOCKER_COMPOSE) exec -u root app
EXEC_SF?=$(DOCKER_COMPOSE) exec -u www-data app
DIR_PROJECT=/var/www/project/poc-messenger
CONSOLE=$(EXEC_SF) symfony console
DOCKER_COMPOSE_OVERRIDE ?= dev
SCRIPT?=.docker/script

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(firstword $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## Project setup
##---------------------------------------------------------------------------
up: up-ci  ## Start project with docker-compose + Dev env

stop: stop-ci 							## Stop docker containers

restart: stop-ci up-ci										## Restart docker containers

install: build up-ci composer-install perm  ## Create and start docker containers

uninstall: stop                                              ## Remove docker containers
	@$(DOCKER_COMPOSE) rm -vf

reset: uninstall install                                     ## Remove and re-create docker containers (delete all data)
refresh:                    			                     ## Remove and re-create docker containers (WITHOUT delete all data)
	@$(DOCKER_COMPOSE) down
	@make up-ci

clear-cache: perm
	@$(CONSOLE) cache:clear --no-warmup
	@$(CONSOLE) cache:warmup
	@$(CONSOLE) cache:clear --no-warmup
	@$(CONSOLE) cache:warnot

c\:c: clear-cache

clear: perm                                                  ## Remove all the cache, the logs, the sessions and the built assets
	@$(EXEC_SF) rm -rf var/cache/* var/log/* public/build
	@$(EXEC_SF) rm -f var/.php_cs.cache
	@$(EXEC_SF) rm -rf var/cache/* var/log/* public/build
	@$(EXEC_SF) rm -f var/.php_cs.cache

clean: clear                                                 ## Clear and remove dependencies
	@$(EXEC_SF) rm -rf vendor


##
## Database
##---------------------------------------------------------------------------
db-install: ## Drop and Install database + schema + fixtures
	@$(CONSOLE) doctrine:database:drop --force --if-exists
	@$(CONSOLE) doctrine:database:create
	@$(CONSOLE) doctrine:migrations:migrate -n
	@$(CONSOLE) doctrine:fixtures:load -n
	@$(CONSOLE) cache:warmup
	@make perm

##
## Tools
##---------------------------------------------------------------------------
composer-install:  ## Composer install
	@echo "[Symfony] Composer install"
	$(call composer,$(EXEC),install,$(DIR_PROJECT))

composer:  ## Composer update. You can specified package, example: `make composer CMD="update twig/twig"`
	$(call composer,$(EXEC),$(CMD),$(DIR_PROJECT))

sf-cmd:  ## Symfony Command, example: `make sf-cmd CMD="debug:container"`
	@$(CONSOLE) $(CMD)

sf-route:  ## Api routes
	@$(CONSOLE) debug:route

messenger: ## [Optional] Consume messenger message, you can add verbose mode: CMD="-v". This command is not required, supervisor send message automatically.
	@$(CONSOLE) messenger:consume async $(CMD)

shell:  ## Run Api container in interactive mode
	@$(EXEC) /bin/bash



# Internal rules
up-ci:
	@$(DOCKER_COMPOSE) up -d

stop-ci:
	@$(DOCKER_COMPOSE) stop

build:
	@$(DOCKER_COMPOSE) pull --ignore-pull-failures
	@$(DOCKER_COMPOSE) build --force-rm

perm:
	@bash $(SCRIPT)/perm.sh "$(EXEC)" "$(DIR_PROJECT)"

define echo_text
	echo -e '\e[1;$(2)m$(1)\e[0m'
endef

define composer
	@$(1) php -d memory_limit=1500M /usr/local/bin/composer $(2) -n --working-dir=$(3)
endef
