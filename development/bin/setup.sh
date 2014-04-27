#!/bin/sh
DIR=`dirname $0`
PWD=`pwd`
cd $DIR
if [ ! -f "composer.phar" ]; then
    curl -sS https://getcomposer.org/installer | php
    chmod +x composer.phar
else
    ./composer.phar self-update
fi
cd ..
bin/composer.phar install
cd $PWD
