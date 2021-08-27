FROM php:7.2-fpm

# Update lists
RUN apt-get update
RUN apt-get install -y \
    git \
    zip \
    libz-dev \
    wget \
    unzip \
    nano \
    libjpeg-dev libpng-dev libfreetype6-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# Install redis extension
RUN pecl install redis
RUN docker-php-ext-enable \
    redis

# Install memcache extension
RUN set -x \
    && cd /tmp \
    && curl -sSL -o php7.zip https://github.com/websupport-sk/pecl-memcache/archive/php7.zip \
    && unzip php7 \
    && cd pecl-memcache-php7 \
    && /usr/local/bin/phpize \
    && ./configure --with-php-config=/usr/local/bin/php-config \
    && make \
    && make install \
    && echo "extension=memcache.so" > /usr/local/etc/php/conf.d/ext-memcache.ini \
    && rm -rf /tmp/pecl-memcache-php7 php7.zip

# Configure GD library
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

# Enable some php-exts
RUN docker-php-ext-install \
    mysqli \
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

# Install phpMyAdmin
RUN wget https://files.phpmyadmin.net/phpMyAdmin/5.0.2/phpMyAdmin-5.0.2-all-languages.zip
RUN unzip phpMyAdmin-5.0.2-all-languages.zip
RUN mv phpMyAdmin-5.0.2-all-languages /usr/share/phpmyadmin
RUN cp /usr/share/phpmyadmin/config.sample.inc.php /usr/share/phpmyadmin/config.inc.php && \
    sed -i "s/\['host'\] = 'localhost'/\['host'\] = 'mysql'/" /usr/share/phpmyadmin/config.inc.php

# Install Node.js and Yarn
RUN curl https://deb.nodesource.com/setup_14.x | bash
RUN curl https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y nodejs yarn

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime

WORKDIR /var/www/codex.media

