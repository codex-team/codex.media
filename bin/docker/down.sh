#!/bin/bash

echo "Stopping codex.media..."

BASEDIR=$(dirname "$0")/../..

docker-compose -f $BASEDIR/docker-compose.yml down