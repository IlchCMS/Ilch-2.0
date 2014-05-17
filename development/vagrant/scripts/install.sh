#!/bin/bash
# script to install needed software and do some configuration (called as root)

aptitude -y update

#install apache2
aptitude -y install apache2
a2enmod rewrite

#install mysql
echo "mysql-server mysql-server/root_password password root" | debconf-set-selections && \
echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections && \
aptitude -y install mysql-server

#allow remote login to mysql
echo "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root'" | mysql -uroot -proot
sed -e s/^bind-address/#bind-address/ -i /etc/mysql/my.cnf

#create databases
echo "CREATE DATABASE ilch2; CREATE DATABASE ilch2test;" | mysql -uroot -proot

#install php
aptitude -y install php5 php5-mysqlnd php5-gd php5-xdebug php5-curl libapache2-mod-php5

# configure xdebug fore remote debugging
cat /vagrant/development/vagrant/xdebug.ini | tee -a /etc/php5/conf.d/20-xdebug.ini > /dev/null

#link document root to vagrant folder
rm -rf /var/www
ln -s /vagrant/ /var/www

#restart services
service mysql restart
service apache2 restart

#install git
aptitude -y install git
