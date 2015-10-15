#!/bin/bash
# script to setup the home directory, and other things for vagrant user (not called as root)

mkdir -p ~/bin
echo 'export PATH="$PATH:/home/vagrant/bin"' >> ~/.bashrc
echo 'cd /vagrant' >> ~/.bashrc

# run setup.sh if no vendor directory exists
if [ ! -d "/vagrant/development/vendor" ];
then
   /vagrant/development/bin/setup.sh
fi

cd ~/bin
ln -s /vagrant/development/vagrant/scripts/phpcs.sh phpcs
ln -s /vagrant/development/vagrant/scripts/phpunit.sh phpunit
ln -s /vagrant/development/vagrant/scripts/php-cs-fixer.sh php-cs-fixer
ln -s /vagrant/development/vagrant/scripts/phpcsfix.sh phpcsfix


