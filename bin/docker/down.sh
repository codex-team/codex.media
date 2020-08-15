#!/bin/bash

echo "Stopping codex.media..."

BASEDIR=$(dirname "$0")/../..

ENV=prod
if [ "$1" != "" ]; then
    ENV=$1
fi

docker-compose -f $BASEDIR/docker-compose.$ENV.yml down