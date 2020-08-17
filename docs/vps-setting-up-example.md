# VPS setting up example

This guide describes installation steps in detail if you want to run your own site.

## Setting up the Ubuntu 20.04

Example setting up a fresh new VPS with Ubuntu 20.04.

### Prepare system

Update packages list and upgrade system at first.

```bash
apt update
apt upgrade -y
```

Install the necessary packages.

```bash
apt install -y net-tools nginx git
```

### Docker installation

CodeX Media is build to run in Docker so you need to have it installed.

```bash
apt install -y apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable"
apt install -y docker-ce
```

### docker-compose installation

For containers composing we use `docker-compose` utility. Install it too.

Find current stable version: [https://github.com/docker/compose/releases](https://github.com/docker/compose/releases) and replace **VERSION** word in the following command.

```bash
VERSION=1.26.2
curl -L https://github.com/docker/compose/releases/download/$VERSION/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
```

### Create a swap partition

Building public files requests at least 1 GB RAM (2 GB of course will be better).

If your VPS has not enough memory then create a swap partition.

```bash
fallocate -l 1G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo "/swapfile swap swap defaults 0 0" > /etc/fstab
```

You can find any other guide for a swap partition creating.

Check result vie `free` command. For example we have 500 MB RAM and 1 GB swap:

```bash
root@stormy-zimba:~# free
              total        used        free      shared  buff/cache   available
Mem:         489444      222836       16384         396      250224      252020
Swap:       1048572      312124      736448
``` 

### Initialize the project

Clone repository

```bash
cd /var/www
git clone https://github.com/codex-team/codex.media
```

Initialize project

```bash
cd codex.media
./bin/init
```

For the setting up the rest config files read [deployment guide](general-installation.md).

### Set up nginx 

Here is a sample nginx config `tools/nginx.conf` for proxying requests to docker:

```conf
server {
    listen 80;
    #server_name codex-media.com;
    client_max_body_size 50M;

    error_log /var/log/nginx/codex-media_error.log;
    access_log /var/log/nginx/codex-media_access.log;

    location / {
        include proxy_params;
        proxy_pass http://127.0.0.1:8880/;
    }
}
```

For a fresh VPS you can use the following command to copy and use config file.

```bash
rm /etc/nginx/sites-available/default
cp tools/nginx.conf /etc/nginx/sites-available/codex-media.conf
ln -s /etc/nginx/sites-available/codex-media.conf /etc/nginx/sites-enabled/codex-media.conf
```

Edit the config file and change `server_name` to your domain. 

```bash
nano /etc/nginx/sites-enabled/codex-media.conf
```

Reload nginx to apply changes

```bash
service nginx reload
```

Now you can open your site and see the main page.

### HTTPS — secure connection via SSL certificate

After setting up the site name you may need to set up a HTTPS connection.

At all you may use [Certbot](https://certbot.eff.org).

Installation

```bash
curl -o- https://raw.githubusercontent.com/vinyll/certbot-install/master/install.sh | bash
```

Then run `certbot`

```bash
certbot --nginx
```

#### Set up auto renewing

Edit the crontab

```bash
crontab -e
```

And enter a task

```cron
0 5 */10 * * certbot renew --post-hook "systemctl reload nginx"
```
