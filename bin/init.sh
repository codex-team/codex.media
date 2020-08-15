#!/bin/bash

echo "Initializing codex.media..."

echo -e "Creating .env file for docker... \c"
  rm .env
  cp sample.env .env
  GENERATED_MYSQL_PASSWORD=$(openssl rand -hex 12)

  if [[ $(uname) =~ Darwin|BSD ]]
  then
    sed -i "" "s/root_password/$GENERATED_MYSQL_PASSWORD/g" ./.env
  else
    sed -i "s/root_password/$GENERATED_MYSQL_PASSWORD/g" ./.env
  fi

  #echo "MySQL root's password: $GENERATED_MYSQL_PASSWORD"
echo "OK"

echo -e "Creating .env file for project... \c"
  cp ./www/sample.env ./www/.env
echo "OK"

echo -e "Creating logs and cache dirs... \c"
  rm -rf ./www/application/{logs,cache}
  mkdir ./www/application/{logs,cache}
  chmod 777 ./www/application/{logs,cache}
echo "OK"

echo -e "Setting up rights for upload directory... \c"
  chmod -R 777 ./www/upload
echo "OK"

