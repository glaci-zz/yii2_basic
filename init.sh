#!/bin/bash
# download composer.phar if not exists
if [ ! -e composer.phar ]
then
	wget https://getcomposer.org/download/1.0.0-alpha11/composer.phar
fi

# install composer plugin
php composer.phar global require "fxp/composer-asset-plugin:~1.1.1"

# update composer
php composer.phar self-update

# install composer.json package
php composer.phar install

# update php package
php composer.phar update

echo "end"
