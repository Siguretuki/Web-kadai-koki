FROM php:8.3-fpm-alpine AS php

RUN apk add git
RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis
RUN docker-php-ext-install redis

RUN docker-php-ext-install pdo_mysql

RUN install -o www-data -g www-data -d /var/www/upload/image/

RUN echo -e "post_max_size = 5M\nuplaod_max_filesize = 5M" >> ${PHP_INI_DIR}/php.ini
