#!/bin/bash
echo "Provisioning virtual machine..."

export DEBIAN_FRONTEND=noninteractive

# mysql
echo mysql-server mysql-server/root_password password root | debconf-set-selections
echo mysql-server mysql-server/root_password_again password root | debconf-set-selections

# Deps
echo "deb http://nginx.org/packages/mainline/ubuntu/ trusty nginx" >> /etc/apt/sources.list
echo "deb-src http://nginx.org/packages/mainline/ubuntu/ trusty nginx" >> /etc/apt/sources.list
wget --quiet http://nginx.org/keys/nginx_signing.key -O- | apt-key add -
apt-get update --assume-yes
apt-get install --assume-yes php5-fpm php5-cli nginx mysql-server php5-mysql

# Database
mysql -u root -proot < /vagrant/provision/init.sql
mysql -u user -ppass anychart_sample < /vagrant/dump.sql

# nginx
sudo cp /vagrant/provision/nginx.conf /etc/nginx/nginx.conf
sudo service nginx restart
