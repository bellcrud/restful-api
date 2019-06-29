#!/bin/bash
cd /var/www/html/okura-restful-api/
sudo php artisan cache:clear
sudo php artisan config:clear
sudo php artisan view:clear
/usr/local/bin/composer update