#!/bin/bash

echo "Building codex.media..."

BASEDIR=$(dirname "$0")/../..

docker-compose -f $BASEDIR/docker-compose.yml build