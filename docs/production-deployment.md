# Production Deployment Guide

To install CodeX Media on production server you need to set up the environment. 

This guide was tested on the newly installed `Ubuntu 16.04`.

## 1. Update list of packages and install updates if it is required.

```shell
apt update
apt upgrade -y
```

## 2. Set up locales

```shell

```


## 3. Install necessary packages

```shell
apt install curl wget nano git ca-certificates zip unzip nginx
```

## 4. Install PHP

```shell
apt install php5.6-fpm -y
```

apt install php5.6-mysql php5.6-redis php-memcache php5.6-curl php5.6-gd php5.6-dom

## 5. Install MySQL, Redis and Memcached

## 6. Install Composer

```shell
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```
