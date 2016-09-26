#!/bin/sh
if [ ! -f "/vagrant/vendor/bin/phpunit" ];
then
    echo "phpunit is not installed, run /vagrant/development/bin/setup.sh";
fi

/vagrant/vendor/bin/phpunit $@
