# Route Troubleshooting Guide

## Masalah: Route Tidak Bisa Diakses

### Kemungkinan Penyebab

1. **Reverse Proxy Configuration**
2. **Apache Rewrite Rules**
3. **Laravel Route Prefix**
4. **Missing Middleware**

---

## Diagnosis

### 1. Cek Route yang Tidak Bisa Diakses

Jalankan di container:
```bash
docker exec laravel-app php artisan route:list
```

Cari route yang bermasalah dan catat:
- URI
- Method (GET/POST)
- Name
- Action (Controller)

### 2. Cek Apache Access Log

```bash
docker exec laravel-app tail -f /var/log/apache2/access.log
```

Akses route yang bermasalah dan lihat:
- Status code (200, 404, 500, dll)
- Request path yang diterima Apache

### 3. Cek Apache Error Log

```bash
docker exec laravel-app tail -f /var/log/apache2/error.log
```

### 4. Cek Laravel Log

```bash
docker exec laravel-app tail -f storage/logs/laravel.log
```

### 5. Test Rewrite Rules

```bash
# Masuk ke container
docker exec -it laravel-app bash

# Test apakah mod_rewrite aktif
apache2ctl -M | grep rewrite

# Test .htaccess
cat /var/www/html/public/.htaccess
```

---

## Solusi Berdasarkan Skenario

### Skenario 1: Semua Route 404

**Penyebab**: mod_rewrite tidak aktif atau .htaccess tidak dibaca

**Solusi**:
```bash
# Masuk ke container
docker exec -it laravel-app bash

# Enable mod_rewrite
a2enmod rewrite

# Restart Apache
apache2ctl restart

# Cek AllowOverride
grep -r "AllowOverride" /etc/apache2/
```

### Skenario 2: Route Tertentu 404 (dengan prefix /ldt)

**Penyebab**: Reverse proxy tidak forward path dengan benar

**Contoh URL Bermasalah**:
- ❌ `https://apps.syscloud.my.id/ldt/ldt/beranda` (double prefix)
- ✅ `https://apps.syscloud.my.id/ldt/beranda` (correct)

**Solusi - Nginx Reverse Proxy**:
```nginx
location /ldt/ {
    proxy_pass http://localhost:8080/ldt/;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Port $server_port;
}
```

**Solusi - Apache Reverse Proxy**:
```apache
<Location /ldt>
    ProxyPass http://localhost:8080/ldt
    ProxyPassReverse http://localhost:8080/ldt
    ProxyPreserveHost On
    RequestHeader set X-Forwarded-Proto "https"
    RequestHeader set X-Forwarded-Port "443"
</Location>
```

### Skenario 3: POST Routes 404 atau 405

**Penyebab**: CSRF token atau method tidak diizinkan

**Solusi**:
```bash
# Cek apakah form punya CSRF token
# Di blade template harus ada:
@csrf

# Atau di AJAX:
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

### Skenario 4: Route dengan Parameter 404

**Contoh**: `/ldt/detail-data/{url_data}` atau `/ldt/preview/{sifat}/{file}`

**Penyebab**: Encoded slashes atau special characters

**Solusi di Apache**:
```apache
# Sudah ditambahkan di laravel.conf
AllowEncodedSlashes NoDecode
```

**Solusi di .htaccess**:
```apache
# Sudah ditambahkan
RewriteBase /
```

### Skenario 5: Static Assets 404

**Contoh**: `/ldt-asset/assets/js/dashboard.js`

**Penyebab**: Path tidak benar atau folder tidak ada

**Solusi**:
```bash
# Cek folder ada
docker exec laravel-app ls -la /var/www/html/public/ldt-asset/

# Cek ASSET_URL
docker exec laravel-app env | grep ASSET_URL

# Seharusnya:
ASSET_URL=https://apps.syscloud.my.id/ldt-asset/
```

---

## Testing Routes

### Test dari Dalam Container

```bash
# Masuk ke container
docker exec -it laravel-app bash

# Test route list
php artisan route:list | grep beranda

# Test dengan curl (internal)
curl -I http://localhost/ldt/beranda

# Test dengan curl (dengan host header)
curl -I -H "Host: apps.syscloud.my.id" http://localhost/ldt/beranda
```

### Test dari Luar Container

```bash
# Test langsung ke container (bypass reverse proxy)
curl -I http://server-ip:8080/ldt/beranda

# Test melalui reverse proxy
curl -I https://apps.syscloud.my.id/ldt/beranda

# Test dengan verbose
curl -v https://apps.syscloud.my.id/ldt/beranda
```

---

## Debug Mode

### Enable Apache Rewrite Logging

Edit `docker/apache/laravel.conf`:
```apache
<Directory /var/www/html/public>
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Require all granted
    
    # Enable rewrite logging
    LogLevel alert rewrite:trace3
</Directory>
```

Rebuild dan restart:
```bash
docker compose build
docker compose up -d
docker logs -f laravel-app
```

### Enable Laravel Debug Mode (Temporary)

```bash
# Edit .env di server
docker exec -it laravel-app nano .env

# Set
APP_DEBUG=true

# Restart container
docker compose restart

# JANGAN LUPA set kembali ke false setelah debug!
```

---

## Common Issues & Quick Fixes

### Issue 1: "404 Not Found" untuk semua routes

```bash
# Quick fix
docker exec laravel-app a2enmod rewrite
docker exec laravel-app apache2ctl restart
```

### Issue 2: "403 Forbidden"

```bash
# Fix permissions
docker exec laravel-app chown -R www-data:www-data /var/www/html
docker exec laravel-app chmod -R 755 /var/www/html/public
```

### Issue 3: "500 Internal Server Error"

```bash
# Cek Laravel log
docker exec laravel-app tail -50 storage/logs/laravel.log

# Cek Apache error log
docker exec laravel-app tail -50 /var/log/apache2/error.log

# Clear cache
docker exec laravel-app php artisan cache:clear
docker exec laravel-app php artisan config:clear
docker exec laravel-app php artisan route:clear
```

### Issue 4: Routes bekerja tanpa prefix tapi tidak dengan prefix

**Masalah**: `http://localhost:8080/beranda` works, tapi `http://localhost:8080/ldt/beranda` tidak

**Penyebab**: Route prefix tidak match dengan URL

**Solusi**:
```bash
# Cek routes
docker exec laravel-app php artisan route:list | grep beranda

# Seharusnya muncul:
# GET|HEAD  ldt/beranda  beranda  App\Http\Controllers\WebDashboardController@index

# Jika tidak ada prefix 'ldt', cek routes/web.php
docker exec laravel-app cat routes/web.php | grep "prefix"
```

---

## Verification Checklist

Setelah fix, verifikasi:

- [ ] `docker exec laravel-app apache2ctl -M | grep rewrite` → mod_rewrite loaded
- [ ] `docker exec laravel-app php artisan route:list` → semua routes ada
- [ ] `curl -I http://localhost:8080/ldt/beranda` → 200 OK
- [ ] `curl -I https://apps.syscloud.my.id/ldt/beranda` → 200 OK
- [ ] Browser test → halaman load dengan benar
- [ ] Static assets load → CSS/JS tidak 404
- [ ] POST routes work → form submission berhasil
- [ ] Routes dengan parameter work → detail pages accessible

---

## Contact & Support

Jika masih ada masalah:

1. Kumpulkan informasi:
   ```bash
   # Route list
   docker exec laravel-app php artisan route:list > routes.txt
   
   # Apache config
   docker exec laravel-app cat /etc/apache2/sites-enabled/000-default.conf > apache.conf
   
   # Logs
   docker exec laravel-app tail -100 /var/log/apache2/error.log > apache-error.log
   docker exec laravel-app tail -100 storage/logs/laravel.log > laravel.log
   ```

2. Test specific route:
   ```bash
   # Ganti dengan route yang bermasalah
   curl -v http://localhost:8080/ldt/your-problematic-route
   ```

3. Share informasi di atas untuk troubleshooting lebih lanjut
