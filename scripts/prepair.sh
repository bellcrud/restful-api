#!/bin/bash
export composer="/usr/local/bin/composer"
export PATH=/usr/local/bin/composer:$PATH
source ~/.bashrc
source ~/.bash_profile
cd /var/www/html/okura-restful-api/
composer update