FROM docker.io/library/php:8.3-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS \
    && echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php/conf.d/logging.ini

RUN sed -i 's/;pm.status_path = \/status/pm.status_path = \/status/g' /usr/local/etc/php-fpm.d/www.conf
