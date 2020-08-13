#!/bin/bash

# Define basedir
BASEDIR=$(dirname "$0")/../..

# Normalize BASEDIR
CURRENT_DIR=$(pwd)
cd $BASEDIR
BASEDIR=$(pwd)
cd $CURRENT_DIR

BACKUPDIR=$BASEDIR/backup
TEMPDIR=$BACKUPDIR/last-restoration

# Load env variables
export $(grep -v '#.*' $BASEDIR/.env | xargs)

# get archive file
ARCHIVE=$1

if [ "$ARCHIVE" = "" ]; then
  echo "Path to archive file is missing"
  exit 1
fi

# Recreate a target directory for unpacking
echo -e "Recreating a temp directory for backup data... \c"
  rm -rf $TEMPDIR
  mkdir $TEMPDIR
echo "OK"

# unpack archive
echo -e "Unpacking archive... \c"
  tar -xf $ARCHIVE -C $TEMPDIR
echo "OK"

# put files
echo -e "Putting back config files... \c"
  # restore .env file
  rm $BASEDIR/.env 2>/dev/null
  cp $TEMPDIR/.env $BASEDIR/ 2>/dev/null

  # restore www/.env file
  rm $TEMPDIR/www/.env 2>/dev/null
  cp $TEMPDIR/www/.env $BASEDIR/www 2>/dev/null

  # restore config files
  rm $BASEDIR/www/application/config/database.php 2>/dev/null
  cp $TEMPDIR/www/application/config/database.php $BASEDIR/www/application/config/ 2>/dev/null

  rm $BASEDIR/www/application/config/email.php 2>/dev/null
  cp $TEMPDIR/www/application/config/email.php $BASEDIR/www/application/config/ 2>/dev/null

  rm $BASEDIR/www/application/config/social.php 2>/dev/null
  cp $TEMPDIR/www/application/config/social.php $BASEDIR/www/application/config/ 2>/dev/null

  rm $BASEDIR/www/application/config/telegram-notification.php 2>/dev/null
  cp $TEMPDIR/www/application/config/telegram-notification.php $BASEDIR/www/application/config/ 2>/dev/null
echo "OK"

echo -e "Putting back upload directory... \c"
  # get www/upload directory
  rm -rf $BASEDIR/www/upload
  cp -R $TEMPDIR/upload $BASEDIR/www/
  chmod -R 777 $BASEDIR/www/upload
echo "OK"

# restore mysql database
echo "Restoring mysql database..."
docker-compose -f $BASEDIR/docker-compose.prod.yml exec -T mysql /bin/bash -c "mysql -u root --password=$MYSQL_PASSWORD codex-media" < $TEMPDIR/mysql/codex-media.sql

# stop redis container
docker-compose -f $BASEDIR/docker-compose.prod.yml stop redis
# import redis db
echo "Restoring redis database..."
rm $BASEDIR/dump/redis/dump.rdb
cp $TEMPDIR/redis/dump.rdb $BASEDIR/dump/redis/
# start redis container
docker-compose -f $BASEDIR/docker-compose.prod.yml start redis

echo "Restarting memcached container..."
# reload memcached container
docker-compose -f $BASEDIR/docker-compose.prod.yml restart memcached

echo ""
echo "Backup $ARCHIVE has been restored successfully"