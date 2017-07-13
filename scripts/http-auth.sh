#!/bin/bash

printf '{"repositories": { "local":{ "type": "composer", "url": "https://php.fury.io/crazyfactory/"} } }' > ~/.composer/config.json
composer global config -l | grep http
printf '{"http-basic": {"php.fury.io": { "username": "$GEMFURY_TOKEN", "password": ""}}}' > ~/auth.json