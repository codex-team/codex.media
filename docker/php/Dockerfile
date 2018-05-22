FROM php:5.6-fpm

MAINTAINER CodeX <github.com/codex-team>

RUN apt-get update
RUN apt-get install -y \
    git \
    zip \
    libz-dev \
    libjpeg-dev libpng-dev libfreetype6-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Install memcache and redis php-exts
RUN pecl install memcache
RUN pecl install redis
RUN docker-php-ext-enable \
    redis \
    memcache

# Configure GD library
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

# Enable some php-exts
RUN docker-php-ext-install \
    mysql mysqli \
    gettext \
    gd

# Set the locale
RUN apt-get -qq update && apt-get -qqy install locales
RUN sed -i -e 's/# ru_RU ISO-8859-5/ru_RU ISO-8859-5/' /etc/locale.gen && \
    sed -i -e 's/# ru_RU.UTF-8 UTF-8/ru_RU.UTF-8 UTF-8/' /etc/locale.gen && \
    sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
    locale-gen && \
    update-locale LANG=ru_RU.UTF-8 && \
    echo "LANGUAGE=ru_RU.UTF-8" >> /etc/default/locale && \
    echo "LC_ALL=ru_RU.UTF-8" >> /etc/default/locale

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime
RUN "date"

WORKDIR /var/www/codex.media
