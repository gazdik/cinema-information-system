#!/bin/bash

rm -rf app/cache/dev/*

echo 'Removing the old dist directory...'
rm -rf ../iis-dist
echo 'Copying project directory...'
mkdir ../iis-dist

cp -rf app ../iis-dist/
cp -rf bin ../iis-dist/
cp -rf src ../iis-dist/
cp -rf web ../iis-dist/
cp -rf composer* ../iis-dist/

cd ../iis-dist

# Remove Git
echo 'Removing the old dist directory...'
rm -rf .git .gitignore

# Clear caches
echo 'Clearing caches...'
rm -rf app/cache/prod
rm -rf app/cache/dev
mkdir app/cache/prod

# Clear logs
echo 'Clearing logs...'
rm -rf app/logs
mkdir app/logs

# Clear Uploads
echo 'Clearing uploads...'
rm -rf web/uploads
mkdir web/uploads

# Rebuild vendor
echo 'Removing vendor...'
rm -rf vendor

echo 'Starting composer install...'
export SYMFONY_ENV=prod
composer install --no-dev --optimize-autoloader

echo 'Clearing production caches...'
php app/console cache:clear --env=prod --no-debug

echo 'Assetic dump...'
php app/console assetic:dump --env=prod --no-debug

echo 'Configuring database...'
rm app/config/parameters.yml
mv app/config/parameters_prod.yml app/config/parameters.yml

echo 'Clearing cache again...'
rm -rf app/cache/prod
mkdir app/cache/prod

echo 'Editing web folder...'
cd web
sed -i 's:/../app:/app:g' app.php
sed -i 's:/../app:/app:g' app_dev.php
sed -i 's:/../app:/app:g' config.php
cd ..

sed -i "s:'/../../../web/':'/../../../':g" src/AppBundle/Entity/Prisoner.php
sed -i "s:'/../../../web/':'/../../../':g" src/AppBundle/Entity/Supervisor.php

echo 'Moving web folder upwards...'
cp -L -rf web/. ./
rm -rf web

echo 'Creating zip file...'
zip -r ../iis-dist_$(date +%Y-%m-%d_%k:%M:%S).zip * .htaccess
