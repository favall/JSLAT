FROM php:5.6-apache

RUN sed -i 's/deb.debian.org/archive.debian.org/g; s/security.debian.org/archive.debian.org/g; s|/debian-security|/debian|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && apt-get update && apt-get install -y \
        libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev locales \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && locale-gen \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install mysqli pdo pdo_mysql curl gd intl zip mbstring xml \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

ENV LANG=fr_FR.UTF-8
ENV TZ=Europe/Paris

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html