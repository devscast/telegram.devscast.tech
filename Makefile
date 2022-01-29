.PHONY: help
help: ## affiche cet aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: serve
serve: vendor/autoload.php ## lance le projet en local
	php -S 0.0.0.0:8091 -t public

.PHONY: lint
lint: vendor/autoload.php ## affiche les erreurs de formatage/style de code
	php vendor/bin/phpcs -s
	php bin/console lint:yaml config --parse-tags
	php vendor/bin/phpstan

.PHONY: refactoring
refactoring: vendor/autoload.php ## propostion de refactoring avec rector
	php vendor/bin/rector --dry-run

.PHONY: lint-fix
lint-fix: vendor/autoload.php ## corrige les erreurs de formatage de code
	php vendor/bin/phpcbf

vendor/autoload.php: composer.lock # installe les dépendances PHP
	composer install
	touch vendor/autoload.php
