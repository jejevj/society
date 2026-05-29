# Fix: Routes Tidak Bisa Diakses

## Problem Statement

Setelah deploy, beberapa atau semua routes tidak bisa diakses (404 Not Found).

---

## Quick Diagnosis

Jalankan script diagnosis:
```bash
bash diagnose-routes.sh
```

Script ini akan check:
- Apache modules (mod_rewrite, mod_headers)
- .htaccess configuration
- Laravel routes
- Environment variables
- Permissions
- Test beberapa routes

---

## Common Causes & Solutions

### 1. mod_rewrite Tidak Aktif

**Symptoms**: Semua routes return 404 kecuali `/`

**Check**:
```bash
docker exec laravel-app apache2ctl -M | grep rewrite
```

**Fix**:
```bash
docker exec laravel-app a2enmod rewrite
docker exec laravel-app apache2ctl restart
```

Atau rebuild image (sudah include di Dockerfile):
```bash
docker compose build --no-cache
docker compose up -d
```

---

### 2. AllowOverride Tidak Diset

**Symptoms**: .htaccess tidak dibaca, routes 404

**Check**:
```bash
docker exec laravel-app cat /etc/apache2/sites-enabled/000-default.conf | grep AllowOverride
```

**Fix**: Sudah diset di `docker/apache/laravel.conf`:
```apache
<Directory /var/www/html/public>
    AllowOverride All
</Directory>
```

Jika belum, rebuild:
```bash
docker compose build --no-cache
docker compose up -d
```

---

### 3. Reverse Proxy Path Issues

**Symptoms**: Routes work di `http://localhost:8080/ldt/beranda` tapi tidak di `https://apps.syscloud.my.id/ldt/beranda`

**Cause**: Reverse proxy tidak forward path dengan benar

**Solution A - Nginx**:
```nginx
server {
    listen 443 ssl;
    server_name apps.syscloud.my.id;

    # SSL config...

    # Important: trailing slash matters!
    location /ldt/ {
        proxy_pass http://localhost:8080/ldt/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
    }

    # Static assets
    location /ldt-asset/ {
        proxy_pass http://localhost:8080/ldt-asset/;
        proxy_set_header Host $host;
        proxy_cache_valid 200 1d;
        expires 1d;
    }
}
```

**Solution B - Apache**:
```apache
<VirtualHost *:443>
    ServerName apps.syscloud.my.id
    
    # SSL config...

    ProxyPreserveHost On
    ProxyRequests Off

    <Location /ldt>
        ProxyPass http://localhost:8080/ldt
        ProxyPassReverse http://localhost:8080/ldt
        RequestHeader set X-Forwarded-Proto "https"
        RequestHeader set X-Forwarded-Port "443"
    </Location>

    <Location /ldt-asset>
        ProxyPass http://localhost:8080/ldt-asset
        ProxyPassReverse http://localhost:8080/ldt-asset
    </Location>
</VirtualHost>
```

**Test**:
```bash
# Test langsung ke container (bypass proxy)
curl -I http://server-ip:8080/ldt/beranda

# Test melalui proxy
curl -I https://apps.syscloud.my.id/ldt/beranda

# Compare responses
```

---

### 4. .htaccess Issues

**Symptoms**: Beberapa routes work, beberapa tidak

**Solution**: Gunakan .htaccess yang sudah diperbaiki

File `public/.htaccess` sudah diupdate dengan:
- `RewriteBase /` untuk subfolder support
- Better header handling
- HTTPS detection

Jika masih bermasalah, coba alternative:
```bash
# Backup current
docker exec laravel-app cp /var/www/html/public/.htaccess /var/www/html/public/.htaccess.backup

# Copy alternative
docker cp public/.htaccess.alternative laravel-app:/var/www/html/public/.htaccess

# Test
curl -I http://localhost:8080/ldt/beranda
```

---

### 5. Routes dengan Special Characters

**Symptoms**: Routes dengan `/` atau special chars dalam parameter tidak work

**Example**: `/ldt/preview/{sifat}/{file}` dimana `{file}` bisa berisi `/`

**Solution**: Sudah ditambahkan di `docker/apache/laravel.conf`:
```apache
# Allow encoded slashes in URLs
AllowEncodedSlashes NoDecode
```

Dan di routes, gunakan `where()`:
```php
Route::get('/preview/{sifat}/{file}', [Controller::class, 'method'])
    ->where('file', '.*');
```

Rebuild jika belum:
```bash
docker compose build --no-cache
docker compose up -d
```

---

### 6. CSRF Token Issues (POST Routes)

**Symptoms**: POST routes return 419 atau 404

**Check**: Form harus punya CSRF token
```blade
<form method="POST" action="{{ route('route-name') }}">
    @csrf
    <!-- form fields -->
</form>
```

**For AJAX**:
```javascript
// Add to <head>
<meta name="csrf-token" content="{{ csrf_token() }}">

// In AJAX request
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    // ...
});
```

---

### 7. Session Issues

**Symptoms**: Login redirect loop, session tidak persist

**Check**:
```bash
# Cek session driver
docker exec laravel-app env | grep SESSION_DRIVER

# Cek table sessions ada
docker exec laravel-app php artisan tinker
# Di tinker:
DB::table('sessions')->count();
```

**Fix**:
```bash
# Run migration
docker exec laravel-app php artisan migrate --force

# Clear session
docker exec laravel-app php artisan session:table
docker exec laravel-app php artisan migrate --force
```

---

## Step-by-Step Troubleshooting

### Step 1: Verify Container is Running

```bash
docker ps | grep laravel-app
```

If not running:
```bash
docker compose up -d
docker logs -f laravel-app
```

### Step 2: Check Apache is Working

```bash
# Test root
curl -I http://localhost:8080/

# Should return 200 or 302
```

### Step 3: Check Routes are Registered

```bash
docker exec laravel-app php artisan route:list | grep ldt
```

Should show all routes with `/ldt` prefix.

### Step 4: Test Route Internally

```bash
# Test from inside container
docker exec laravel-app curl -I http://localhost/ldt/beranda

# Should return 200 or 302
```

### Step 5: Test Route Externally

```bash
# Test direct to container
curl -I http://server-ip:8080/ldt/beranda

# Test through proxy
curl -I https://apps.syscloud.my.id/ldt/beranda
```

### Step 6: Check Logs

```bash
# Apache error log
docker exec laravel-app tail -50 /var/log/apache2/error.log

# Apache access log
docker exec laravel-app tail -50 /var/log/apache2/access.log

# Laravel log
docker exec laravel-app tail -50 storage/logs/laravel.log
```

### Step 7: Enable Debug Mode (Temporary)

```bash
# Edit environment
docker exec -it laravel-app nano .env

# Change
APP_DEBUG=true

# Restart
docker compose restart

# Access route in browser to see detailed error

# IMPORTANT: Set back to false after debugging!
APP_DEBUG=false
```

---

## Complete Fix Procedure

Jika semua routes tidak work, ikuti langkah ini:

### 1. Rebuild Image dengan Fix Terbaru

```bash
# Stop container
docker compose down

# Rebuild (no cache)
docker compose build --no-cache

# Start
docker compose up -d

# Check logs
docker logs -f laravel-app
```

### 2. Verify Apache Configuration

```bash
# Check mod_rewrite
docker exec laravel-app apache2ctl -M | grep rewrite

# Check config syntax
docker exec laravel-app apache2ctl -t

# Should output: Syntax OK
```

### 3. Verify .htaccess

```bash
# Check file exists
docker exec laravel-app cat /var/www/html/public/.htaccess

# Should contain RewriteEngine On
```

### 4. Test Routes

```bash
# Run diagnosis script
bash diagnose-routes.sh

# Or manual test
docker exec laravel-app curl -I http://localhost/ldt/beranda
```

### 5. Fix Reverse Proxy (if applicable)

Update Nginx/Apache reverse proxy config dengan contoh di atas.

### 6. Clear All Caches

```bash
docker exec laravel-app php artisan cache:clear
docker exec laravel-app php artisan config:clear
docker exec laravel-app php artisan route:clear
docker exec laravel-app php artisan view:clear
```

### 7. Restart Everything

```bash
# Restart container
docker compose restart

# If using reverse proxy, restart it too
sudo systemctl restart nginx
# or
sudo systemctl restart apache2
```

---

## Verification

After fixes, verify:

```bash
# 1. Check diagnosis
bash diagnose-routes.sh

# 2. Test key routes
curl -I https://apps.syscloud.my.id/ldt/beranda
curl -I https://apps.syscloud.my.id/ldt/login
curl -I https://apps.syscloud.my.id/ldt/list

# 3. Test in browser
# Open: https://apps.syscloud.my.id/ldt

# 4. Check browser console for errors
# F12 -> Console tab

# 5. Test POST route (login form)
# Try to login and check if it works
```

---

## Prevention

Untuk mencegah masalah ini di future deployments:

1. **Always test locally first**:
   ```bash
   docker compose up -d
   curl -I http://localhost:8000/ldt/beranda
   ```

2. **Use diagnosis script before deploy**:
   ```bash
   bash diagnose-routes.sh
   ```

3. **Document reverse proxy config**: Save Nginx/Apache config in repo

4. **Test after each change**: Don't change multiple things at once

5. **Keep logs**: Monitor logs during deployment
   ```bash
   docker logs -f laravel-app
   ```

---

## Still Not Working?

If routes still don't work after all fixes:

1. **Collect information**:
   ```bash
   # Save diagnosis output
   bash diagnose-routes.sh > diagnosis.txt
   
   # Save route list
   docker exec laravel-app php artisan route:list > routes.txt
   
   # Save logs
   docker exec laravel-app tail -100 /var/log/apache2/error.log > apache-error.log
   docker exec laravel-app tail -100 storage/logs/laravel.log > laravel.log
   
   # Save configs
   docker exec laravel-app cat /etc/apache2/sites-enabled/000-default.conf > apache.conf
   docker exec laravel-app cat /var/www/html/public/.htaccess > htaccess.txt
   ```

2. **Test specific problematic route**:
   ```bash
   # Replace with your problematic route
   curl -v http://localhost:8080/ldt/your-route
   ```

3. **Check if it's a Laravel issue**:
   ```bash
   # Access Laravel directly (bypass Apache rewrite)
   docker exec laravel-app curl -I http://localhost/index.php/ldt/beranda
   
   # If this works, it's an Apache rewrite issue
   # If this doesn't work, it's a Laravel routing issue
   ```

4. **Share the collected information** for further troubleshooting

---

## Files Modified

- ✅ `public/.htaccess` - Added RewriteBase and better handling
- ✅ `docker/apache/laravel.conf` - Added AllowEncodedSlashes
- ✅ `diagnose-routes.sh` - Diagnosis script
- ✅ `public/.htaccess.alternative` - Alternative .htaccess
- ✅ `ROUTE-TROUBLESHOOTING.md` - Detailed troubleshooting guide
- ✅ `FIX-ROUTES-NOT-ACCESSIBLE.md` - This file
