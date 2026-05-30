# Society Event - ScienceBank Platform
> Project Summary diperbarui: 2026-05-31 | Base commit: b5d16303

---

## 1. Overview Proyek

Aplikasi ini adalah platform manajemen event berbasis Laravel untuk **ScienceBank Society**. Platform mendukung dua layer: **Admin Panel** (back-office) dan **Web Publik** (front-office). Proyek diadaptasi dari sistem lama "Satu Data Pertahanan" dan sedang dalam proses rebranding penuh ke Society Event.

---

## 2. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.x / Laravel 10.x |
| Database | MySQL 8.0.30 |
| Frontend | Blade Template, jQuery, SweetAlert2, DataTables (Yajra) |
| Container | Docker (`Dockerfile` + `docker-compose.yml` + `docker-compose.prod.yml`) |
| Email | SMTP Gmail (configurable via admin panel) |
| Auth | Session-based login + OTP, bcrypt password |

---

## 3. Struktur Database (`society_event_db`)

### Tabel Sistem / Framework

| Tabel | Fungsi |
|---|---|
| `users` | Laravel default users (kosong, tidak dipakai aktif) |
| `cache` / `cache_locks` | Laravel cache |
| `jobs` / `job_batches` / `failed_jobs` | Laravel queue |
| `sessions` | Session aktif user |
| `migrations` | Riwayat migrasi |
| `password_reset_tokens` | Token reset password |

### Tabel Referensi / Master Data

| Tabel | Fungsi | Data Aktif |
|---|---|---|
| `reff_role` | Role user | Super Admin (SADM), Public (PUB) |
| `reff_menu` | Menu admin panel | Dashboard, Reference, Content Web |
| `reff_akses_menu` | Hak akses per role ke menu | Role 1 (SADM) akses penuh |
| `reff_topik` | Tags/topik konten | 1 data aktif: "Riset2" |
| `reff_organisasi` | Data organisasi | Kosong |
| `reff_status` | Konfigurasi status sistem | 23 status (menu, data, pengaduan, permohonan) |

### Tabel Aplikasi

| Tabel | Fungsi | Data Aktif |
|---|---|---|
| `app_user` | User aplikasi (admin + publik) | 3 user (2 Super Admin, 1 ex-Organisasi) |
| `app_email` | Konfigurasi SMTP | 1 konfigurasi Gmail |
| `app_setting` | Pengaturan tampilan website | 1 record (social media, gambar, deskripsi) |
| `app_slider` | Slider halaman web | Kosong (struktur minimal, perlu ALTER TABLE) |
| `app_log_aktivitas` | Log semua aksi admin | 37+ log entries |
| `t_sponsor` | Sponsor event | 2 sponsor: INTELLEGENT SCIENCE, BioNexus |

### Tabel Event (Inti)

| Tabel | Fungsi | Data Aktif |
|---|---|---|
| `t_event` | Data event utama | 2 event (ScienceBank Society - Bali, Jul 2026) |
| `t_event_kolaborasi` | Kolaborator per event | 2: Indonesia BPOM, Prof Taruna Ikrar |
| `t_event_paket` | Paket aktivitas event | 5 paket (Golf, Beach, Spa, Diving, Cultural Tour) |
| `t_event_paket_detail` | Detail item per paket | Kosong |
| `t_event_program` | Program/jadwal per hari | Kosong |
| `t_event_program_detail` | Detail sesi per program | Kosong |
| `t_event_paper` | Paper/submission peserta | Kosong (tabel sudah ada) |
| `t_event_registrasi` | Registrasi peserta event | Kosong (tabel sudah ada) |
| `t_event_timeline` | Timeline/milestone event | Kosong (tabel sudah ada) |
| `t_event_addon` | Add-on/tambahan event | Kosong (tabel sudah ada) |

---

## 4. Event Aktif

### Event 1: ScienceBank Society - Inagural President & Summit
- **Kode:** EV260529145400
- **Lokasi:** Bali Beach Convention Center
- **Tanggal:** 1 - 4 Juli 2026
- **Status:** Aktif (Y)
- **Paket Aktivitas:** Beach Activities, Spa & Traditional Massage, Diving Adventure, Bali Cultural Tour
- **Kolaborasi:** Indonesia BPOM, Prof Taruna Ikrar

### Event 2: ScienceBank Society2 (duplikat/testing)
- **Kode:** EV260529145401
- **Lokasi:** Bali Beach Convention Center
- **Tanggal:** 1 - 4 Juli 2026
- **Paket Aktivitas:** Golf Experience (di Bali National Golf Club)

---

## 5. Controllers yang Ada

### Admin Panel Controllers

| Controller | File | Fungsi |
|---|---|---|
| `LoginController` | `LoginController.php` | Login/OTP admin panel |
| `DashboardController` | `DashboardController.php` | Halaman dashboard admin |
| `ProfilController` | `ProfilController.php` | Update profil & ganti password admin |
| `ReffUserController` | `ReffUserController.php` | CRUD manajemen user |
| `ReffRoleController` | `ReffRoleController.php` | CRUD role dan hak akses menu |
| `ReffMenuController` | `ReffMenuController.php` | CRUD menu admin panel |
| `ReffTopikController` | `ReffTopikController.php` | CRUD tags/topik |
| `ReffSponsorController` | `ReffSponsorController.php` | CRUD sponsor event |
| `SettingController` | `SettingController.php` | Pengaturan tampilan website |
| `TautanController` | `TautanController.php` | Manajemen link/tautan |
| `LogController` | `LogController.php` | Lihat log aktivitas |
| `EventController` | `EventController.php` | CRUD event utama (50KB - lengkap) |
| `EventAddonController` | `EventAddonController.php` | CRUD add-on event |
| `EventPaperController` | `EventPaperController.php` | CRUD paper/submission event |
| `EventRegistrasiController` | `EventRegistrasiController.php` | CRUD registrasi peserta event |
| `EventTimelineController` | `EventTimelineController.php` | CRUD timeline event |

### Web Publik Controllers

| Controller | File | Fungsi |
|---|---|---|
| `WebHomeController` | `WebHomeController.php` | Halaman utama publik |
| `WebLoginController` | `WebLoginController.php` | Login/Register/OTP user publik |
| `WebDashboardController_` | `WebDashboardController_.php` | Dashboard user publik **(draft - nama file ada underscore)** |
| `WebDataController` | `WebDataController.php` | Tampil data/konten |
| `WebTopikController` | `WebTopikController.php` | Filter topik di web |
| `WebProfilController` | `WebProfilController.php` | Profil user publik |

---

## 6. Menu Admin Panel

```
Dashboards (Single)
Reference (Master)
  - Users
  - Role & Akses Menu
  - Menu
  - Topik
  - Sponsor
Event Management
  - Event (CRUD)
  - Registrasi Peserta
  - Paper Submission
  - Add-on
  - Timeline
Content Web (Master)
  - Settings
  - Slider
  - Tautan/Link
Logs
  - Log Aktivitas
```

---

## 7. Role & Akses

| Role | Kode | All Data | Keterangan |
|---|---|---|---|
| Super Admin | SADM | Y | Full access semua menu |
| Public | PUB | N | User terdaftar publik |

> Role "Organisasi" (ORG) sudah dihapus pada 2026-05-29.

---

## 8. Gap Analysis - Fitur Belum / Perlu Diperbaiki

| Fitur | Tabel DB | Controller | Status |
|---|---|---|---|
| CRUD Paket Event | `t_event_paket` | Ada di `EventController.php` | **Cek apakah sudah terhubung ke route** |
| CRUD Program/Jadwal | `t_event_program` | Ada di `EventController.php` | **Cek apakah sudah terhubung ke route** |
| CRUD Kolaborasi | `t_event_kolaborasi` | Ada di `EventController.php` | **Cek apakah sudah terhubung ke route** |
| Registrasi Peserta | `t_event_registrasi` | `EventRegistrasiController.php` | **Tabel + Controller SUDAH ADA** |
| Paper Submission | `t_event_paper` | `EventPaperController.php` | **Tabel + Controller SUDAH ADA** |
| Slider Web | `app_slider` | `SettingController.php` | **Perlu ALTER TABLE tambah kolom konten** |
| `WebDashboardController_` | - | `WebDashboardController_.php` | **Nama file perlu rename (hapus underscore)** |
| Halaman Paper Publik | - | Parsial di `WebDataController` | **Perlu dikembangkan lebih lanjut** |
| Data konten real | - | - | **Event masih pakai Lorem Ipsum** |
| App setting | `app_setting` | `SettingController.php` | **Masih ada konten "Satu Data Pertahanan"** |

---

## 9. Halaman Web Publik

Navigasi yang sudah ada di header:
- **About** - route `about`
- **Event** - route `events` (terhubung ke `WebHomeController`)
- **Paper** - route perlu dipastikan
- **Login / Register** - route `web-login`, `web-register`
- **User Dropdown:** Riwayat Permohonan, Profil Saya, Ganti Password

---

## 10. Catatan Penting

1. **SMTP credentials** tersimpan plaintext di DB (`app_email`) - pertimbangkan enkripsi.
2. `app_slider` hanya punya kolom `id_slider` - belum ada kolom konten (**perlu ALTER TABLE**).
3. `t_event_program` dan `t_event_program_detail` sudah ada strukturnya tapi **belum ada data** - siap diisi.
4. `WebDashboardController_.php` punya **underscore di nama file** - class tidak dapat di-autoload oleh Laravel. Perlu rename ke `WebDashboardController.php`.
5. Event data masih pakai **Lorem Ipsum** sebagai deskripsi - perlu diganti konten real.
6. `app_setting` masih menyimpan konten lama **"Satu Data Pertahanan"** - perlu di-update ke ScienceBank Society.
7. `EventController.php` berukuran **50KB** - sangat besar, pertimbangkan dipecah per domain (Paket, Program, Kolaborasi) agar maintainable.
8. Database dump tersedia di **`society_event_db.sql`** di root project - gunakan untuk setup fresh install.
