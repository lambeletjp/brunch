#!/usr/bin/env bash
php composer.phar install
php bin/console cache:clear
php bin/console cache:clear -e prod
php bin/console assets:install
php bin/console fos:comment:installAces
printf "parameters: \n    assets_version: `shuf -i 0-1000000 -n 1` \n    deploy_timestamp: 0" > app/config/version.yml