#!/bin/bash

# Change to the /var/www/html directory
cd /var/www/html

# Run composer install and composer dump-autoload
composer install --no-interaction --no-dev --prefer-dist
composer dump-autoload --optimize --no-dev --classmap-authoritative

# Check the Nginx installation
if [ -f /usr/local/nginx/sbin/nginx ]; then
    echo "Nginx is installed at /usr/local/nginx/sbin/nginx"
else
    echo "Nginx is not installed at /usr/local/nginx/sbin/nginx"
    exit 1
fi

# Create /var/log/nginx/ directory
mkdir -p /var/log/nginx/

# Create access.log and error.log file
touch /var/log/nginx/access.log
touch /var/log/nginx/error.log

# Start PHP-FPM services
php-fpm -D

# Start nginx in the foreground
/usr/local/nginx/sbin/nginx -g 'daemon off;'
