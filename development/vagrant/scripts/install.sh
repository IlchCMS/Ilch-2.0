#!/bin/bash
# script to install needed software and do some configuration (called as root)
export DEBIAN_FRONTEND=noninteractive

#configure locale and timezone
export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
locale-gen en_US.UTF-8
echo "Europe/Berlin" > /etc/timezone && \
dpkg-reconfigure -f noninteractive tzdata && \
sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen && \
echo 'LANG="en_US.UTF-8"'>/etc/default/locale && \
dpkg-reconfigure --frontend=noninteractive locales && \
update-locale LANG=en_US.UTF-8

aptitude -q -y update

#install apache2
aptitude -q -y install apache2
a2enmod rewrite

# install mysql
echo "mysql-server mysql-server/root_password password root" | debconf-set-selections && \
echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections && \
aptitude -q -y install mysql-server

# allow remote login to mysql
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root'" | mysql -uroot -proot
sed -e 's/^bind-address/#bind-address/' -i /etc/mysql/my.cnf

# create databases
echo "CREATE DATABASE ilch2; CREATE DATABASE ilch2test;" | mysql -uroot -proot

# install php
aptitude -q -y install php5 php5-mysqlnd php5-gd php5-xdebug php5-curl php5-intl libapache2-mod-php5

# configure xdebug fore remote debugging
cat /vagrant/development/vagrant/xdebug.ini | tee -a /etc/php5/mods-available/xdebug.ini > /dev/null

# configure web server
cp -f /vagrant/development/vagrant/000-default.conf /etc/apache2/sites-available/

# install mailhog
sh /vagrant/development/vagrant/scripts/install_mailhog.sh

# restart services
service mysql restart
service apache2 restart

# install git
aptitude -q -y install git
