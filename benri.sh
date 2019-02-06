#!/bin/sh

#set -e

composer dump-autoload

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan ide-helper:generate
php artisan ide-helper:meta
php artisan ide-helper:eloquent

php artisan key:generate
php artisan migrate

php artisan serve