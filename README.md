<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://projectlogourl.svg" width="400" alt="App logo"></a></p>

## About Project

# Requirements

1. PHP 8.1
2. MySql 8
3. Composer 2.2

# Installation

1. Clone the repository
2. Run `composer install`
3. Run `cp .env.example .env`
4. Run `php artisan key:generate`
5. Run `php artisan jwt:secret`
6. Run `php artisan migrate`
7. Run `php artisan db:seed`
8. Run `php artisan serve`
9. Open [http://localhost:8000](http://localhost:800) in your browser
10. Swagger documentation is available at [http://localhost:8000/documentation](http://localhost:8000/documentation)

# Packages

1. [Laravel Passport](https://laravel.com/docs/passport)
2. [Laravel OpenAPI](https://vyuldashev.github.io/laravel-openapi/)
3. [Swagger UI](https://swagger.io/tools/swagger-ui/)
11. [JWT Auth](https://laravel-jwt-auth.readthedocs.io/en/latest/laravel-installation/)

# OAuth

1. [Vonage SMS sender](https://www.vonage.com/)
