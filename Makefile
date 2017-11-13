.PHONY: help install run stop test logs

help: ## Print all commands (default)
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## Run composer install within the host
	docker-compose run --no-deps --rm \
		service_php bash -ci './bin/composer install'

database-install: ## Create and setup the database
	docker-compose up -d service_postgres
	docker-compose run --no-deps --rm service_php \
		bash -ci './bin/console doctrine:database:create --if-not-exists && ./bin/console doctrine:schema:update --force'
	docker-compose down

install: ## Build the dockers
	docker-compose build
	$(MAKE) composer-install
	$(MAKE) database-install

run: ## Run the 15-puzzle game
	docker-compose up -d

start: ## Start 15-puzzle game (alias for `run`)
	$(MAKE) run

stop: ## Stop 15-puzzle game
	docker-compose down

test: ## Run all tests
	docker build -t service_php docker/php
	$(MAKE) composer-install
	docker run -it --rm -v "${PWD}/app:/app" service_php bin/phpunit

connect-php: ## Open bash session in php container as host user
	docker-compose run --no-deps --rm service_php bash

connect-db: ## Open database container
	docker-compose run service_postgres psql -h service_postgres -U docker

logs: ## Display the logs of all containers
	docker-compose logs
