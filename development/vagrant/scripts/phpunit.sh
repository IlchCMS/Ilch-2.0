#!/bin/sh
if [ ! -f "/vagrant/development/bin/phpunit" ];
then
    echo "phpunit is not installed, run /vagrant/development/bin/setup.sh";
fi

/vagrant/development/bin/phpunit $@
