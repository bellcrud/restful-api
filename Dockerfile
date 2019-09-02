FROM tleokura/alpine-php-apache

# timezoneの設定
RUN apk --update add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo "Asia/Tokyo" > /etc/timezone

# 文字コードの設定
ENV LANG ja_JP.UTF-8

# apacheとphpを設定
COPY docker/app/httpd.conf /etc/apache2/
COPY docker/app/php.ini $PHP_INI_DIR/conf.d/
WORKDIR /var/www/html
RUN mkdir okura-restful-api
COPY ./ /var/www/html/okura-restful-api/
WORKDIR /var/www/html/okura-restful-api/

RUN composer install

## git管理対象外のディレクトであるstorage及びstorage配下のディレクトリを作成
RUN mkdir -p /var/www/html/okura-restful-api/storage/framework/cache /var/www/html/okura-restful-api/storage/framework/sessions /var/www/html/okura-restful-api/storage/framework/views
RUN chmod -R a+w /var/www/html/
RUN chmod -R a+w /var/www/html/okura-restful-api/storage/framework/ /var/www/html/okura-restful-api/ bootstrap/cache


# apache起動
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]