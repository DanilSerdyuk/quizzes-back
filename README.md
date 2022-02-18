## Copy .env.example or copy it manually: 

```php 
 php -r "file_exists('.env') || copy('.env.example', '.env');"
 ```

### Run via [make](https://askubuntu.com/a/1363822):
1. Run docker:`make build`
   
2. Run init: `make init`

### Run via docker-compose:
1. `docker-compose --build -d`

2. `docker-compose exec -T app composer install`
   
3. `docker-compose exec -T app php artisan migrate:fresh --seed`
   
4. `docker-compose exec -T app php artisan key:generate`
   
5. `docker-compose exec -T app php artisan jwt:secret`

6. `docker-compose exec -T app php artisan queue:work --tries=3`


### Mailtrap credits: 

email: `work.serdyuk@gmail.com`

password: `quizzesdemo`
