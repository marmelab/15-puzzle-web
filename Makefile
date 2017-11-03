.PHONY: help run stop test

help: ## Print all commands (default)
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Build the dockers
	docker-compose build

run: ## Run the 15-puzzle game
	docker-compose up -d

stop: ## Stop 15-puzzle game
	docker-compose down

test: ## Run all tests
	docker build -t php docker/php
	docker run -it --rm -v "${PWD}/app:/app" php phpunit -v
