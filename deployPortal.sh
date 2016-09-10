#!/usr/bin/env bash
php composer.phar install
php bin/console cache:clear
php bin/console cache:clear -e prod
php bin/console assets:install