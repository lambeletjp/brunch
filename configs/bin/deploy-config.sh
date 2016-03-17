#!/bin/bash

## copies the system configuration and restarts all services

# must be executed with root-privileges
if [ `id -u` -ne 0 ] ; then sudo $0 $@ ; exit $? ; fi

APACHE2_SITES="default curved.dev cms.curved.dev edma.curved.dev"
APACHE2_MODULES="pagespeed"

SERVICES="apache2 nginx varnish php5-fpm memcached elasticsearch mysql"

# copy over all customized configuration-files
(
    cd /vagrant/vagrant
    for d in etc root home ; do
        for f in `find $d -type f` ; do
            d=`dirname "${f}"`

            [[ -d "$d" ]] || mkdir -p "${d}"
            
            cp -v "${f}" "/${f}"
        done
    done

    chown -R vagrant:vagrant /home/vagrant
)

# configure the server to use this site
for f in `find /etc/apache2/sites-enabled -type f` ; do a2dissite `basename $f` ; done

for site in ${APACHE2_SITES} ; do a2ensite ${site} ; done
for module in ${APACHE2_MODULES} ; do a2enmod ${module} ; done

# fix permissions for apache state-directories etc
service apache2 stop
chown -R vagrant:vagrant /var/log/apache2 /var/lock/apache2 /var/run/apache2 /var/cache/apache2

(
    cd /etc/nginx/sites-enabled

    rm -f default

    for host in curved.dev cms.curved.dev edma.curved.dev ; do
        ln -sf ../sites-available/${host}
    done
)


for svc in ${SERVICES} ; do echo ">>> restarting service ${svc}" ; service ${svc} restart ; done
