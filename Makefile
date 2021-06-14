.PHONY: help
help: ## affiche cet aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: migrate
migrate: vendor/autoload.php ## crée le schema de la base de données
	php bin/console doctrine:migrations:migrate -v

.PHONY: create-migration
create-migration: vendor/autoload.php ## crée une migration (code)
	php bin/console make:migration -v

.PHONY: create-database
create-database: vendor/autoload.php ## crée la base de données
	php bin/console doctrine:database:create -v

.PHONY: create-admin
create-admin: vendor/autoload.php ## crée un admin => admin@admin.com / 000000
	php bin/console app:create-admin --email admin@admin.com -p 000000 -v

.PHONY: drop-database
drop-database: vendor/autoload.php ## supprime la base de données
	php bin/console doctrine:database:drop --force -v

.PHONY: import-database
import-database: vendor/autoload.php ## importe l'ancienne base de donnée
	php bin/console doctrine:database:import migrations/database.sql -v

.PHONY: routes
routes: vendor/autoload.php ## affiche toutes les routes de l'application
	php bin/console debug:router -v

.PHONY: serve
serve: vendor/autoload.php ## lance le projet en local
	php -S 0.0.0.0:8091 -t public

.PHONY: lint
lint: vendor/autoload.php ## affiche les erreurs de formatage de code
	php vendor/bin/phpcs -s

.PHONY: lint-fix
lint-fix: vendor/autoload.php ## corrige les erreurs de formatage de code
	php vendor/bin/phpcbf

.PHONY: clear
clear: vendor/autoload.php ## efface le cache de l'application
	php bin/console cache:clear -v

vendor/autoload.php: composer.lock # installe les dépendances PHP
	composer install
	touch vendor/autoload.php
