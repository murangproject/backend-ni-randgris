#!/bin/sh

# update application cache
php artisan optimize
php artisan migrate --force
php artisan migrate:fresh --seed -n --force

# start the application

php-fpm -D &&  nginx -g "daemon off;"
