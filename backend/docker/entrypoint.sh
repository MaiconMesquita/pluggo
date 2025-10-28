#!/bin/sh

echo "Script executed from: ${PWD}"
rm -rf ./vendor && rm -rf ./composer.lock
composer install --ignore-platform-reqs
php -S 0.0.0.0:8080 -t src src/index.php &
sleep 5
php ./src/local-aws-settings.php
