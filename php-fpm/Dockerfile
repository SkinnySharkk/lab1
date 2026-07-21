FROM docker.io/library/php:8.3-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql \
    && pecl install redis && docker-php-ext-enable redis \
    && echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php/conf.d/logging.ini
