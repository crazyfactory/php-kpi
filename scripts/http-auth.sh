#!/bin/bash

printf "{\"repositories\": [ { \"type\": \"composer\", \"url\": \"https://php.fury.io/crazyfactory/\"} ] }" > ~/.composer/config.json
printf "{ \"http-basic\": {\"php.fury.io\": { \"username\": \"$GEMFURY_TOKEN\", \"password\": \"\"}}" > ~/.composer/auth.json
composer global config -l | grep http
