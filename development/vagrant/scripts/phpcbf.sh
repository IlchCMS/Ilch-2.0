#!/bin/sh

if [ ! -f "/vagrant/development/bin/phpcbf" ];
then
    echo "phpbf is not installed, run /vagrant/development/bin/setup.sh";
fi

/vagrant/development/bin/phpcbf --standard=/vagrant/phpcs.xml $@
