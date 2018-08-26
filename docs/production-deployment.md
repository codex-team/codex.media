# Production Deployment Guide

To install CodeX Media on production server you need to set up the environment. 

This guide was tested on the newly installed `Ubuntu 16.04`.

## Update list of packages and install updates if it is required.

This step is optional but recommended.

```shell
apt update
apt upgrade -y
```

## Set up locales

To avoid «Setting locale failed» error add the following lines to `/etc/default/locale` file.

```shell
LC_CTYPE="en_US.UTF-8"
LC_ALL="en_US.UTF-8"
LANG="en_US.UTF-8"
LANGUAGE="en_US:en"
```

Then restart a shell-session. 

## Install necessary packages

You can skip this step and install packages only if 'package is missing' error will be throwen. 

```shell
apt install curl \
            wget \
            software-properties-common \ 
            git \
            unzip
            // ca-certificates
```

## Install PHP

Add a ppa with PHP 5.6

```shell
add-apt-repository ppa:ondrej/php
```

Update list of repositories and install PHP

```shell
apt update
apt install php5.6-fpm -y
```

Also you need to install some PHP-extensions to run CodeX Media.

```shell
apt install php5.6-mysql \
            php5.6-redis \
            php-memcache \
            php5.6-curl \
            php5.6-gd \
            php5.6-dom
```

## Install MySQL, Redis and Memcached

```shell
apt install mysql-server \
            redis-server \
            memcached
```

While installing MySQL you will be asked for a MySQL's root password. Do not forget it!

## Install Composer

It is a packages manager for external PHP libraries.

```shell
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

## Install Nginx server

```shell
apt install nginx 
```

If you try to open your site you'll see welcome page from nginx.

![](https://capella.pics/c2272ad5-c4f5-4592-a9db-ef1d34cf9cab.jpg/cover/eff2f5)

## Clone Media sources

Go to directory for your sites.

```shell
cd /var/www
```

Clone sources to `codex-media.com` directory. You can use any name for it.

```shell
git clone https://github.com/codex-team/codex.media codex-media.com
```

## Nginx-config for Media

Create a configuration file `codex-media.com` (enter your domain) for your site in `/etc/nginx/sites-available` directory.

```shell
nano /etc/nginx/sites-available/codex-media.com
```

Check out [sample config](../www/tools/nginx-media.conf) in project's `tools` directory. Each settings string in this file has a comment.

Enter your values in the following params:

- `server_name` - domain for this site  
- `root` - path to this project's `www` directory
- `fastcgi_pass` - php proxy processor (host:port or socket-file)

Then make a symbolic link in the directory `/etc/nginx/sites-enabled` for this config to enable it. 

```shell
ln -s /etc/nginx/sites-available/codex-media.com /etc/nginx/sites-enabled
```

Reload nginx and open your site by domain name.

```shell
service nginx reload
```

Try to open site again. You will see a `missing file` error from Media.

![](https://capella.pics/ec4e2f48-d071-43ee-922d-88fe26a6698e.jpg)

## Install phpMyAdmin

You can use PMA as webui for MySQL.

Full [Guide](https://gist.github.com/talyguryn/902c0703aa036c61c8b68b64ca90ee3c).

## Setting up Media

These steps are almost the same as for a [local deployment guide](./deployment.md) with Docker.

Go to Media's sources directory.

```shell
cd /var/www/codex-media.com
```

### Create `.env` config file in a subfolder `www` and fill up params.
    
You can copy env file skeleton from sample file [`www/.env.sample`](../www/.env.sample).

```shell
cd www
cp .env.sample .env
```

This site uses [Hawk](https://hawk.so) as error catching service. You can create an account and add a new project.

### Run composer in PHP container and install all dependencies.
    
```shell
composer install
```

### Setting up directories for uploaded files, cache and logs

Reload site's page. You will see this error.

![](https://capella.pics/125aa83b-c958-4ee2-b4b8-3cd4f568c304.jpg)

They will be placed here:
    
```
codex.media (project's root directory)
  |- docker
  |- docs
  |- www ( <- You are here )
  |   |- application
  |   |   |- cache
  |   |   |- logs
  |   |   ...
  |   ...
  |   |- upload
  |   ...
  ...
```

Create a directory for uploaded files e.g. editor's images and users' profile pictures.

```shell
chmod -R 777 upload
```

Then you need to go to the `application` directory. Create these two directories and set full access permissions for every user.

```shell
cd application
mkdir cache logs
chmod 777 cache logs
```

### Setting up config files

Go to `www/application/config` and duplicate sample files without `.sample` in their names.

```shell
cd config
```

#### cache.php

If you have missed config for cache, you will see this error. 

![](https://capella.pics/d3f17cda-9e8b-4f32-9424-25b9e4ee410b.jpg)

#### database.php

Missing database config. 

![](https://capella.pics/97971bc3-337d-4987-8b5f-ad5e1c47b140.jpg)

Wrong credentials in database config.

![](https://capella.pics/52891118-3aa5-484a-b3ca-a70d777a9879.jpg)

Create a new database (ex. `codex-media`) with collation `utf8_general_ci`. Enter hostname, database name, username and password to config file in the following fields.

![](https://capella.pics/1f0a06c3-f507-4c1a-84ce-08dfd6b05111.jpg)

```
'hostname'   => '127.0.0.1',
'database'   => 'codex-media',
'username'   => 'root',
'password'   => 'root_password_for_mysql',
```

#### email.php

CodeX Media uses a [Sendgrid](https://sendgrid.com) as a mailing service.

1. Register a new account and choose free plan (40000 emails per month)

2. Open [Setup Guide - Integrate - Web API - PHP](https://app.sendgrid.com/guide/integrate/langs/php)

![](assets/create-a-new-key-for-sendgrid.png)

Enter a name for a new key then press «Create Key»

![](assets/sendgrid-test-key.png)

Copy this key and paste to `email.php` config file.

You can fill up `senderName` and `senderEmail`.

#### communities.php (optional feature)

If you want to enable auto posting news to VK community page, then follow [this guide](https://github.com/codex-team/codex.edu/issues/119#issuecomment-296349880). You need to have rights to post anything to the community's wall as that community.

Shortly you need to register a new Standalone application, give it rights to post to walls from your name and get an `access_token`. Then you need to get `group_id` — a page's identifier (integer number below zero).

Then you'll be able to enable or disable posting news to community's wall.

![](assets/editor-with-vk-post-button.png)

#### telegram-notification.php (optional feature)

Somewhere in the project we use notifications from [@codex_bot](https://ifmo.su/bot). You can get a notifications link from it typing a command `/notify_start`. You have to add [@codex_bot](https://t.me/codex_bot) in Telegram for that and start conversation.

### Build front-end scripts

![](https://capella.pics/941326ef-a877-45ae-bc86-94e131f32a80.jpg)

#### Install Node.js

You can use [this guide](https://gist.github.com/talyguryn/769a6133b3219ab47c2fe8d961fbf33e) to install Node.js and NPM.

#### Build front-end from sources

Go to `www` directory in the project.

```shell
cd ../..
```

Install necessary Node.js packages.

```shell
npm install
```  

Run `build` script.

```shell
npm run build
```

![](https://capella.pics/7e802bbd-43c1-470c-918e-ebbb7a9291dd.jpg)