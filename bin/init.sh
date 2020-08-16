#!/bin/bash

# Get base directory path
BASEDIR=$(dirname "$0")/..

# Normalize BASEDIR path
CURRENT_DIR=$(pwd)
cd $BASEDIR
BASEDIR=$(pwd)
cd $CURRENT_DIR

echo "Initializing codex.media."

echo -e "Creating .env file for docker... \c"
  # create .env file for docker from sample
  cp $BASEDIR/sample.env $BASEDIR/.env

  # generate mysql root's password
  GENERATED_MYSQL_PASSWORD=$(openssl rand -hex 12)

  # put mysql root's password to docker's .env file
  if [[ $(uname) =~ Darwin|BSD ]]
  then
    sed -i "" "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/.env
  else
    sed -i "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/.env
  fi
echo "OK"

echo -e "Creating .env file for project... \c"
  # create project's .env file from sample
  cp $BASEDIR/www/sample.env $BASEDIR/www/.env
echo "OK"

echo -e "Creating logs and cache dirs... \c"
  # create internal system dirs for Kohana core
  mkdir $BASEDIR/www/application/{logs,cache} 2>/dev/null
  # setting up rights for them
  chmod 777 $BASEDIR/www/application/{logs,cache}
echo "OK"

echo -e "Setting up rights for upload directory... \c"
  # setting up rights for upload directory
  chmod -R 777 $BASEDIR/www/upload
echo "OK"

echo -e "Copy config files... \c"
  # copy database config
  cp $BASEDIR/www/application/config/database.sample.php $BASEDIR/www/application/config/database.php

  # put mysql root's password to database.php config file
  # this IF exists because of different `sed` command syntax for macOS and Linux
  if [[ $(uname) =~ Darwin|BSD ]]
  then
    sed -i "" "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/www/application/config/database.php
  else
    sed -i "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/www/application/config/database.php
  fi

  # copy the rest config files
  cp $BASEDIR/www/application/config/communities.sample.php $BASEDIR/www/application/config/communities.php
  cp $BASEDIR/www/application/config/email.sample.php $BASEDIR/www/application/config/email.php
  cp $BASEDIR/www/application/config/social.sample.php $BASEDIR/www/application/config/social.php
echo "OK"

echo "Setting up a docker environment"
  # build docker environment
  $BASEDIR/bin/docker/build.sh prod
  # start docker environment
  $BASEDIR/bin/docker/up.sh prod

  # install composer dependencies
  docker-compose -f $BASEDIR/docker-compose.yml exec php composer install

  # wait mysql container start
  sleep 3

  # create a new database
  docker-compose -f $BASEDIR/docker-compose.yml exec -T mysql /bin/bash -c "mysql -u root --password=$GENERATED_MYSQL_PASSWORD -e \"CREATE DATABASE codexmedia;\""
  # import database structure
  docker-compose -f $BASEDIR/docker-compose.yml exec -T mysql /bin/bash -c "mysql -u root --password=$GENERATED_MYSQL_PASSWORD codexmedia" < $BASEDIR/www/migrations/\!_codex-media.sql
echo "Done"

echo ""
echo "MySQL root's password: $GENERATED_MYSQL_PASSWORD"
echo ""
echo "Set up the rest config file by yourself:"
echo " - ./www/application/config/communities.php"
echo " - ./www/application/config/email.php"
echo " - ./www/application/config/social.php"
echo ""
echo "Copy nginx config to run web-server:"
echo "- ./tools/nginx.conf"


