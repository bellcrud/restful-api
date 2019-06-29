#!/bin/bash
sudo php artisan cache:clear
php artisan config:clear
php artisan view:clear

cd /var/www/html/okura-restful-api/
/usr/local/bin/composer update