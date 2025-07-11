#!/bin/bash

php bin/console d:s:c --no-interaction
php bin/console d:d:c --env=test --no-interaction
php bin/console d:s:c --env=test --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
php bin/console doctrine:fixtures:load --env=test --no-interaction

php -S 0.0.0.0:8000 -t public