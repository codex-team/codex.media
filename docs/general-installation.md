# General installation

This guide will help you set up and run your own copy of CodeX Media.

Also you can read [VPS setting up example](vps-setting-up-example.md) page.

## Clone and init

Make sure you have installed [Docker](https://docs.docker.com/install/) and [docker-compose](https://docs.docker.com/compose/). 

Clone the repository

```bash
git clone https://github.com/codex-team/codex.media
```

Initialize project

```bash
cd codex.media
./bin/init.sh
```

You may want to know what this script does. Here is a [source code](/bin/init.sh) with comments.

### Links

Site will be run on `8880` port by default in the localhost loop.

![](assets/main-page.png)

[http://localhost:8880/](http://localhost:8880/) — main site

[http://localhost:8880/phpmyadmin](http://localhost:8880/phpmyadmin) — phpMyAdmin panel

### Environment vars — `.env` files

There are two environment files:

- `.env` — vars for docker
- `www/.env` — vars for project

Open them and read comments for correct setting up.

### Config files

Config files are located at `www/application/config/` directory.

### `social.php` — auth methods

You need to create application for auth in these social networks.

#### vk.com

TBA

#### facebook.com 

TBA

#### twitter.com

Go to [https://developer.twitter.com/en/apps](https://developer.twitter.com/en/apps).

Create a new application by pressing button "Create an app".

Fill up required fields.

Enter correct `Callbacks URL` for your site.

`https://your-domain.com/auth/tw`

In the tab 'Keys and tokens' you will find Consumer API keys:

```
API key: LzEFP.................E5u4
API secret key: ZqYORi..................................mRR5
```

Use them in `social.php` config file.

#### `email.php` — outgoing emails from server

CodeX Media uses a [Sendgrid](https://sendgrid.com) as a mailing service.

1. Register a new account and choose free plan (40000 emails per month)

2. Open [Setup Guide - Integrate - Web API - PHP](https://app.sendgrid.com/guide/integrate/langs/php)

![](assets/create-a-new-key-for-sendgrid.png)

Enter a name for a new key then press «Create Key»

![](assets/sendgrid-test-key.png)

Copy this key and paste to `email.php` config file.

You can fill up `senderName` and `senderEmail`.

### `communities.php` — news autoposting to vk.com

If you want to enable auto posting news to VK community page, then follow [this guide](https://github.com/codex-team/codex.edu/issues/119#issuecomment-296349880). You need to have rights to post anything to the community's wall as that community.

Shortly you need to register a new Standalone application, give it rights to post to walls from your name and get an `access_token`. Then you need to get `group_id` — a page's identifier (integer number below zero).

Then you'll be able to enable or disable posting news to community's wall.

![](assets/editor-with-vk-post-button.png)

## Admin rights

In phpMyAdmin choose `codexmedia` database and open table `Users`. Find row with for your user and change `role` value from `1` to `3`.

Changes will be applied in 5 minutes because of caching to reduce the database load. You can also clear cache yourself by restarting Memcached container.

## Backups 
 
Read: [Creating and restoring backups](creating-and-restoring-backups.md)

## Apply updates

To upgrade the project on your server follow the deploy steps on [releases page](https://github.com/codex-team/codex.media/releases).

### Example

Usually you just need to pull changes and rebuild static files:

```bash
# Pull changes
git pull

# Install new node.js dependencies
docker-compose exec php yarn

# Build static files
docker-compose exec php yarn build
```

But sometimes you also need to rebuild the containers:

```bash
# Stop containers
./bin/docker/down.sh

# Pull changes
git pull

# Build containers
./bin/docker/build.sh

# Run containers
./bin/docker/up.sh

# Install new node.js dependencies
docker-compose exec php yarn

# Build static files
docker-compose exec php yarn build
```

You may need to apply mysql database migrations.
