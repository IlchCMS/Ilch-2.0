#!/bin/sh
DIR=`dirname $0`
START_PWD=${PWD}
cd ${DIR}
echo "--- Install/Update Composer"
if [ ! -f "composer.phar" ]; then
    curl -sS https://getcomposer.org/installer | php
    chmod +x composer.phar
else
    php ./composer.phar self-update
    chmod +x composer.phar
fi

cd ..
echo "--- Update Development Vendor"
php bin/composer.phar install

cd ..
echo "--- Update System Vendor"
php development/bin/composer.phar install

php build/optimize_vendor.php


cd ${START_PWD}
