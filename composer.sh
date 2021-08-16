#!/bin/bash

docker run -it --rm -v "$(pwd -P)":/var/www/app posmplus_app composer $1
