#!/bin/bash
sudo rm -r /var/www/html/okura-restful-api/storage/logs/
sudo mkdir /var/www/html/okura-restful-api/storage/logs/
sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
sudo /sbin/mkswap /var/swap.1
sudo /sbin/swapon /var/swap.1
cd /var/www/html/okura-restful-api/
/usr/local/bin/composer update