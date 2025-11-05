#!/bin/bash

echo "🚀 Starting Railway deployment..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install

# Build assets with Vite
echo "🏗️ Building assets with Vite..."
npm run build

# Clear any existing cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Create storage link if it doesn't exist
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Run database migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Cache config for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
