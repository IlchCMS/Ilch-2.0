#!/bin/bash
# script to install needed software and do some configuration (called as root)
export DEBIAN_FRONTEND=noninteractive

aptitude -q -y update

# configure update
echo "grub-pc grub-pc/install_devices multiselect /dev/sda" | debconf-set-selections && \
echo "grub-pc grub-pc/install_devices_disks_changed multiselect /dev/sda" | debconf-set-selections && \
aptitude -q -y upgrade

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
aptitude -q -y install php5 php5-mysqlnd php5-gd php5-xdebug php5-curl libapache2-mod-php5

# configure xdebug fore remote debugging
cat /vagrant/development/vagrant/xdebug.ini | tee -a /etc/php5/conf.d/20-xdebug.ini > /dev/null

# link document root to vagrant folder
rm -rf /var/www
ln -s /vagrant/ /var/www

# allow .htaccess override
sed -e 's/AllowOverride None/AllowOverride All/' -i /etc/apache2/sites-available/default

# set timezone
echo "Europe/Berlin" > /etc/timezone && \
dpkg-reconfigure --frontend noninteractive tzdata

# install mailhog
sh /vagrant/development/vagrant/scripts/install_mailhog.sh

# restart services
service mysql restart
service apache2 restart

# install git
aptitude -q -y install git
