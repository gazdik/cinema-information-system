#!/bin/bash

if [ -a ./composer.phar ] 
then
	php composer.phar update
else
	composer update
fi

# Remove old database
php app/console doctrine:database:drop --force

# Create new database
php app/console doctrine:database:create

# Create tables from entities
php app/console doctrine:schema:update --force

# Add testing data into database
php app/console doctrine:fixtures:load
