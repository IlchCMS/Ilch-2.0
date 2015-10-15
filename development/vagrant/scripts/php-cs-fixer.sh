#!/bin/sh
if [ ! -f "/vagrant/development/bin/php-cs-fixer" ];
then
    echo "php-cs-fixer is not installed, run /vagrant/development/bin/setup.sh";
fi

/vagrant/development/bin/php-cs-fixer $@
