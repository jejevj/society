# Deployment Checklist - Laravel 12 dengan Docker

## ✅ Konfigurasi yang Sudah Diperbaiki

### 1. Docker Setup
- ✅ Dockerfile menggunakan PHP 8.4-Apache
- ✅ Apache mod_rewrite dan headers enabled
- ✅ Document root di `/var/www/html/public`
- ✅ PostgreSQL extensions (pdo_pgsql, pgsql) installed
- ✅ Composer dependencies auto-install saat build
- ✅ Nano editor included

### 2. Routing & Prefix
- ✅ Semua routes menggunakan prefix `ldt`
- ✅ APP_ROUTE environment variable dikonfigurasi
- ✅ APP_URL dan ASSET_URL dikonfigurasi untuk production

### 3. HTTPS & Proxy
- ✅ TrustProxies middleware dikonfigurasi untuk trust semua proxies
- ✅ AppServiceProvider force HTTPS scheme untuk production
- ✅ Middleware registered di bootstrap/app.php

### 4. Static Assets
- ✅ Apache akan serve static files dari `public/ldt-asset/`
- ✅ ASSET_URL dikonfigurasi: `https://apps.syscloud.my.id/ldt-asset/`
- ✅ .htaccess sudah benar untuk Laravel routing

### 5. Controllers & Routes
- ✅ Semua 27 controllers ada dan terdaftar
- ✅ Semua routes terdefinisi dengan benar
- ✅ Tidak ada missing controller

## ⚠️ Yang Perlu Dilakukan di Server

### 1. Environment Variables (.env di server)
```env
APP_NAME='Satu Data Pertahanan'
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:jnVxnfjwUK/2xCz1g70f9XdYsM+00R8VuKa/+yTksLU=
APP_URL=https://apps.syscloud.my.id
ASSET_URL=https://apps.syscloud.my.id/ldt-asset/
APP_ROUTE=ldt

DB_CONNECTION=pgsql
DB_HOST=10.1.100.132
DB_PORT=5432
DB_DATABASE=satu_data_db
DB_USERNAME=postgres
DB_PASSWORD=qwert12345!

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Storage SFTP
STORAGE_HOST=10.1.100.134
STORAGE_PORT=22
STORAGE_USERNAME=cap
STORAGE_PASSWORD=qwert12345!
STORAGE_PATH=/home/cap

STORAGE_HOST_TERBUKA=10.1.100.133
STORAGE_PORT_TERBUKA=22
STORAGE_USERNAME_TERBUKA=cap
STORAGE_PASSWORD_TERBUKA=qwert12345!
STORAGE_PATH_TERBUKA=/home/cap

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=work.basrilhafi@gmail.com
MAIL_PASSWORD=fmwvtodportjulmx
MAIL_FROM_ADDRESS=work.basrilhafi@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Database Migrations
Sebelum running container, pastikan database sudah ada dan migrations sudah dijalankan:
```bash
# Di container atau sebelum deploy
php artisan migrate --force
```

### 3. Storage Permissions
Pastikan folder storage dan bootstrap/cache writable:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Reverse Proxy (Nginx/Apache di depan Docker)
Jika menggunakan reverse proxy, pastikan konfigurasi:
```nginx
location /ldt {
    proxy_pass http://localhost:8080;
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Port $server_port;
}

location /ldt-asset {
    proxy_pass http://localhost:8080/ldt-asset;
    proxy_set_header Host $host;
}
```

## 🚀 Deployment Steps

### Development (Local)
```bash
# Build dan run
docker compose up -d --build

# Akses di http://localhost:8000/ldt
```

### Production (Server)
```bash
# 1. Build image di development
docker compose build

# 2. Save image ke tar
docker save -o laravel-app.tar sdi-app:latest

# 3. Transfer ke server
scp laravel-app.tar user@server:/path/

# 4. Di server, load image
docker load -i laravel-app.tar

# 5. Buat file .env di server dengan konfigurasi production

# 6. Upload docker-compose.prod.yml ke server

# 7. Run container
docker compose -f docker-compose.prod.yml up -d

# 8. Cek logs
docker logs -f laravel-app

# 9. Akses di https://apps.syscloud.my.id/ldt
```

## 🔍 Troubleshooting

### Jika asset tidak load:
1. Cek ASSET_URL di environment
2. Pastikan folder `public/ldt-asset/` ada di image
3. Cek Apache logs: `docker logs laravel-app`

### Jika session tidak work:
1. Pastikan table `sessions` ada di database
2. Run migration: `docker exec laravel-app php artisan migrate`
3. Cek SESSION_DRIVER=database di .env

### Jika HTTPS redirect loop:
1. Pastikan TrustProxies middleware active
2. Cek X-Forwarded-Proto header dari reverse proxy
3. Set APP_ENV=production

### Jika 404 untuk semua routes:
1. Cek Apache mod_rewrite enabled
2. Cek .htaccess ada di public/
3. Cek document root di Apache config

## 📝 Files Modified/Created

1. `Dockerfile` - PHP 8.4-Apache dengan PostgreSQL
2. `docker-compose.yml` - Development setup
3. `docker-compose.prod.yml` - Production setup
4. `docker/apache/laravel.conf` - Apache virtual host config
5. `app/Http/Middleware/TrustProxies.php` - Trust proxy headers
6. `bootstrap/app.php` - Register TrustProxies middleware
7. `DEPLOYMENT-CHECKLIST.md` - This file

## ✅ Pre-deployment Verification

Sebelum deploy, pastikan:
- [ ] Database accessible dari container
- [ ] APP_KEY sudah di-generate
- [ ] Migrations sudah dijalankan
- [ ] Storage folders writable
- [ ] SFTP storage accessible
- [ ] SMTP mail configuration tested
- [ ] Reverse proxy configured (jika ada)
- [ ] SSL certificate valid
- [ ] Port 8080 available di server
