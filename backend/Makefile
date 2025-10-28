test-cov:
	./vendor/bin/phpunit --testdox --coverage-html coverage

test:
	./vendor/bin/phpunit

run-seed:
	php ./src/run-seed.php

deploy-dev:
	sh devdeploy.sh

deploy-prod:
	sh proddeploy.sh

migration-gen:
	./vendor/bin/doctrine-migrations generate

migration-gen-auto:
	./vendor/bin/doctrine-migrations diff

migration-test:
	./vendor/bin/doctrine-migrations migrate --dry-run

migration-run:
	./vendor/bin/doctrine-migrations migrate --all-or-nothing

migration-down:
	./vendor/bin/doctrine-migrations migrate first

migration-prev:
	./vendor/bin/doctrine-migrations migrate prev

migration-is-up-to-date:
	./vendor/bin/doctrine-migrations migrations:up-to-date

migration-status:
	./vendor/bin/doctrine-migrations status

migration-current:
	./vendor/bin/doctrine-migrations current

migration-sync-metadata-storage:
	./vendor/bin/doctrine-migrations sync-metadata-storage

migration-diff:
	./vendor/bin/doctrine-migrations diff

gen-db: 
	php bin/doctrine orm:schema-tool:create