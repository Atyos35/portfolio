#!/bin/bash

set -e

echo "Construction des services Docker..."
docker compose build --pull --no-cache

echo "Lancement des services Docker..."
docker compose -f compose.yaml up -d --wait

echo "Installation des dépendances PHP..."
composer install

echo "Création et migration de la base de données..."
php bin/console doctrine:database:create || true
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load -n

echo "Configuration de l'environnement de test..."
php bin/console doctrine:database:create --env=test || true
php bin/console doctrine:schema:create --env=test
php bin/console doctrine:fixtures:load --env=test -n

echo "Installation des dépendances Node.js..."
npm install

echo "Build des assets..."
npm run build

echo "Lancement du serveur PHP local..."
php -S localhost:8000 -t public