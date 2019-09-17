FROM tleokura/alpine-php-apache

# timezoneの設定
RUN apk --update add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo "Asia/Tokyo" > /etc/timezone

# 文字コードの設定
ENV LANG ja_JP.UTF-8