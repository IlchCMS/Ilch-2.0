#!/bin/sh

#rm -rf /usr/share/phpmyadmin

# install phpmyadmin
echo "Downloading phpMyAdmin..."

wget https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.zip -O phpmyadmin.zip
unzip -qq phpmyadmin.zip
rm phpmyadmin.zip
mv phpMyAdmin-*-all-languages /usr/share/phpmyadmin

cp /vagrant/development/vagrant/phpmyadmin_config.php /usr/share/phpmyadmin/config.inc.php

chown -R vagrant:vagrant /usr/share/phpmyadmin
chmod -R 0755 /usr/share/phpmyadmin

mkdir /usr/share/phpmyadmin/tmp/
chmod -R 0777 /usr/share/phpmyadmin/tmp

mysql -uroot -proot < /usr/share/phpmyadmin/sql/create_tables.sql
echo "GRANT SELECT, INSERT, UPDATE, DELETE ON phpmyadmin.* TO 'pma'@'localhost' IDENTIFIED BY 'password';" | mysql -uroot -proot


cat > /etc/apache2/conf-available/phpmyadmin.conf <<'EOF'
# phpMyAdmin Apache configuration

Alias /phpmyadmin /usr/share/phpmyadmin

<Directory /usr/share/phpmyadmin>
    Options SymLinksIfOwnerMatch
    DirectoryIndex index.php
</Directory>

# Disallow web access to directories that don't need it
<Directory /usr/share/phpmyadmin/templates>
    Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/libraries>
    Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/setup/lib>
    Require all denied
</Directory>
EOF

a2enconf phpmyadmin
