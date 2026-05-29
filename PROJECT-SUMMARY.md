# Society Event - ScienceBank Platform
> Project Summary generated: 2026-05-29

---

## 1. Overview Proyek

Aplikasi ini adalah platform manajemen event berbasis Laravel untuk **ScienceBank Society**. Platform mendukung dua layer: **Admin Panel** (back-office) dan **Web Publik** (front-office). Proyek diadaptasi dari sistem lama "Satu Data Pertahanan" dan sedang dalam proses rebranding penuh ke Society Event.

---

## 2. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP / Laravel |
| Database | MySQL 8.0.30 |
| Frontend | Blade Template, jQuery, SweetAlert2, DataTables |
| Container | Docker (Dockerfile + docker-compose.prod.yml) |
| Email | SMTP Gmail (configurable via admin panel) |
| Auth | OTP-based login, bcrypt password |

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
| `app_slider` | Slider halaman web | Kosong (struktur minimal) |
| `app_log_aktivitas` | Log semua aksi admin | 37 log entries |
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

| Controller | Fungsi |
|---|---|
| `LoginController` | Login/OTP admin panel |
| `DashboardController` | Halaman dashboard admin |
| `ProfilController` | Update profil & ganti password admin |
| `ReffUserController` | CRUD manajemen user |
| `ReffRoleController` | CRUD role dan hak akses menu |
| `ReffMenuController` | CRUD menu admin panel |
| `ReffTopikController` | CRUD tags/topik |
| `ReffSponsorController` | CRUD sponsor event |
| `SettingController` | Pengaturan tampilan website |
| `TautanController` | Manajemen link/tautan |
| `LogController` | Lihat log aktivitas |

### Web Publik Controllers

| Controller | Fungsi |
|---|---|
| `WebHomeController` | Halaman utama publik |
| `WebLoginController` | Login/Register/OTP user publik |
| `WebDashboardController_` | Dashboard user publik (in-progress) |
| `WebDataController` | Tampil data/konten |
| `WebTopikController` | Filter topik di web |
| `WebProfilController` | Profil user publik |

---

## 6. Menu Admin Panel

```
Dashboards (Single)
Reference (Master)
  - Users
  - Sponsor
  - Tags
Content Web (Master)
  - Settings
  - Link
```

---

## 7. Role & Akses

| Role | Kode | All Data | Keterangan |
|---|---|---|---|
| Super Admin | SADM | Y | Full access semua menu |
| Public | PUB | N | User terdaftar publik |

> Role "Organisasi" (ORG) sudah dihapus pada 2026-05-29.

---

## 8. Gap Analysis - Fitur Belum Ada

Berdasarkan tabel yang sudah dibuat di DB vs controller yang ada:

| Fitur | Tabel DB | Controller | Status |
|---|---|---|---|
| CRUD Event | `t_event` | BELUM ADA | **Perlu dibuat** |
| CRUD Paket Event | `t_event_paket` | BELUM ADA | **Perlu dibuat** |
| CRUD Program/Jadwal | `t_event_program` | BELUM ADA | **Perlu dibuat** |
| CRUD Kolaborasi | `t_event_kolaborasi` | BELUM ADA | **Perlu dibuat** |
| Registrasi Peserta | Tabel belum ada | BELUM ADA | **Perlu tabel + controller** |
| Submission Paper | Tabel belum ada | BELUM ADA | **Perlu tabel + controller** |
| Halaman Event Publik | - | Partial (WebHomeController) | **Perlu dikembangkan** |
| Halaman Paper Publik | - | BELUM ADA | **Perlu dibuat** |

---

## 9. Halaman Web Publik (dari header-v2)

Navigasi yang sudah ada di header:
- **About** - route `about`
- **Event** - route perlu diperbaiki
- **Paper** - route perlu diperbaiki
- **Login / Register**
- **User Dropdown:** Riwayat Permohonan, Profil Saya, Ganti Password

---

## 10. Catatan Penting

1. **SMTP credentials** tersimpan plaintext di DB (`app_email`) - pertimbangkan enkripsi.
2. `app_slider` hanya punya kolom `id_slider` - belum ada kolom konten (perlu ALTER TABLE).
3. `t_event_program` dan `t_event_program_detail` sudah ada strukturnya tapi **belum ada data** - siap diisi.
4. `WebDashboardController_.php` punya underscore di nama file - kemungkinan masih draft/belum aktif di routing.
5. Event data masih pakai **Lorem Ipsum** sebagai deskripsi - perlu diganti konten real.
6. `app_setting` masih menyimpan konten lama **"Satu Data Pertahanan"** - perlu di-update ke ScienceBank Society.
