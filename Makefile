.PHONY: help install update test test-coverage rector rector-dry lint validate clean all ci

# Colors for better readability
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)

# Default target
.DEFAULT_GOAL := help

help: ## Show this help message
	@echo ''
	@echo 'Usage:'
	@echo '  ${YELLOW}make${RESET} ${GREEN}<target>${RESET}'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  ${YELLOW}%-20s${GREEN}%s${RESET}\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo ''

install: ## Install dependencies
	@echo "${GREEN}Installing dependencies...${RESET}"
	composer install

update: ## Update dependencies
	@echo "${GREEN}Updating dependencies...${RESET}"
	composer update

validate: ## Validate composer.json
	@echo "${GREEN}Validating composer.json...${RESET}"
	composer validate --strict

test: ## Run unit tests
	@echo "${GREEN}Running tests...${RESET}"
	vendor/bin/phpunit

test-coverage: ## Run tests with coverage report
	@echo "${GREEN}Running tests with coverage...${RESET}"
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html coverage --coverage-text
	@echo "${YELLOW}Coverage report generated in coverage/index.html${RESET}"

rector: ## Apply Rector changes
	@echo "${GREEN}Applying Rector changes...${RESET}"
	vendor/bin/rector process

rector-dry: ## Show Rector changes without applying them
	@echo "${GREEN}Showing Rector changes (dry-run)...${RESET}"
	vendor/bin/rector process --dry-run

lint: ## Check PHP syntax
	@echo "${GREEN}Checking PHP syntax...${RESET}"
	@find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n1 php -l

check: validate lint rector-dry ## Run all checks (validate, lint, rector-dry)
	@echo "${GREEN}All checks completed!${RESET}"

clean: ## Clean generated files
	@echo "${GREEN}Cleaning generated files...${RESET}"
	rm -rf coverage .phpunit.cache vendor composer.lock

all: install check test ## Install, check and test

ci: install validate lint rector-dry test ## Run CI pipeline (install, validate, lint, rector-dry, test)
	@echo "${GREEN}CI pipeline completed successfully!${RESET}"
