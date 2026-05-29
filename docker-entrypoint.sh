#!/bin/bash
set -e

echo "Starting Satu Data Pertahanan..."

# Ensure storage directories exist with correct permissions
echo "Setting up storage directories..."
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Create storage symlink
if [ ! -L /var/www/html/public/storage ]; then
    ln -sf /var/www/html/storage/app/public /var/www/html/public/storage
fi

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for database to be ready (if DB_HOST is set)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at $DB_HOST:${DB_PORT:-5432}..."
    timeout=60
    while ! nc -z $DB_HOST ${DB_PORT:-5432} 2>/dev/null; do
        timeout=$((timeout - 1))
        if [ $timeout -le 0 ]; then
            echo "Warning: Database not ready after 60 seconds, continuing anyway..."
            break
        fi
        sleep 1
    done
    if [ $timeout -gt 0 ]; then
        echo "Database is ready!"
    fi
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations automatically
echo "Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "Migration skipped (database might not be ready)"

# Cache configuration for better performance
echo "Optimizing application..."
php artisan config:cache 2>/dev/null || echo "Config cache skipped"
php artisan route:cache 2>/dev/null || echo "Route cache skipped"
php artisan view:cache 2>/dev/null || echo "View cache skipped"

# Create storage link
php artisan storage:link 2>/dev/null || echo "Storage link already exists"

echo "Application is ready!"
echo "========================================"

# Start supervisor
exec "$@"
