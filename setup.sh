#!/usr/bin/env bash

#COLORS
# Reset
Color_Off='\033[0m'       # Text Reset

# Regular Colors
Red='\033[0;31m'          # Red
Green='\033[0;32m'        # Green
Yellow='\033[0;33m'       # Yellow
Purple='\033[0;35m'       # Purple
Cyan='\033[0;36m'         # Cyan

# Update packages and Upgrade system
export DEBIAN_FRONTEND=noninteractive
export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
# locale-gen en_US.UTF-8
# dpkg-reconfigure locales

echo -e "$Cyan \n Updating System.. $Color_Off"
sudo apt-get update -y >/dev/null 2>&1
echo -e "$Cyan \n Done. $Color_Off"
# sudo apt-get upgrade -y 2>&1 >/dev/null

## Install Apache
echo -e "$Cyan \n Installing Apache2.. $Color_Off"
# sudo dpkg --configure -a
sudo apt-get install apache2 -y >/dev/null 2>&1
sudo apt-get install apache2-utils  -y >/dev/null 2>&1
sudo apt-get install libexpat1 -y >/dev/null 2>&1
sudo apt-get install ssl-cert -y >/dev/null 2>&1
echo -e "$Cyan \n Done. $Color_Off"

## Install PHP Packages
echo -e "$Cyan \n Installing Php.. $Color_Off"
# sudo dpkg --configure -a
sudo apt-get install software-properties-common python-software-properties -y  >/dev/null 2>&1
sudo add-apt-repository ppa:ondrej/php -y >/dev/null 2>&1
sudo apt-get update -y >/dev/null 2>&1
sudo apt-get install php7.2  php7.2-cli php7.2-common -y >/dev/null 2>&1
echo -e "$Cyan \n Done. $Color_Off"

## Install Java Package
sudo apt-get install openjdk-9-jre-headless -y >/dev/null 2>&1

##Install Solr Package
echo -e "$Cyan \n Installing Apache SOLR 7.5.0.. $Color_Off"
cd /tmp && wget http://www-eu.apache.org/dist/lucene/solr/7.5.0/solr-7.5.0.tgz >/dev/null 2>&1
tar xzf solr-7.5.0.tgz solr-7.5.0/bin/install_solr_service.sh --strip-components=2 >/dev/null 2>&1
# ./install_solr_service.sh solr-7.3.1.tgz
sudo bash ./install_solr_service.sh solr-7.5.0.tgz >/dev/null 2>&1
echo -e "$Cyan \n Done. $Color_Off"
