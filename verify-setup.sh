#!/bin/bash

echo "========================================="
echo "Laravel Docker Setup Verification"
echo "========================================="
echo ""

# Check if required files exist
echo "✓ Checking required files..."
files=("Dockerfile" "docker-compose.yml" "docker-compose.prod.yml" "public/.htaccess" "docker/apache/laravel.conf")
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✓ $file exists"
    else
        echo "  ✗ $file missing"
        exit 1
    fi
done
echo ""

# Check if all controllers exist
echo "✓ Checking controllers..."
controllers=(
    "Controller.php"
    "DashboardController.php"
    "DataController.php"
    "LogController.php"
    "LoginController.php"
    "PengaduanController.php"
    "PermohonanController.php"
    "ProfilController.php"
    "RabbitMQController.php"
    "ReffMenuController.php"
    "ReffOrganisasiController.php"
    "ReffRoleController.php"
    "ReffTopikController.php"
    "ReffUserController.php"
    "ReportController.php"
    "SettingController.php"
    "SurveyController.php"
    "TautanController.php"
    "ValidasiController.php"
    "WebDashboardController.php"
    "WebDataController.php"
    "WebHubungiController.php"
    "WebLoginController.php"
    "WebMonitoringController.php"
    "WebOrganisasiController.php"
    "WebProfilController.php"
    "WebTentangController.php"
)

for controller in "${controllers[@]}"; do
    if [ -f "app/Http/Controllers/$controller" ]; then
        echo "  ✓ $controller exists"
    else
        echo "  ✗ $controller missing"
        exit 1
    fi
done
echo ""

# Check if public/ldt-asset exists
echo "✓ Checking static assets..."
if [ -d "public/ldt-asset" ]; then
    echo "  ✓ public/ldt-asset directory exists"
    asset_count=$(find public/ldt-asset -type f | wc -l)
    echo "  ✓ Found $asset_count asset files"
else
    echo "  ✗ public/ldt-asset directory missing"
    exit 1
fi
echo ""

# Check composer.json
echo "✓ Checking composer.json..."
if [ -f "composer.json" ]; then
    echo "  ✓ composer.json exists"
    if grep -q '"laravel/framework": "\^12.0"' composer.json; then
        echo "  ✓ Laravel 12 detected"
    fi
    if grep -q '"php": "\^8.2"' composer.json; then
        echo "  ✓ PHP 8.2+ required"
    fi
else
    echo "  ✗ composer.json missing"
    exit 1
fi
echo ""

# Check routes
echo "✓ Checking routes..."
if [ -f "routes/web.php" ]; then
    echo "  ✓ routes/web.php exists"
    if grep -q "Route::prefix('ldt')" routes/web.php; then
        echo "  ✓ Route prefix 'ldt' configured"
    else
        echo "  ✗ Route prefix 'ldt' not found"
        exit 1
    fi
else
    echo "  ✗ routes/web.php missing"
    exit 1
fi
echo ""

# Check middleware
echo "✓ Checking middleware..."
if [ -f "app/Http/Middleware/TrustProxies.php" ]; then
    echo "  ✓ TrustProxies middleware exists"
    if grep -q "protected \$proxies = '\*';" app/Http/Middleware/TrustProxies.php; then
        echo "  ✓ TrustProxies configured to trust all proxies"
    fi
else
    echo "  ✗ TrustProxies middleware missing"
    exit 1
fi
echo ""

# Check AppServiceProvider
echo "✓ Checking AppServiceProvider..."
if [ -f "app/Providers/AppServiceProvider.php" ]; then
    echo "  ✓ AppServiceProvider exists"
    if grep -q "URL::forceScheme('https')" app/Providers/AppServiceProvider.php; then
        echo "  ✓ Force HTTPS configured for production"
    fi
else
    echo "  ✗ AppServiceProvider missing"
    exit 1
fi
echo ""

echo "========================================="
echo "✓ All checks passed!"
echo "========================================="
echo ""
echo "Ready to build Docker image:"
echo "  docker compose build"
echo ""
echo "Or for production:"
echo "  docker compose build"
echo "  docker save -o laravel-app.tar sdi-app:latest"
echo ""
