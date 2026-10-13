FROM php:5.6-apache

RUN echo "deb [trusted=yes] http://archive.debian.org/debian stretch main contrib non-free" > /etc/apt/sources.list \
    && echo "deb [trusted=yes] http://archive.debian.org/debian-security stretch/updates main contrib non-free" >> /etc/apt/sources.list \
    && apt-get update && apt-get install -y \
        libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libcurl4-openssl-dev libxml2-dev locales \
    && echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && locale-gen \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install mysqli pdo pdo_mysql curl gd intl zip mbstring xml \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

ENV LANG=fr_FR.UTF-8
ENV TZ=Europe/Paris

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY . /var/www/html/

RUN mkdir -p /var/www/html/QRCodes \
    && chown -R www-data:www-data /var/www/html \
    && chmod 755 /var/www/html/QRCodes
RUN chown -R www-data:www-data /var/www/html
