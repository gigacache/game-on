build-docker:
	docker compose --file='docker-compose.yml' up -d;

build-app:
	make composer-install
	make migrate

composer-update:
	cd app; composer update

composer-install:
	cd app; composer install

remove-composer:
	cd app; rm -rf composer.lock rm -rf vendor

remove-docker:
	docker rm game-on-apache --force
	docker rm game-on-mysql --force
	docker rm game-on-phpmyadmin --force
	docker image rm game-on-server

migrate:
	docker exec -it game-on-apache bin/cake migrations migrate
	docker exec -it game-on-apache bin/cake migrations seed --seed UsersSeed
	docker exec -it game-on-apache bin/cake migrations seed --seed EventsSeed
	docker exec -it game-on-apache bin/cake migrations seed --seed AttendeesSeed
	docker exec -it game-on-apache bin/cake migrations seed --seed BookingsSeed

clear-cache:
	docker exec -it game-on-apache bin/cake cache clear_all

tests:
	docker exec -it game-on-apache composer run tests

static-analysis:
	docker exec -it game-on-apache composer run static-analysis

unit-tests:
	docker exec -it game-on-apache composer run unit-tests

cs-fix: 
	docker exec -it game-on-apache composer run cs-fix

view-routes: 
	docker exec -it game-on-apache bin/cake routes
