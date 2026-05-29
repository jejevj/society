#!/bin/bash

echo "========================================="
echo "Laravel Route Diagnosis Tool"
echo "========================================="
echo ""

CONTAINER_NAME="laravel-app"

# Check if container is running
if ! docker ps | grep -q $CONTAINER_NAME; then
    echo "❌ Container '$CONTAINER_NAME' is not running!"
    echo "Start it with: docker compose up -d"
    exit 1
fi

echo "✓ Container is running"
echo ""

# Check Apache modules
echo "========================================="
echo "1. Checking Apache Modules"
echo "========================================="
echo ""

echo "Checking mod_rewrite..."
if docker exec $CONTAINER_NAME apache2ctl -M 2>/dev/null | grep -q rewrite; then
    echo "  ✓ mod_rewrite is enabled"
else
    echo "  ❌ mod_rewrite is NOT enabled"
    echo "  Fix: docker exec $CONTAINER_NAME a2enmod rewrite && docker exec $CONTAINER_NAME apache2ctl restart"
fi

echo "Checking mod_headers..."
if docker exec $CONTAINER_NAME apache2ctl -M 2>/dev/null | grep -q headers; then
    echo "  ✓ mod_headers is enabled"
else
    echo "  ❌ mod_headers is NOT enabled"
    echo "  Fix: docker exec $CONTAINER_NAME a2enmod headers && docker exec $CONTAINER_NAME apache2ctl restart"
fi
echo ""

# Check .htaccess
echo "========================================="
echo "2. Checking .htaccess"
echo "========================================="
echo ""

if docker exec $CONTAINER_NAME test -f /var/www/html/public/.htaccess; then
    echo "  ✓ .htaccess exists"
    
    if docker exec $CONTAINER_NAME grep -q "RewriteEngine On" /var/www/html/public/.htaccess; then
        echo "  ✓ RewriteEngine is On"
    else
        echo "  ❌ RewriteEngine is not On"
    fi
else
    echo "  ❌ .htaccess does NOT exist"
fi
echo ""

# Check AllowOverride
echo "========================================="
echo "3. Checking Apache AllowOverride"
echo "========================================="
echo ""

if docker exec $CONTAINER_NAME grep -q "AllowOverride All" /etc/apache2/sites-enabled/000-default.conf; then
    echo "  ✓ AllowOverride All is set"
else
    echo "  ❌ AllowOverride is not set to All"
fi
echo ""

# Check routes
echo "========================================="
echo "4. Checking Laravel Routes"
echo "========================================="
echo ""

echo "Total routes:"
route_count=$(docker exec $CONTAINER_NAME php artisan route:list 2>/dev/null | grep -c "ldt/")
echo "  Routes with 'ldt' prefix: $route_count"

if [ $route_count -gt 0 ]; then
    echo "  ✓ Routes are registered"
else
    echo "  ❌ No routes found with 'ldt' prefix"
fi
echo ""

echo "Sample routes:"
docker exec $CONTAINER_NAME php artisan route:list 2>/dev/null | grep "ldt/" | head -5
echo ""

# Check environment
echo "========================================="
echo "5. Checking Environment Variables"
echo "========================================="
echo ""

APP_URL=$(docker exec $CONTAINER_NAME printenv APP_URL 2>/dev/null)
ASSET_URL=$(docker exec $CONTAINER_NAME printenv ASSET_URL 2>/dev/null)
APP_ROUTE=$(docker exec $CONTAINER_NAME printenv APP_ROUTE 2>/dev/null)
APP_ENV=$(docker exec $CONTAINER_NAME printenv APP_ENV 2>/dev/null)

echo "  APP_URL: $APP_URL"
echo "  ASSET_URL: $ASSET_URL"
echo "  APP_ROUTE: $APP_ROUTE"
echo "  APP_ENV: $APP_ENV"
echo ""

# Check permissions
echo "========================================="
echo "6. Checking Permissions"
echo "========================================="
echo ""

storage_perm=$(docker exec $CONTAINER_NAME stat -c "%a" /var/www/html/storage 2>/dev/null)
public_perm=$(docker exec $CONTAINER_NAME stat -c "%a" /var/www/html/public 2>/dev/null)

echo "  storage/ permissions: $storage_perm"
echo "  public/ permissions: $public_perm"

if [ "$storage_perm" -ge "755" ]; then
    echo "  ✓ storage/ is writable"
else
    echo "  ❌ storage/ may not be writable"
fi
echo ""

# Test routes
echo "========================================="
echo "7. Testing Routes (Internal)"
echo "========================================="
echo ""

echo "Testing: /ldt/beranda"
response=$(docker exec $CONTAINER_NAME curl -s -o /dev/null -w "%{http_code}" http://localhost/ldt/beranda 2>/dev/null)
if [ "$response" = "200" ]; then
    echo "  ✓ /ldt/beranda returns 200 OK"
elif [ "$response" = "302" ]; then
    echo "  ⚠ /ldt/beranda returns 302 (redirect)"
else
    echo "  ❌ /ldt/beranda returns $response"
fi

echo "Testing: /ldt/login"
response=$(docker exec $CONTAINER_NAME curl -s -o /dev/null -w "%{http_code}" http://localhost/ldt/login 2>/dev/null)
if [ "$response" = "200" ]; then
    echo "  ✓ /ldt/login returns 200 OK"
elif [ "$response" = "302" ]; then
    echo "  ⚠ /ldt/login returns 302 (redirect)"
else
    echo "  ❌ /ldt/login returns $response"
fi

echo "Testing: /ldt/list"
response=$(docker exec $CONTAINER_NAME curl -s -o /dev/null -w "%{http_code}" http://localhost/ldt/list 2>/dev/null)
if [ "$response" = "200" ]; then
    echo "  ✓ /ldt/list returns 200 OK"
elif [ "$response" = "302" ]; then
    echo "  ⚠ /ldt/list returns 302 (redirect)"
else
    echo "  ❌ /ldt/list returns $response"
fi
echo ""

# Check logs for errors
echo "========================================="
echo "8. Recent Errors (Last 10 lines)"
echo "========================================="
echo ""

echo "Apache Error Log:"
docker exec $CONTAINER_NAME tail -10 /var/log/apache2/error.log 2>/dev/null | grep -i error || echo "  No recent errors"
echo ""

echo "Laravel Log:"
if docker exec $CONTAINER_NAME test -f /var/www/html/storage/logs/laravel.log; then
    docker exec $CONTAINER_NAME tail -10 /var/www/html/storage/logs/laravel.log 2>/dev/null | grep -i error || echo "  No recent errors"
else
    echo "  No Laravel log file found"
fi
echo ""

# Summary
echo "========================================="
echo "Summary & Recommendations"
echo "========================================="
echo ""

issues=0

# Check critical issues
if ! docker exec $CONTAINER_NAME apache2ctl -M 2>/dev/null | grep -q rewrite; then
    echo "❌ CRITICAL: mod_rewrite is not enabled"
    echo "   Fix: docker exec $CONTAINER_NAME a2enmod rewrite && docker compose restart"
    issues=$((issues+1))
fi

if [ $route_count -eq 0 ]; then
    echo "❌ CRITICAL: No routes found"
    echo "   Fix: Check routes/web.php and ensure Route::prefix('ldt') exists"
    issues=$((issues+1))
fi

if [ -z "$APP_URL" ]; then
    echo "⚠ WARNING: APP_URL is not set"
    echo "   Fix: Set APP_URL in .env or docker-compose.yml"
    issues=$((issues+1))
fi

if [ $issues -eq 0 ]; then
    echo "✓ No critical issues found!"
    echo ""
    echo "If routes still don't work, check:"
    echo "  1. Reverse proxy configuration (if any)"
    echo "  2. Firewall rules"
    echo "  3. DNS resolution"
    echo "  4. SSL certificate"
else
    echo ""
    echo "Found $issues issue(s) that need attention."
fi

echo ""
echo "========================================="
echo "For more help, see: ROUTE-TROUBLESHOOTING.md"
echo "========================================="
