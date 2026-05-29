# Laravel 12 Docker Setup - Satu Data Pertahanan

## 📋 Overview

Setup Docker untuk Laravel 12 dengan:
- PHP 8.4-Apache
- PostgreSQL (database eksternal)
- Composer auto-install
- Static assets serving via Apache
- HTTPS support dengan reverse proxy
- Route prefix `/ldt`

## 🏗️ Arsitektur

```
[Reverse Proxy (Nginx/Apache)]
         ↓ HTTPS
    Port 8080
         ↓
[Docker Container]
  - Apache + PHP 8.4
  - Laravel 12
  - Port 80 (internal)
         ↓
[PostgreSQL Database]
  - Host: 10.1.100.132
  - Port: 5432
```

## 📁 Struktur File Docker

```
.
├── Dockerfile                    # PHP 8.4-Apache image
├── docker-compose.yml            # Development setup
├── docker-compose.prod.yml       # Production setup
├── docker/
│   └── apache/
│       └── laravel.conf          # Apache virtual host
├── DEPLOYMENT-CHECKLIST.md       # Checklist deployment
├── verify-setup.sh               # Script verifikasi
└── DOCKER-README.md              # File ini
```

## 🚀 Quick Start

### Development

```bash
# Build dan run
docker compose up -d --build

# Lihat logs
docker logs -f laravel-app

# Akses aplikasi
http://localhost:8000/ldt
```

### Production

```bash
# 1. Build image
docker compose build

# 2. Save ke tar file
docker save -o laravel-app.tar sdi-app:latest

# 3. Transfer ke server
scp laravel-app.tar user@server:/path/

# 4. Di server - Load image
docker load -i laravel-app.tar

# 5. Setup .env file di server (lihat DEPLOYMENT-CHECKLIST.md)

# 6. Upload docker-compose.prod.yml

# 7. Run container
docker compose -f docker-compose.prod.yml up -d

# 8. Akses aplikasi
https://apps.syscloud.my.id/ldt
```

## 🔧 Konfigurasi

### Environment Variables (Production)

File `.env` di server harus berisi:

```env
APP_ENV=production
APP_DEBUG=false
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
CACHE_STORE=database
```

### Port Mapping

- **Development**: `8000:80` → http://localhost:8000
- **Production**: `8080:80` → http://server-ip:8080

### Reverse Proxy (Nginx)

Jika menggunakan Nginx di depan Docker:

```nginx
server {
    listen 443 ssl;
    server_name apps.syscloud.my.id;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

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
}
```

## 🛠️ Docker Commands

### Container Management

```bash
# Start container
docker compose up -d

# Stop container
docker compose down

# Restart container
docker compose restart

# View logs
docker logs -f laravel-app

# Masuk ke container
docker exec -it laravel-app bash

# Lihat status
docker ps
```

### Laravel Commands di Container

```bash
# Run migrations
docker exec laravel-app php artisan migrate --force

# Clear cache
docker exec laravel-app php artisan cache:clear
docker exec laravel-app php artisan config:clear
docker exec laravel-app php artisan route:clear
docker exec laravel-app php artisan view:clear

# Generate key
docker exec laravel-app php artisan key:generate

# Run queue worker
docker exec laravel-app php artisan queue:work

# Check routes
docker exec laravel-app php artisan route:list
```

## 🔍 Troubleshooting

### 1. Asset tidak load (404)

**Gejala**: File CSS/JS tidak load, error 404

**Solusi**:
```bash
# Cek ASSET_URL
docker exec laravel-app env | grep ASSET_URL

# Pastikan folder ada
docker exec laravel-app ls -la public/ldt-asset/

# Cek Apache logs
docker logs laravel-app
```

### 2. Session tidak work

**Gejala**: Login tidak persist, selalu redirect ke login

**Solusi**:
```bash
# Cek session driver
docker exec laravel-app env | grep SESSION_DRIVER

# Run migration untuk table sessions
docker exec laravel-app php artisan migrate --force

# Clear session
docker exec laravel-app php artisan session:table
```

### 3. HTTPS redirect loop

**Gejala**: Infinite redirect antara HTTP dan HTTPS

**Solusi**:
- Pastikan `APP_ENV=production` di .env
- Cek TrustProxies middleware active
- Pastikan reverse proxy mengirim header `X-Forwarded-Proto: https`

### 4. Database connection error

**Gejala**: SQLSTATE[08006] connection failed

**Solusi**:
```bash
# Test koneksi dari container
docker exec laravel-app ping -c 3 10.1.100.132

# Cek credentials
docker exec laravel-app env | grep DB_

# Test PostgreSQL connection
docker exec laravel-app php artisan tinker
# Di tinker: DB::connection()->getPdo();
```

### 5. Permission denied di storage

**Gejala**: Error writing to storage/logs

**Solusi**:
```bash
# Fix permissions
docker exec laravel-app chown -R www-data:www-data storage bootstrap/cache
docker exec laravel-app chmod -R 775 storage bootstrap/cache
```

## 📊 Monitoring

### Health Check

```bash
# Check if container is running
docker ps | grep laravel-app

# Check Apache status
docker exec laravel-app apache2ctl -t

# Check PHP version
docker exec laravel-app php -v

# Check disk usage
docker exec laravel-app df -h

# Check memory usage
docker stats laravel-app
```

### Logs

```bash
# Application logs
docker exec laravel-app tail -f storage/logs/laravel.log

# Apache access logs
docker exec laravel-app tail -f /var/log/apache2/access.log

# Apache error logs
docker exec laravel-app tail -f /var/log/apache2/error.log

# Container logs
docker logs -f laravel-app
```

## 🔐 Security

### Best Practices

1. **Environment Variables**: Jangan commit file `.env` ke git
2. **APP_KEY**: Generate unique key untuk production
3. **APP_DEBUG**: Set `false` di production
4. **Database Password**: Gunakan password yang kuat
5. **HTTPS**: Selalu gunakan HTTPS di production
6. **Firewall**: Batasi akses ke port 8080 hanya dari reverse proxy

### Update Dependencies

```bash
# Update composer dependencies
docker exec laravel-app composer update

# Rebuild image dengan dependencies terbaru
docker compose build --no-cache
```

## 📝 Maintenance

### Backup

```bash
# Backup database (dari server database)
pg_dump -h 10.1.100.132 -U postgres satu_data_db > backup.sql

# Backup uploaded files (jika ada di container)
docker cp laravel-app:/var/www/html/storage/app ./backup-storage
```

### Update Application

```bash
# 1. Pull latest code
git pull origin main

# 2. Rebuild image
docker compose build

# 3. Save new image
docker save -o laravel-app-v2.tar sdi-app:latest

# 4. Transfer ke server dan load

# 5. Stop old container
docker compose -f docker-compose.prod.yml down

# 6. Start new container
docker compose -f docker-compose.prod.yml up -d

# 7. Run migrations
docker exec laravel-app php artisan migrate --force
```

## 📞 Support

Untuk masalah atau pertanyaan:
1. Cek DEPLOYMENT-CHECKLIST.md
2. Cek logs: `docker logs laravel-app`
3. Cek Laravel logs: `docker exec laravel-app tail storage/logs/laravel.log`

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Docker Documentation](https://docs.docker.com/)
- [Apache Documentation](https://httpd.apache.org/docs/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
