<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirements (Local Environment)

- PHP 8.1.
- MySQL 8.0
- Composer 2.2
- NPM latest

## Installation (Local Environment)

- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan config:cache
- php artisan migrate
- php artisan db:seed
- npm install
- npm run build

## Testing

- php artisan config:clear
- php artisan test

## Starting Application (Local Environment)

- php artisan serve

## URL for the Application

- http://localhost:8000

## Admin User

- Email : admin@example.com
- Password : password
