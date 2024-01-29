.DEFAULT_GOAL := help
BASE_DIR 	  := $(shell pwd | xargs basename)

define launch_browser
    case `uname` in \
        Darwin) open $(1) ;; \
        Linux) xdg-open $(1) ;; \
        CYGWIN*|MINGW32*|MSYS*) start $(1) ;; \
    esac
endef

# MariaDB version 10.2 is equivalent to MySQL 5.7, 
# mysql:5.7 does not have a version available for the linux/arm64/v8 platform
# image: mysql:5.7
# image: mariadb:10.2
DB_IMAGE      := $(shell if [ "$(shell uname -s)" = "Darwin" ]; then echo "mariadb:10.2"; else echo "mysql:5.7"; fi)

env:
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@if [ ! -f ./app/.env ]; then cp ./app/.env.example ./app/.env; fi

fresh-build: env down
	@echo "Using $(DB_IMAGE) as database image"
	@DB_IMAGE=$(DB_IMAGE) docker compose up --build -d

fresh: fresh-build seed-subscribers open-vue-app ## Make Fresh Docker Setup Based on OS Environment

start: down ## Start Docker Environment
	@echo "Using $(DB_IMAGE) as database image"
	@DB_IMAGE=$(DB_IMAGE) docker compose up -d

ps: ## List Docker Containers
	@DB_IMAGE=$(DB_IMAGE) docker compose ps

check-vue-ready: ## Check if Vue.js application is ready
	@echo "Checking if Vue.js application is ready..."
	@until curl --silent --output /dev/null http://localhost:8080; do \
		echo "Waiting for Vue.js application..."; \
		sleep 1; \
	done
	@echo "Vue.js application is ready."

open-vue-app: check-vue-ready ## Open Vue.js Application
	@$(call launch_browser,http://localhost:8080)

monitor-php-logs: ## Monitor PHP Logs
	@DB_IMAGE=$(DB_IMAGE) docker compose logs -f php

monitor-db-logs: ## Monitor DB Logs
	@DB_IMAGE=$(DB_IMAGE) docker compose logs -f db

monitor-vue-logs: ## Monitor Vue Logs
	@DB_IMAGE=$(DB_IMAGE) docker compose logs -f vue

down: ## Stop Docker Environment
	@DB_IMAGE=$(DB_IMAGE) docker compose down

ssh-php: ## SSH into PHP Container
	@DB_IMAGE=$(DB_IMAGE) docker compose exec -it php /bin/bash

ssh-mysql: ## SSH into Mysql Container
	@DB_IMAGE=$(DB_IMAGE) docker compose exec -it db /bin/bash

install-composer-dev: ## Install Composer Dev Dependencies
	@DB_IMAGE=$(DB_IMAGE) docker compose exec -it php composer install --dev

generate-test-report: install-composer-dev ## Generate Test Report
	@DB_IMAGE=$(DB_IMAGE) docker compose exec -it php vendor/bin/phpunit --coverage-html ./coverage
	@echo "Test report generated at ./src/coverage/index.html"

seed-subscribers: install-composer-dev ## Insert fake subscribers into database
	@DB_IMAGE=$(DB_IMAGE) docker compose exec -it php php seeders/SubscriberSeeder.php

cleanup: ## Cleanup Docker Environment including Volumes
	@DB_IMAGE=$(DB_IMAGE) docker compose down -v


############################
# GENERIC HELP
############################
.PHONY: help # Print help screen
help: SHELL := /bin/sh
help:
	@echo
	@echo "\033[1m\033[7m                                                              \033[0m"
	@echo "\033[1m\033[7m                    Mailerlite Subscribers                    \033[0m"
	@echo "\033[1m\033[7m                                                              \033[0m"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-25s\033[0m %s\n", $$1, $$2}'