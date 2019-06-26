#!/bin/bash
export composer="/home/ec2-user/composer"
export PATH=/home/ec2-user/composer:$PATH
source ~/.bashrc
source ~/.bash_profile
cd /var/www/html/okura-restful-api/
composer update