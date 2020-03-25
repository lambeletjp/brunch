#!/usr/bin/env bash
git pull
cd portal
/usr/bin/php7.3-cli ../composer.phar install
/usr/bin/php7.3-cli  bin/console cache:clear
/usr/bin/php7.3-cli  bin/console cache:clear -e prod
/usr/bin/php7.3-cli  bin/console assets:install
/usr/bin/php7.3-cli  bin/console doctrine:schema:update --force
