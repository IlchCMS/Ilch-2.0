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
if [ "$1" = "travis" ]; then
    bin/composer.phar install --no-dev
    cat <<<PHP > ../tests/config.php
<?php
//Config for Tests
$config["dbEngine"] = "Mysql";
$config["dbHost"] = "127.0.0.1";
$config["dbUser"] = "travis";
$config["dbPassword"] = "";
$config["dbName"] = "ilch2_test";
$config["dbPrefix"] = "";
PHP
else
    bin/composer.phar install
fi
cd ${START_PWD}
