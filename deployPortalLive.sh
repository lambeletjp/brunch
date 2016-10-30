#!/usr/bin/env bash
git pull
/usr/bin/php5.5-cli composer.phar install
/usr/bin/php5.5-cli bin/console cache:clear
/usr/bin/php5.5-cli bin/console cache:clear -e prod
/usr/bin/php5.5-cli bin/console assets:install
/usr/bin/php5.5-cli bin/console fos:comment:installAces
printf "parameters: \n    assets_version: `shuf -i 0-1000000 -n 1` \n    deploy_timestamp: 0" > app/config/version.yml