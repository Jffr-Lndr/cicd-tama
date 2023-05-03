docker-down:
	cd .build && docker-compose down

docker-build:
	cd .build && docker-compose up --build -d

docker-init-migration:
	cd .build && docker-compose exec php-apache-environment php MigrationInitiale.php
	cd .build && docker-compose exec php-apache-environment php CallSql.php