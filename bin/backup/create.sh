#!/bin/bash

# Define vars
SECONDS=0
BASEDIR=$(dirname "$0")/../..

# Normalize BASEDIR
CURRENT_DIR=$(pwd)
cd $BASEDIR
BASEDIR=$(pwd)
cd $CURRENT_DIR

BACKUPDIR=$BASEDIR/backup
TEMPDIR=$BACKUPDIR/last-backup

# Load env variables
export $(grep -v '#.*' $BASEDIR/.env | xargs)

# Define function to detect filesize
fileSize() {
  local optChar='c' fmtString='%s'
  [[ $(uname) =~ Darwin|BSD ]] && { optChar='f'; fmtString='%z'; }
  stat -$optChar "$fmtString" "$@"
}

# Recreate temp directory
echo -e "Recreating a temp file for backup... \c"
  rm -rf $TEMPDIR 2>/dev/null
  mkdir $TEMPDIR
echo "OK"

echo -e "Getting backup files... \c"
  # get mysql backup
  mkdir -p $TEMPDIR/mysql
  MYSQLDUMPPATH=$TEMPDIR/mysql/codexmedia.sql
  docker-compose -f $BASEDIR/docker-compose.yml exec mysql /bin/bash -c "mysqldump -u root --password=$MYSQL_PASSWORD codexmedia" > $MYSQLDUMPPATH
  tail -n +2 "$MYSQLDUMPPATH" > "$MYSQLDUMPPATH.tmp" && mv "$MYSQLDUMPPATH.tmp" "$MYSQLDUMPPATH"

  # get redis backup
  mkdir -p $TEMPDIR/redis
  cp $BASEDIR/dump/redis/dump.rdb $TEMPDIR/redis/

  # get .env file
  cp $BASEDIR/.env $TEMPDIR/

  # get www/.env file
  mkdir -p $TEMPDIR/www
  cp $BASEDIR/www/.env $TEMPDIR/www/

  # get www/upload directory
  mkdir -p $TEMPDIR/upload
  cp -R $BASEDIR/www/upload $TEMPDIR/

  # get config files
  mkdir -p $TEMPDIR/www/application/config/
  cp $BASEDIR/www/application/config/database.php $TEMPDIR/www/application/config/ 2>/dev/null
  cp $BASEDIR/www/application/config/email.php $TEMPDIR/www/application/config/ 2>/dev/null
  cp $BASEDIR/www/application/config/social.php $TEMPDIR/www/application/config/ 2>/dev/null
echo "OK"

# Compress backup
echo -e "Compressing backup... \c"
  NOW=`date +"%Y-%m-%d_%H-%M-%S"`
  FILENAME="backup_$NOW.tar.gz"
  FILEPATH="$BACKUPDIR/$FILENAME"
  tar -zcf $FILEPATH -C $TEMPDIR .
  SIZE=$(($(fileSize $FILEPATH) / 1000000))
echo "OK"

# Show report
echo ""
echo "Backup file was created:"
echo "File: $FILENAME"
echo "Size: $SIZE MB"
echo "Time: $(($SECONDS / 60))m $(($SECONDS % 60))s"

