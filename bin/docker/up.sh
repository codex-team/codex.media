#!/bin/bash

echo "Starting codex.media..."

BASEDIR=$(dirname "$0")/../..

docker-compose -f $BASEDIR/docker-compose.yml up -d