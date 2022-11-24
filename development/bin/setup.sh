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
echo "--- Update Development Vendor"
cd ..
php bin/composer.phar install
echo "--- Update System Vendor"
cd ..
php development/bin/composer.phar install
cd ${START_PWD}
