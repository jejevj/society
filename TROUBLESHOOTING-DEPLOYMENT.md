# Troubleshooting Deployment

## Problem: Connection Refused saat curl localhost

### Cek Status Container
```bash
docker ps
```

Pastikan container STATUS adalah "Up" dan PORTS menunjukkan mapping yang benar.

### Cek Port Mapping
```bash
docker port <container-name>
```

Contoh output yang benar:
```
80/tcp -> 0.0.0.0:8000
```

### Cek Logs Container
```bash
docker logs <container-name>
```

Pastikan tidak ada error dan muncul:
```
Application is ready!
nginx entered RUNNING state
php-fpm entered RUNNING state
```

### Solusi: Pastikan Port Mapping Benar

Container expose port 80 (internal), harus di-map ke port host.

#### Jika running di port 8000:
```bash
docker run -d -p 8000:80 --name satu-data-app satu-data-pertahanan:1.0.0
```

#### Jika running di port 80:
```bash
docker run -d -p 80:80 --name satu-data-app satu-data-pertahanan:1.0.0
```

#### Jika running di port 8080:
```bash
docker run -d -p 8080:80 --name satu-data-app satu-data-pertahanan:1.0.0
```

### Test Akses

#### Test health endpoint:
```bash
curl http://localhost:8000/health
```

Harus return: `healthy`

#### Test aplikasi dengan route prefix:
```bash
# Jika APP_ROUTE=ldt di .env
curl http://localhost:8000/ldt/

# Atau tanpa prefix
curl http://localhost:8000/
```

### Jika Masih Connection Refused

1. **Cek container benar-benar running:**
```bash
docker ps | grep satu-data
```

2. **Cek nginx dan php-fpm running di dalam container:**
```bash
docker exec <container-name> supervisorctl status
```

Output harus:
```
nginx                            RUNNING   pid 33, uptime 0:05:00
php-fpm                          RUNNING   pid 34, uptime 0:05:00
laravel-queue:laravel-queue_00   STOPPED   Not started
```

3. **Cek nginx listening di port 80:**
```bash
docker exec <container-name> netstat -tlnp | grep 80
```

4. **Test dari dalam container:**
```bash
docker exec <container-name> curl http://localhost/health
```

Jika ini berhasil tapi dari host gagal, berarti masalah di port mapping.

5. **Restart container dengan port mapping yang benar:**
```bash
# Stop dan hapus container lama
docker stop satu-data-app
docker rm satu-data-app

# Running ulang dengan port yang benar
docker run -d -p 8000:80 --name satu-data-app satu-data-pertahanan:1.0.0
```

### Untuk HAProxy Backend

Jika menggunakan HAProxy, pastikan backend config mengarah ke:
```
server app1 <IP_MESIN>:8000 check
```

Bukan:
```
server app1 <IP_MESIN>:80 check  # SALAH jika container di-map ke 8000
```

### Quick Fix Commands

```bash
# Stop container
docker stop satu-data-app

# Hapus container
docker rm satu-data-app

# Running ulang dengan port 8000
docker run -d -p 8000:80 --name satu-data-app satu-data-pertahanan:1.0.0

# Test
curl http://localhost:8000/health

# Lihat logs
docker logs -f satu-data-app
```

### Verifikasi Lengkap

```bash
# 1. Cek container running
docker ps | grep satu-data

# 2. Cek port mapping
docker port satu-data-app

# 3. Cek services di dalam container
docker exec satu-data-app supervisorctl status

# 4. Test dari dalam container
docker exec satu-data-app curl http://localhost/health

# 5. Test dari host
curl http://localhost:8000/health

# 6. Test aplikasi
curl http://localhost:8000/ldt/
```

Semua test harus berhasil sebelum HAProxy bisa akses.
