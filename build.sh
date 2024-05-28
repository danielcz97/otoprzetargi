#!/bin/bash
set -e

# Install GD extension using pecl
pecl install gd

# Enable GD extension in PHP
docker-php-ext-enable gd

# Wykonaj npm run build
npm install
npm run build
