include .env

.PHONY: up down webapp api consume vagrant

.env: ## Setup .env from dist
	cp .env.dist .env

up: .env ## Start the Docker Compose stack.
	docker-compose up -d

ps: .env ## Status of running containers
	docker-compose ps

top-lazy: .env ## Use lazy docker to show a docker compose interface
	@if ! type lazydocker > /dev/null 2>&1; then \
  		curl https://raw.githubusercontent.com/jesseduffield/lazydocker/master/scripts/install_update_linux.sh | bash; \
  	fi
	lazydocker

down: ## Stop the Docker Compose stack.
	docker-compose down

webapp: ## Run bash in the webapp service.
	docker-compose exec webapp bash

api: ## Run bash in the api service.
	docker-compose exec api bash

consume: ## Consume messages from the queue.
	docker-compose exec api php bin/console messenger:consume async -vv

vagrant: ## Create the Vagrantfile from the template Vagrantfile.template.
	./scripts/create-vagrantfile-from-template.sh \
	$(VAGRANT_BOX) \
	$(VAGRANT_PROJECT_NAME) \
	$(VAGRANT_MEMORY) \
	$(VAGRANT_CPUS) \
	$(VAGRANT_DOCKER_COMPOSE_VERSION)

.PHONY: create-migrate
create-migrate: ## Create a new migration file
	docker-compose exec api bin/console doctrine:database:drop --force
	docker-compose exec api bin/console doctrine:database:create -n
	docker-compose exec api bin/console doctrine:migrations:migrate -n
	docker-compose exec api bin/console make:migration
	docker-compose exec api bin/console doctrine:migrations:migrate -n

.PHONY: graphql-print-schema
graphql-print-schema: ## Display current GraphQL Schema
	docker-compose exec api ./bin/console graphqlite:dump-schema

.PHONY: test-% lint-%
test-api: ## Launch test in api
	docker-compose exec api composer yaml-lint
	docker-compose exec api composer cscheck
	docker-compose exec api composer phpstan
	docker-compose exec api composer pest
	docker-compose exec api composer deptrac

test-webapp: ## Launch test in webapp
	docker-compose exec webapp yarn lint

lint-api: ## Launch linter in api
	docker-compose exec api composer yaml-lint
	docker-compose exec api composer csfix
	docker-compose exec api composer cscheck

lint-webapp: ## Launch linter in webapp
	docker-compose exec webapp yarn lint:fix
	docker-compose exec webapp yarn lint

.PHONY: xdebug-start
xdebug-start: up ## Activate x-debug
	@if ! (docker-compose exec -T api php -m | grep -qi xdebug); then \
  		echo "php-xdebug is not activated"; \
  		echo "Ensure env \`PHP_EXTENSION_XDEBUG=1\`"; \
  		exit 1; \
  	fi;
	@if [ "$(FROM_PHPSTORM)" != "1" ]; then echo "From phpstorm type MAJ+F9"; fi
	@echo "- For browser debug"
	@echo "    - To Enable go onto this url from your browser (do not forget to place yours breakpoints)"
	@echo "      http://$(API_SUBDOMAIN).$(DOMAIN)/ping?XDEBUG_SESSION_START=1"
	@echo "    - To Disable go onto this url from your browser (or just stop listen from phpstorm)"
	@echo "      http://$(API_SUBDOMAIN).$(DOMAIN)/ping?XDEBUG_SESSION_STOP=1"
	@echo "    - Disable Server Side Rendering if required (on you .env, change to \`NUXT_SSR=0\`)"
	@echo "- For cli debug : "
	@echo "    - type on api container : \`XDEBUG_SESSION=1 php ./my-script.php\`"
	@echo "    - type on api container : \`XDEBUG_SESSION=1 ./bin/console app:my-script\`"

.PHONY: help
help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
