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

# Add password for root user
echo -e "root\nroot" | passwd -q

apt-get -y update

apt-get -y install curl

#install apache2
apt-get -y install apache2
a2enmod rewrite

# install mysql
echo "mysql-server mysql-server/root_password password root" | debconf-set-selections && \
echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections && \
apt-get -y install default-mysql-server

# allow remote login to mysql
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY 'root'" | mysql -uroot -proot
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root'" | mysql -uroot -proot
sed -e 's/bind-address.*/bind-address = 0.0.0.0/g' -i /etc/mysql/mariadb.conf.d/50-server.cnf

# create databases
echo "CREATE DATABASE ilch2; CREATE DATABASE ilch2test;" | mysql -uroot -proot

# install php
apt-get -y install php php-curl php-gd php-intl php-mbstring php-mysql php-xdebug php-xml php-zip libapache2-mod-php

# configure xdebug fore remote debugging
cat /vagrant/development/vagrant/xdebug.ini | tee -a /etc/php/7.4/mods-available/xdebug.ini > /dev/null

# configure web server
cp -f /vagrant/development/vagrant/000-default.conf /etc/apache2/sites-available/

# install mailhog
sh /vagrant/development/vagrant/scripts/install_mailhog.sh

# restart services
service mysql restart
service apache2 restart

# install git
apt-get -y install git
