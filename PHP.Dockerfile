FROM php:8.1-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update -y && apt-get install -y zlib1g-dev libpng-dev libfreetype6-dev

RUN docker-php-ext-configure gd --enable-gd --with-freetype

RUN docker-php-ext-install gd

# debug
RUN docker-php-ext-install pdo pdo_mysql \
    && pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"