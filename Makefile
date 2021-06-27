init: up composer-install

up:
	docker-compose up -d
	docker-compose ps

down:
	docker-compose down

composer-install:
	docker-compose run --rm php-fpm composer install
	docker-compose run --rm php-fpm composer dumpautoload --optimize

build:
	docker-compose build --no-cache
