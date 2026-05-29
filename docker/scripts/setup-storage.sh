#!/bin/bash

echo "Setting up storage directories..."

# Create storage directories
mkdir -p /var/www/html/storage/app/public/image_setting
mkdir -p /var/www/html/storage/app/public/slider
mkdir -p /var/www/html/storage/app/public/organisasi
mkdir -p /var/www/html/storage/app/public/data
mkdir -p /var/www/html/storage/app/public/infografis
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

echo "Setting permissions..."

# Set ownership to www-data
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Set permissions
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Ensure www-data can write
chmod -R g+w /var/www/html/storage
chmod -R g+w /var/www/html/bootstrap/cache

echo "Storage setup completed!"
