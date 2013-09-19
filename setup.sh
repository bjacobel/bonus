#!/bin/sh

# Create an Orient database
mysql -uroot -e "CREATE DATABASE DB02Orient;"

# Get Apache and PHP set up
echo "Would you like to OVERWRITE your httpd.conf and php.ini with the files included?"
echo "This is really only advisable if you don't host any other sites on this machine."
read -p "Do it? [y/n]: " yn
case $yn in
 [Yy]*) echo "OK. I'm making backups, just in case." && sudo cp /etc/apache2/httpd.conf /etc/apache2/httpd-backup.conf && sudo cp setup-files/httpd.conf /etc/apache2 && sudo cp /etc/php.ini /etc/php-backup.ini && sudo cp setup-files/php.ini /etc/;;
 * ) echo "OK, I won't. You're on your own to get that working!!";;
esac

# Make an apache vsite
sudo cp ./setup-files/bowdoinorient.conf /etc/apache2/other
sudo ln -s `pwd` /Library/WebServer/Documents

# make bowdoinorient.dev work
sudo sh -c "echo '127.0.0.1 bowdoinorient.dev' >> /etc/hosts"

# restart apache
sudo apachectl -k restart

# give it a go
echo "\nOK, I'm done. Now all you need is an SQL dump and you're good to go."

