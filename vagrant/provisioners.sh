#!/bin/bash
PROJECT=src
PROJECT_DIR="/srv/${PROJECT}"

CURRENT_DIR=$(pwd)

exiterr() {
  if [ "$1" -gt 0 ]; then
    if [ ! -z "$2" ]; then
      echo $2
    fi
    exit $1
  fi
}

ensure-dir() {
    if [ ! -d $1 ]; then
       mkdir -p $1
       exiterr $? "Failed to create directory $1"
    fi
}

ensure-rm() {
    if [ -f $1 ]; then
       rm -r $1
       exiterr $? "Failed to remove $1"
    fi
}

copy() {
#    echo "$@"
    cp $1 $2
    exiterr $? "Failed to copy $1 into $2"
}

installed() {
  if [ -z "$2" ]; then
    if [ -f /var/vagrant/installed-$1 ]; then
      return 0
    fi
    return 1
  fi

  touch /var/vagrant/installed-$1
}

install() {
    installed $1
    if [ "$?" -gt 0 ]; then
        apt-get install -q -y $1 || exiterr $? "$1 installation fault"
        installed $1 ok
    fi
}

configured() {
    if [ -z "$2" ]; then
      if [ -f /var/vagrant/configured-$1 ]; then
        return 0
      fi
      return 1
    fi

    touch /var/vagrant/configured-$1
}

pecl-install() {
  package=$1
  package=${package%-beta}
  package=${package%-alpha}
  package=${package%-devel}
  echo $package
  installed pecl-$package
  if [ "$?" -gt 0 ]; then
    printf "\n" | pecl install -a $2 $1 || exiterr $? "pecl $1 installation fault"
    echo "extension=$package.so" > /etc/php5/mods-available/${package}.ini
    php5enmod $package
    installed pecl-$package ok
  fi
}

update-apt() {
  # TODO: ttl
  configured apt-update
  if [ "$?" -gt 0 ]; then
    apt-get update && DEBIAN_FRONTEND=noninteractive apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" --force-yes -fuy upgrade
    exiterr $? "Failed to update apt"
    configured apt-update ok
  fi
}

install-composer() {
    installed php-composer
    if [ "$?" -gt 0 ]; then
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
        sudo -uvagrant composer global require "fxp/composer-asset-plugin:~1.1"
        installed php-composer ok
    fi
}

add-php55-repository() {
  configured php55
  if [ "$?" -gt 0 ]; then
    add-apt-repository -y ppa:ondrej/php5-oldstable
    exiterr $? "Failed to add the php5 repository"
    configured php55 ok
  fi
}

add-php56-repository() {
  configured php56
  if [ "$?" -gt 0 ]; then
    add-apt-repository -y ppa:ondrej/php5
    exiterr $? "Failed to add the php5 repository"
    configured php56 ok
  fi
}


config-hosts() {
  copy ${PROJECT_DIR}/vagrant/hosts /etc/hosts
}

config-php-fpm() {
  copy ${PROJECT_DIR}/vagrant/php.ini /etc/php5/fpm/php.ini
  copy ${PROJECT_DIR}/vagrant/fpm.conf /etc/php5/fpm/pool.d/www.conf
}

config-php-cli() {
  copy ${PROJECT_DIR}/vagrant/php.ini /etc/php5/cli/php.ini
}

config-nginx() {
    ensure-dir /var/log/www/
    ensure-rm /etc/nginx/sites-enabled/default
    copy "${PROJECT_DIR}/vagrant/sites-available/*" /etc/nginx/sites-enabled/
    copy ${PROJECT_DIR}/vagrant/nginx.conf /etc/nginx/nginx.conf
    copy ${PROJECT_DIR}/vagrant/fastcgi_params /etc/nginx/fastcgi_params
}

config-locale() {
  configured locale
  if [ "$?" -gt 0 ]; then
    locale-gen ru_RU.UTF-8
    exiterr $? "Failed to generate locale ru_RU.UTF-8"
    sed -i "s/LC_ALL=\"en_US\"/LC_ALL=\"ru_RU.UTF-8\"/" /etc/default/locale
    exiterr $? "Failed to replace locale into /etc/default/locale"
    dpkg-reconfigure locales
    exiterr $? "Failed to reconfigure locale"
    configured locale ok
  fi
}
