#!/bin/bash

BASEDIR=$(dirname "$0")/..

# Normalize BASEDIR
CURRENT_DIR=$(pwd)
cd $BASEDIR
BASEDIR=$(pwd)
cd $CURRENT_DIR

echo "Initializing codex.media..."

echo -e "Dropping all changes... \c"
  rm $BASEDIR/.env 2>/dev/null
  rm $BASEDIR/www/.env 2>/dev/null
  rm -rf $BASEDIR/www/application/{logs,cache} 2>/dev/null
  rm $BASEDIR/www/application/config/database.php 2>/dev/null
echo "OK"

echo -e "Creating .env file for docker... \c"
  cp $BASEDIR/sample.env $BASEDIR/.env
  GENERATED_MYSQL_PASSWORD=$(openssl rand -hex 12)

  if [[ $(uname) =~ Darwin|BSD ]]
  then
    sed -i "" "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/.env
  else
    sed -i "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/.env
  fi
echo "OK"

echo -e "Creating .env file for project... \c"
  cp $BASEDIR/www/sample.env $BASEDIR/www/.env
echo "OK"

echo -e "Creating logs and cache dirs... \c"
  mkdir $BASEDIR/www/application/{logs,cache}
  chmod 777 $BASEDIR/www/application/{logs,cache}
echo "OK"

echo -e "Setting up rights for upload directory... \c"
  chmod -R 777 $BASEDIR/www/upload
echo "OK"

echo -e "Copy config files... \c"
  cp $BASEDIR/www/application/config/database.sample.php $BASEDIR/www/application/config/database.php

  if [[ $(uname) =~ Darwin|BSD ]]
  then
    sed -i "" "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/www/application/config/database.php
  else
    sed -i "s/root_password/$GENERATED_MYSQL_PASSWORD/g" $BASEDIR/www/application/config/database.php
  fi

  cp $BASEDIR/www/application/config/communities.sample.php $BASEDIR/www/application/config/communities.php
  cp $BASEDIR/www/application/config/email.sample.php $BASEDIR/www/application/config/email.php
  cp $BASEDIR/www/application/config/social.sample.php $BASEDIR/www/application/config/social.php
echo "OK"

echo "Setting up a docker environment"
  $BASEDIR/bin/docker/build.sh prod
  $BASEDIR/bin/docker/up.sh prod

  docker-compose -f $BASEDIR/docker-compose.prod.yml exec php composer install

  sleep 3
  docker-compose -f $BASEDIR/docker-compose.prod.yml exec -T mysql /bin/bash -c "mysql -u root --password=$GENERATED_MYSQL_PASSWORD -e \"CREATE DATABASE codexmedia;\""
  docker-compose -f $BASEDIR/docker-compose.prod.yml exec -T mysql /bin/bash -c "mysql -u root --password=$GENERATED_MYSQL_PASSWORD codexmedia" < $BASEDIR/www/migrations/\!_codex-media.sql
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


