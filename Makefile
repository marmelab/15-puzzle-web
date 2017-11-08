.PHONY: help install run stop test logs

help: ## Print all commands (default)
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## Run composer install within the host
	docker-compose run --no-deps --rm \
		php bash -ci './bin/composer install'

database-install: ## Create and set the database
	docker-compose run --no-deps --rm \
		php bash -ci 'rm -f var/data.db && ./bin/console doctrine:database:create && ./bin/console doctrine:schema:create && chmod 666 var/data.db'

install: ## Build the dockers
	docker-compose build
	$(MAKE) composer-install
	$(MAKE) database-install

run: ## Run the 15-puzzle game
	docker-compose up -d

stop: ## Stop 15-puzzle game
	docker-compose down

test: ## Run all tests
	docker build -t php docker/php
	$(MAKE) composer-install
	docker run -it --rm -v "${PWD}/app:/app" php bin/phpunit

connect-php: ## Open bash session in php container as host user
	docker-compose run --no-deps --rm php bash

logs: ## Display the logs of all containers
	docker-compose logs
