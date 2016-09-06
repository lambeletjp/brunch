#!/bin/bash

# must be executed with root-privileges
if [ `id -u` -ne 0 ] ; then sudo $0 $@ ; exit $? ; fi

# switch to non-interactive
export DEBIAN_FRONTEND=noninteractive
# supplied configuration for debconf
export DEBCONF_DB_FALLBACK="File{/vagrant/configs/debconf/config.dat}"

#ELASTICSEARCH_VERSION=1.3.2
#ELASTICSEARCH_DEB_URL="https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-${ELASTICSEARCH_VERSION}.deb"
#MODPAGESPEED_DEB_URL="https://dl-ssl.google.com/dl/linux/direct/mod-pagespeed-stable_current_amd64.deb"

NODE_MODULES="uglify-js@2.2.0 cssmin grunt-cli uglifycss bower"

get_packages() {
set -- `grep -vE '^\s+#' <<- EOF
    # utils
    build-essential
    language-pack-de
    language-pack-en
    ruby
    git-core
    htop
    mc
    curl
    # main services
    # varnish
    # apache2
    nginx
    memcached
    mysql-server
    elasticsearch
    # nodejs (via chris-lea PPA)
    nodejs
    # java
    openjdk-7-jre-headless
    # php and php-modules
    php5-fpm
    # libapache2-mod-php5
    php5-imagick
    php5-memcache
    php5-memcached
    php5-xdebug
    php5-mysql 
    php5-sqlite 
    php5-curl 
    php5-intl
    php5-xsl
    # phpmyadmin
    jpegoptim
EOF`

echo $@
}


# ==== SYSTEM SETTINGS ====

initSwapFile() {
    fallocate -l 1024m /swapfile
    dd if=/dev/zero of=/swapfile bs=1024 count=1048576
    mkswap /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    swapon -a
}

initSwapFile


# ==== CLEANUP ====
# ---- basebox contains outdated guest-additions, need to remove them for the autoupdate-plugin to work
# apt-get -y purge virtualbox-guest-dkms virtualbox-guest-utils virtualbox-guest-x11
apt-get -y purge apache2
apt-get -y autoremove



# ==== PACKAGE-INSTALLATION ====
# ---- ppa for nodejs and php 5.4
add-apt-repository -y ppa:chris-lea/node.js
add-apt-repository -y ppa:ondrej/php5-oldstable

wget -qO - http://packages.elasticsearch.org/GPG-KEY-elasticsearch | sudo apt-key add -
echo "deb http://packages.elasticsearch.org/elasticsearch/1.3/debian stable main" >> /etc/apt/sources.list

# ---- system updates
apt-get -qqy update
apt-get -y dist-upgrade

# ---- ubuntu-packages
apt-get -y install `get_packages`

# ---- elasticsearch
#wget -cO /tmp/elasticsearch.deb "$ELASTICSEARCH_DEB_URL"
#dpkg -i /tmp/elasticsearch.deb

( # es-plugins
    cd /usr/share/elasticsearch

    ./bin/plugin --install mobz/elasticsearch-head
    ./bin/plugin --install lukas-vlcek/bigdesk
    ./bin/plugin --install royrusso/elasticsearch-HQ
)

# ---- mod_pagespeed
#wget -cO /tmp/mod-pagespeed.deb "$MODPAGESPEED_DEB_URL"
#dpkg -i /tmp/mod-pagespeed.deb

# ---- update dependencies
apt-get -yf install


# ---- composer
mkdir -p /opt/{composer,bin}
curl -sS https://getcomposer.org/installer | php -- --install-dir=/opt/composer
ln -sf /opt/composer/composer.phar /opt/bin/composer

# ---- nodejs-modules
npm install -g $NODE_MODULES

# ---- sass-compiler
gem install --no-rdoc --no-ri sass --pre





# ==== CONFIGURATION ====

chown -R vagrant:vagrant /var/log/nginx

# setup a root-password for mysql
mysqladmin -u root password root

# open up from anywhere
mysql -uroot -proot -e "GRANT ALL ON *.* TO 'root'@'%' IDENTIFIED BY 'root';"




## copies the system configuration and restarts all services

#APACHE2_SITES="default aeto.dev cms.aeto.dev edma.aeto.dev"
#APACHE2_MODULES="pagespeed"

SERVICES="nginx php5-fpm memcached elasticsearch mysql"

# copy over all customized configuration-files
(
    cd /vagrant/configs
    for d in etc home ; do
        for f in `find $d -type f` ; do
            d=`dirname "${f}"`

            [[ -d "$d" ]] || mkdir -p "${d}"
            
            cp -v "${f}" "/${f}"
        done
    done

    chown -R vagrant:vagrant /home/vagrant
)

# configure the server to use this site
#for f in `find /etc/apache2/sites-enabled -type f` ; do a2dissite `basename $f` ; done

#for site in ${APACHE2_SITES} ; do a2ensite ${site} ; done
#for module in ${APACHE2_MODULES} ; do a2enmod ${module} ; done

# fix permissions for apache state-directories etc
#service apache2 stop
#chown -R vagrant:vagrant /var/log/apache2 /var/lock/apache2 /var/run/apache2 /var/cache/apache2

(
    cd /etc/nginx/sites-enabled

    rm -f default

    for host in /etc/nginx/sites-available/* ; do
        ln -sf ${host}
    done
)

mkdir -p /etc/nginx/ssl

#copy the commands
sudo cp /vagrant/configs/commands/cf_db_import_all /usr/bin/ && sudo chmod +x /usr/bin/cf_db_import_all
sudo cp /vagrant/configs/commands/cf_images_import_all /usr/bin/ && sudo chmod +x /usr/bin/cf_images_import_all
sudo cp /vagrant/configs/commands/cf_elasticsearch_reset /usr/bin/ && sudo chmod +x /usr/bin/cf_elasticsearch_reset
sudo cp /vagrant/configs/ssl/* /etc/nginx/ssl

#start ES automatically on boot
update-rc.d elasticsearch defaults 95 10

#Wordpress upload dir linked to portal media dir
ln -s /vagrant/cms/wp-content/uploads /vagrant/portal/web/media/cms
#EDMA upload dir linked to portal media dir
ln -s /vagrant/edma/public/upload_tmp /vagrant/portal/web/media/products

for svc in ${SERVICES} ; do echo ">>> restarting service ${svc}" ; service ${svc} restart ; done

mysql -u root -proot -e "CREATE DATABASE edma"
mysql -u root -proot -e "CREATE DATABASE wordpress"
mysql -u root -proot -e "CREATE DATABASE portal"
mysql -u root -proot -e "CREATE DATABASE old_wordpress"
mysql -u root -proot -e "CREATE DATABASE wordpress_chde"
mysql -u root -proot -e "CREATE DATABASE wordpress_chfr"
mysql -u root -proot wordpress < /vagrant/sql/cms_data.sql
mysql -u root -proot wordpress_chde < /vagrant/sql/cms_data.sql
mysql -u root -proot wordpress_chfr < /vagrant/sql/cms_data.sql
mysql -u root -proot edma < /vagrant/sql/edma_structure.sql
mysql -u root -proot edma < /vagrant/sql/edma_data.sql
mysql -u root -proot edma < /vagrant/sql/portal_structure.sql
mysql -u root -proot old_wordpress < /vagrant/sql/old_cms_data.sql


# Add colors for the bash script
(
    cd ~

    echo ' ' >> /home/vagrant/.bashrc
    echo 'export LSCOLORS=GxFxCxDxBxegedabagaced ' >> /home/vagrant/.bashrc
    echo ' ' >> /home/vagrant/.bashrc
    echo '# Make your own colors ' >> /home/vagrant/.bashrc
    echo 'export CLICOLOR=1 ' >> /home/vagrant/.bashrc
    echo ' ' >> /home/vagrant/.bashrc
    echo 'GRAYEDOUT="30m" ' >> /home/vagrant/.bashrc
    echo 'RED="31m" ' >> /home/vagrant/.bashrc
    echo 'GREEN="32m" ' >> /home/vagrant/.bashrc
    echo 'YELLOW="33m" ' >> /home/vagrant/.bashrc
    echo 'DARKBLUE="34m" ' >> /home/vagrant/.bashrc
    echo 'PURPLE="35m" ' >> /home/vagrant/.bashrc
    echo 'CYAN="36m" ' >> /home/vagrant/.bashrc
    echo 'WHITEBOLD="37m" ' >> /home/vagrant/.bashrc
    echo "export PS1=\"\[\033[01;\$GREEN\]\u@\[\033[01;\$CYAN\] \w \[\033[\$YELLOW\]\\\`ruby -e \\\"print  (%x{git branch 2> /dev/null}.each_line.grep(/^\*/).first ||  '').gsub(/^\* (.+)$/, '(\1) ')\\\"\\\`\\[\033[\$WHITEBOLD\]$\[\033[00m\] \" " >> /home/vagrant/.bashrc
    echo 'export LSCOLORS=GxFxCxDxBxegedabagaced ' >> /home/vagrant/.bashrc
    echo ' ' >> /home/vagrant/.bashrc
    echo 'cd /vagrant/ ' >> /home/vagrant/.bashrc
    echo ' ' >> /home/vagrant/.bashrc
)


# Add zsh to command line
sudo apt-get install zsh -yf
sudo apt-get install git-core -yf
 
wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh

chsh -s `which zsh`

varg
# Add php unit testing
sudo apt-get install phpunit -fy

# Add install tree
sudo apt-get install tree -fy

# Ag searcher 
sudo apt-get install -fy silversearcher-ag

# Add xdebug support ini file Ref: http://www.sitepoint.com/install-xdebug-phpstorm-vagrant/
(
    sudo touch /etc/php5/mods-available/xdebug.ini
    sudo echo '    ' >> /etc/php5/mods-available/xdebug.ini
    sudo echo 'xdebug.remote_enable = on' >> /etc/php5/mods-available/xdebug.ini
    sudo echo 'xdebug.remote_connect_back = on' >> /etc/php5/mods-available/xdebug.ini
    sudo echo 'xdebug.idekey = "vagrant"' >> /etc/php5/mods-available/xdebug.ini

)


echo "zu ende :)"

