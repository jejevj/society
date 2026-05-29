# Quick Start - Satu Data Pertahanan Docker

## Build Image (Sudah Selesai ✅)

```bash
docker build -t satu-data-pertahanan:1.0.0 .
docker tag satu-data-pertahanan:1.0.0 satu-data-pertahanan:latest
```

Image sudah berhasil dibuild dengan ukuran ~880MB.

## Running Image

### Cara Paling Mudah - Langsung Running
```bash
docker run -d -p 8080:80 --name satu-data-app satu-data-pertahanan:1.0.0
```

Akses: http://localhost:8080

### Dengan Docker Compose (Include PostgreSQL)
```bash
docker-compose up -d
```

Akses: http://localhost:8080

## Push ke Registry

### Docker Hub
```bash
# Login dulu
docker login

# Tag dengan username Anda
docker tag satu-data-pertahanan:1.0.0 YOUR_USERNAME/satu-data-pertahanan:1.0.0
docker tag satu-data-pertahanan:1.0.0 YOUR_USERNAME/satu-data-pertahanan:latest

# Push
docker push YOUR_USERNAME/satu-data-pertahanan:1.0.0
docker push YOUR_USERNAME/satu-data-pertahanan:latest
```

### Private Registry
```bash
# Tag dengan registry URL
docker tag satu-data-pertahanan:1.0.0 registry.example.com/satu-data-pertahanan:1.0.0
docker tag satu-data-pertahanan:1.0.0 registry.example.com/satu-data-pertahanan:latest

# Push
docker push registry.example.com/satu-data-pertahanan:1.0.0
docker push registry.example.com/satu-data-pertahanan:latest
```

## Deploy di Server Lain

```bash
# Pull image
docker pull YOUR_USERNAME/satu-data-pertahanan:1.0.0

# Run
docker run -d -p 80:80 --name satu-data-app YOUR_USERNAME/satu-data-pertahanan:1.0.0
```

## Management Commands

```bash
# Lihat logs
docker logs -f satu-data-app

# Stop container
docker stop satu-data-app

# Start container
docker start satu-data-app

# Restart container
docker restart satu-data-app

# Hapus container
docker rm -f satu-data-app

# Masuk ke container
docker exec -it satu-data-app bash

# Run artisan command
docker exec satu-data-app php artisan migrate
docker exec satu-data-app php artisan cache:clear
```

## Fitur Image

✅ **Ready to Use** - Tidak perlu konfigurasi tambahan
✅ **All-in-One** - Nginx + PHP-FPM + Queue Worker
✅ **.env Included** - Semua konfigurasi sudah ada di dalam image
✅ **Auto Migration** - Database otomatis di-migrate saat start
✅ **Auto Optimize** - Config, route, view otomatis di-cache
✅ **Health Check** - Endpoint `/health` untuk monitoring

## Troubleshooting

### Lihat apa yang terjadi di dalam container
```bash
docker logs satu-data-app
```

### Container tidak bisa start
```bash
# Lihat error
docker logs satu-data-app

# Coba start ulang
docker restart satu-data-app
```

### Butuh akses database external
```bash
docker run -d -p 8080:80 \
  -e DB_HOST=your-db-host \
  -e DB_PASSWORD=your-password \
  satu-data-pertahanan:1.0.0
```

## Image Info

- **Repository**: satu-data-pertahanan
- **Tags**: 1.0.0, latest
- **Size**: ~880MB
- **Base**: PHP 8.2 FPM Alpine
- **Includes**: Nginx, PostgreSQL client, GD, ZIP, dan semua dependencies Laravel
