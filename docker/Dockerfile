FROM tleokura/alpine-php-apache

COPY okura-restful-api/ /var/www/html/okura-restful-api
COPY okura-restful-api/docker/app/httpd.conf /etc/apache2/httpd.conf
COPY okura-restful-api/docker/app/php.ini /usr/local/lib/php.ini

WORKDIR var/www/html/okura-restful-api
RUN composer install
RUN mkdir -p ./storage/framework/cache ./storage/framework/sessions ./storage/framework/views
RUN chmod -R a+w /var/www/html/
RUN chmod -R a+w ./storage/framework/ ./bootstrap/cache

CMD /usr/sbin/httpd -DFOREGROUND