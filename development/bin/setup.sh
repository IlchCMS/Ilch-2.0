#!/bin/sh
DIR=`dirname $0`
START_PWD=${PWD}
cd ${DIR}
if [ ! -f "composer.phar" ]; then
    curl -sS https://getcomposer.org/installer | php
    chmod +x composer.phar
else
    ./composer.phar self-update
fi
cd ..
bin/composer.phar install
cd ..
development/bin/composer.phar install
cd ${START_PWD}
