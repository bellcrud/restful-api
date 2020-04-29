FROM tleokura/alpine-php-apache

COPY . /var/www/html/okura-restful-api
COPY ./docker/app/httpd.conf /etc/apache2/httpd.conf
COPY ./docker/app/php.ini /usr/local/lib/php.ini
COPY ./docker/app/.env.production /var/www/html/okura-restful-api/.env

WORKDIR var/www/html/okura-restful-api
RUN chmod -R a+w /var/www/html/
RUN composer install
RUN php artisan key:generate
RUN mkdir -p ./storage/framework/cache ./storage/framework/sessions ./storage/framework/views
RUN chmod -R a+w /var/www/html/
RUN chmod -R a+w ./storage/framework/ ./bootstrap/cache

CMD /usr/sbin/httpd -DFOREGROUND
