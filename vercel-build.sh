#!/usr/bin/env bash

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build assets
npm run build

# Generate optimized autoload files
php artisan config:cache
php artisan route:cache
php artisan view:cache
