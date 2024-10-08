#!/bin/bash

APP_ROOT=/data/www/whise-api

docker rm -f whise-fpm
docker build --file="./srv/fpm/Dockerfile"  --build-arg environment=${env} -t whise-api/fpm .

docker run --name  whise-fpm \
     -p 192.168.100.5:9009:9000 \
     -v ${APP_ROOT}:/var/www/html \
     -d --restart always  whise-api/fpm
