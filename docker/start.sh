#!/bin/sh
set -e

cd /var/www/html

# Run Laravel setup
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start services via supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
