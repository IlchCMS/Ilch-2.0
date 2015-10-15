#!/bin/sh

if [ ! -f "/vagrant/development/bin/phpcs" ];
then
    echo "phpcs is not installed, run /vagrant/development/bin/setup.sh";
fi

/vagrant/development/bin/phpcs --standard=/vagrant/phpcs.xml $@
