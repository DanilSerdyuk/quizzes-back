up:
	- docker-compose up -d
build:
	- docker-compose up --build -d
down:
	- docker-compose down
local-up:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d
local-build:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml up --build -d
local-down:
	- docker-compose -f docker-compose.yml -f docker-compose.local.yml down
queue:
	- docker-compose exec -T app php artisan queue:work --tries=3
composer:
	- docker-compose exec -T app composer install
migrate:
	- docker-compose exec -T app php artisan migrate:fresh --seed
key:
	- docker-compose exec -T app php artisan key:generate
jwt:
	- docker-compose exec -T app php artisan jwt:secret
init:
	- make composer && make migrate && make key && make jwt && make queue
