#!/bin/bash

printf "{'repositories': [{ 'type': 'composer', 'url': 'https://php.fury.io/crazyfactory/'}]}" > ~/config.json
printf "{'http-basic': {'php.fury.io': { 'username': '$GEMFURY_TOKEN', 'password': ''}}" > ~/auth.json
