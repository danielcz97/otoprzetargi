#!/bin/bash
set -e

# Aktualizacja listy pakietów i instalacja GD
apt-get update
apt-get install -y libgd-dev

docker-php-ext-install gd

# Wykonaj npm run build
npm install
npm run build
