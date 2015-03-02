#!/bin/bash

SOURCEDIR="$( dirname "${BASH_SOURCE[0]}" )/";

###################################
## Default files
###################################

if [ ! -f index.php ]; then
    echo "- Add default index.php";
    cp "${SOURCEDIR}files/index.php" "index.php";
fi;

if [ ! -f .htaccess ]; then
    echo "- Add default .htaccess";
    cp "${SOURCEDIR}files/default.htaccess" ".htaccess";
fi;

###################################
## Conf
###################################

if [ ! -f app/etc/config.xml ]; then
    echo "- Add default config.xml";
    cp "${SOURCEDIR}files/config.xml" "app/etc/config.xml";
fi;

read -p "Project ID : " project_id;
sed -i '' "s/INTEGENTODBNAME/${project_id}/" "app/etc/config.xml";

###################################
## DB
###################################

# read -p "Project tmp domain (without http) : " project_domain;

###################################
## Permissions
###################################

read -p "Set permissions ? (y/N) " set_permissions;
if [ "${set_permissions}" = "n" ]; then
    find . -type f -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    chmod o+w app/etc
    chmod 550 mage;
    mkdir -p var;
    chmod -R 755 var;
    mkdir -p media;
    chmod -R 755 media;
fi;

###################################
## Cache
###################################

echo "- Delete cache";
rm -rf "var/cache/";