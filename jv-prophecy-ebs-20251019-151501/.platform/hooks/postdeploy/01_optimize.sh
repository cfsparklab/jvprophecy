#!/bin/bash
# Post-deployment optimization script

cd /var/app/current

# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Optimize composer autoloader
composer dump-autoload --optimize

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R webapp:webapp storage bootstrap/cache

echo "Post-deployment optimization completed successfully!"

