# log clear for laravel artisan
  A laravel artisan log clear. 
  
## How does it work?

This package expects that you are using Laravel 5.1 or above.
You will need to import the `uipps/laravel-log-clear` package via composer:

### Configuration

```shell
composer require uipps/laravel-log-clear --dev
```

### Usage

Assuming you have already configured your database, you are now all set to go.

- Let's scaffold some of your models from your default connection.

```shell
php artisan log:clear
```
