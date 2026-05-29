#!/bin/bash
set -e

echo "Running Docker entrypoint..."

# Setup storage directories and permissions
/usr/local/bin/setup-storage.sh

# Create symlink for ldt-asset/storage (in case volume mount overwrites it)
mkdir -p /var/www/html/public/ldt-asset
if [ ! -L /var/www/html/public/ldt-asset/storage ]; then
    ln -sf /var/www/html/storage/app/public /var/www/html/public/ldt-asset/storage
    echo "Created symlink: /var/www/html/public/ldt-asset/storage"
fi

# Start Apache
exec apache2-foreground
