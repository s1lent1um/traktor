#!/bin/bash
script_dir="$(dirname "$0")"
. "/vagrant/vagrant/provisioners.sh"

ensure-dir /var/vagrant

update-apt

install software-properties-common # changed in 14.04
install libpcre3-dev
install libcurl3-openssl-dev

add-php54-repository
apt-get update

install pkg-config
install git-core
install curl
install redis-server


install php5-cli
install php5-dev
install php-pear
install php-apc
install php5-curl
install php5-xdebug
install php5-idn
install php5-redis
install php5-mcrypt

install-composer

pear upgrade pear
pear upgrade
#install-phpunit
pecl-install redis -f
pecl-install proctitle -f

config-hosts
config-locale
config-php-cli



chown -R vagrant /vagrant


# init scripts here
cd /vagrant
#sudo -u vagrant ./install
cd -
#sudo -u vagrant ./update


exit 0