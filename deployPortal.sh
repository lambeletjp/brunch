#!/usr/bin/env bash
composer install
php bin/console cache:clear
php bin/console cache:clear -e prod
php bin/console assets:install