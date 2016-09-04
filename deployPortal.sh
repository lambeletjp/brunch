#!/usr/bin/env bash
composer install
php app/console cache:clear
php app/console cache:clear -e prod
php app/console assets:install