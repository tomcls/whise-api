#!/bin/bash

APP_ROOT=~/www/whise-api

docker rm -f whise-nginx
docker build --file=${APP_ROOT}/srv/nginx/Dockerfile  --build-arg environment=${ENVIRONMENT} -t whise/nginx .

docker run   --name whise-nginx \
--link whise-api-php \
-p  8085:80 \
-v  $APP_ROOT:/usr/share/nginx/html \
-v  $APP_ROOT/srv/nginx/sites-available/default.conf:/etc/nginx/conf.d/default.conf \
-d whise/nginx


