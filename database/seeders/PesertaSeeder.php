<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_paper')->whereIn('kode_registrasi', [
            'REG-2025-0001','REG-2025-0002','REG-2025-0003',
            'REG-2025-0004','REG-2025-0005','REG-2025-0006',
            'REG-2025-0007',
        ])->delete();
        DB::table('t_event_registrasi')->whereIn('kode_registrasi', [
            'REG-2025-0001','REG-2025-0002','REG-2025-0003',
            'REG-2025-0004','REG-2025-0005','REG-2025-0006',
            'REG-2025-0007',
        ])->delete();
        DB::table('app_user')->whereIn('id_user', [10,11,12,13,14,15,16])->delete();

        $password = Hash::make('password');
        $now      = now();

        DB::table('app_user')->insert([
            ['id_user'=>10,'role_id'=>4,'nama_user'=>'Andi Pratama',     'username_user'=>'andipratama',          'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0001','pekerjaan_user'=>'Mahasiswa', 'alamat_user'=>'Bandung, Jawa Barat',        'organisasi_user'=>'Universitas Padjadjaran',            'nationality_user'=>'Indonesian','job_title_user'=>'Graduate Researcher',       'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>11,'role_id'=>4,'nama_user'=>'Budi Santoso',     'username_user'=>'budisantoso',          'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0002','pekerjaan_user'=>'Peneliti',  'alamat_user'=>'Jakarta Selatan',            'organisasi_user'=>'LIPI',                              'nationality_user'=>'Indonesian','job_title_user'=>'Senior Research Scientist', 'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>12,'role_id'=>4,'nama_user'=>'Citra Dewi',       'username_user'=>'citradewi',            'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0003','pekerjaan_user'=>'Dosen',     'alamat_user'=>'Yogyakarta',                 'organisasi_user'=>'Universitas Gadjah Mada',            'nationality_user'=>'Indonesian','job_title_user'=>'Lecturer & Researcher',    'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>13,'role_id'=>4,'nama_user'=>'Dian Kurniawan',   'username_user'=>'diankurniawan',        'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0004','pekerjaan_user'=>'Mahasiswa', 'alamat_user'=>'Surabaya, Jawa Timur',       'organisasi_user'=>'Institut Teknologi Sepuluh Nopember','nationality_user'=>'Indonesian','job_title_user'=>'Doctoral Student',          'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>14,'role_id'=>4,'nama_user'=>'Eka Fitriani',     'username_user'=>'ekafitriani',          'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0005','pekerjaan_user'=>'Peneliti',  'alamat_user'=>'Makassar, Sulawesi Selatan', 'organisasi_user'=>'Universitas Hasanuddin',             'nationality_user'=>'Indonesian','job_title_user'=>'Research Associate',       'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>15,'role_id'=>4,'nama_user'=>'Fajar Nugroho',    'username_user'=>'fajarnugroho',         'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0006','pekerjaan_user'=>'Praktisi',  'alamat_user'=>'Semarang, Jawa Tengah',      'organisasi_user'=>'PT Teknologi Nusantara',             'nationality_user'=>'Indonesian','job_title_user'=>'Chief Technology Officer',  'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            // User untuk testing: wijaya.angga.j@gmail.com
            ['id_user'=>16,'role_id'=>4,'nama_user'=>'J Angga Wijaya',   'username_user'=>'wijaya.angga.j@gmail.com','password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'+62 812-3456-0007','pekerjaan_user'=>'Developer', 'alamat_user'=>'Jakarta',                    'organisasi_user'=>'Society App',                        'nationality_user'=>'Indonesian','job_title_user'=>'Software Developer',       'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
        ]);

        DB::table('t_event_registrasi')->insert([
            ['kode_registrasi'=>'REG-2025-0001','kode_event'=>'EV260529145400','nama_peserta'=>'Andi Pratama',     'email_peserta'=>'andi.pratama@example.com',         'instansi_peserta'=>'Universitas Padjadjaran',            'no_hp_peserta'=>'+62 812-3456-0001','status_registrasi'=>'A','catatan_registrasi'=>'Registrasi diterima, dokumen lengkap.',        'created_by_registrasi'=>'andipratama',          'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0002','kode_event'=>'EV260529145400','nama_peserta'=>'Budi Santoso',     'email_peserta'=>'budi.santoso@example.com',         'instansi_peserta'=>'LIPI',                              'no_hp_peserta'=>'+62 812-3456-0002','status_registrasi'=>'A','catatan_registrasi'=>'Registrasi diterima, peneliti aktif.',         'created_by_registrasi'=>'budisantoso',          'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0003','kode_event'=>'EV260529145400','nama_peserta'=>'Citra Dewi',       'email_peserta'=>'citra.dewi@example.com',           'instansi_peserta'=>'Universitas Gadjah Mada',           'no_hp_peserta'=>'+62 812-3456-0003','status_registrasi'=>'R','catatan_registrasi'=>'Dokumen tidak lengkap, silakan submit ulang.', 'created_by_registrasi'=>'citradewi',            'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0004','kode_event'=>'EV260529145400','nama_peserta'=>'Dian Kurniawan',   'email_peserta'=>'dian.kurniawan@example.com',       'instansi_peserta'=>'Institut Teknologi Sepuluh Nopember','no_hp_peserta'=>'+62 812-3456-0004','status_registrasi'=>'R','catatan_registrasi'=>'Kuota event telah penuh untuk kategori ini.', 'created_by_registrasi'=>'diankurniawan',        'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0005','kode_event'=>'EV260529145400','nama_peserta'=>'Eka Fitriani',     'email_peserta'=>'eka.fitriani@example.com',         'instansi_peserta'=>'Universitas Hasanuddin',            'no_hp_peserta'=>'+62 812-3456-0005','status_registrasi'=>'P','catatan_registrasi'=>null,                                            'created_by_registrasi'=>'ekafitriani',          'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0006','kode_event'=>'EV260529145400','nama_peserta'=>'Fajar Nugroho',    'email_peserta'=>'fajar.nugroho@example.com',        'instansi_peserta'=>'PT Teknologi Nusantara',            'no_hp_peserta'=>'+62 812-3456-0006','status_registrasi'=>'P','catatan_registrasi'=>null,                                            'created_by_registrasi'=>'fajarnugroho',         'created_at'=>$now,'updated_at'=>$now],
            // Registrasi wijaya.angga.j@gmail.com ke event berbayar EV260529145400
            ['kode_registrasi'=>'REG-2025-0007','kode_event'=>'EV260529145400','nama_peserta'=>'J Angga Wijaya',   'email_peserta'=>'wijaya.angga.j@gmail.com',         'instansi_peserta'=>'Society App',                       'no_hp_peserta'=>'+62 812-3456-0007','status_registrasi'=>'A','catatan_registrasi'=>'Registrasi diterima sebagai akun testing.',    'created_by_registrasi'=>'wijaya.angga.j@gmail.com','created_at'=>$now,'updated_at'=>$now],
        ]);

        DB::table('t_paper')->insert([
            ['kode_paper'=>'PAP-2025-0001','kode_registrasi'=>'REG-2025-0001','kode_event'=>'EV260529145400','judul_paper'=>'Implementasi Machine Learning untuk Deteksi Penyakit Tanaman Padi',        'deskripsi_paper'=>'Model CNN untuk mendeteksi penyakit tanaman padi secara real-time menggunakan dataset citra daun dari lahan pertanian di Jawa Barat dengan akurasi 94.7%.','file_paper'=>null,'tipe_file_paper'=>'pdf', 'status_paper'=>'A','catatan_paper'=>'Paper diterima, presentasi dijadwalkan sesi pagi.',    'created_by_paper'=>'andipratama','created_at'=>$now,'updated_at'=>$now],
            ['kode_paper'=>'PAP-2025-0002','kode_registrasi'=>'REG-2025-0001','kode_event'=>'EV260529145400','judul_paper'=>'Optimasi Algoritma Gradient Boosting pada Dataset Tabular Medis',         'deskripsi_paper'=>'Studi komparatif XGBoost, LightGBM, dan CatBoost pada dataset medis publik untuk prediksi risiko diabetes dan hipertensi dengan evaluasi AUC-ROC dan F1-Score.','file_paper'=>null,'tipe_file_paper'=>'pptx','status_paper'=>'P','catatan_paper'=>null,                                                                 'created_by_paper'=>'andipratama','created_at'=>$now,'updated_at'=>$now],
            ['kode_paper'=>'PAP-2025-0003','kode_registrasi'=>'REG-2025-0002','kode_event'=>'EV260529145400','judul_paper'=>'Analisis Dampak Digitalisasi UMKM terhadap Produktivitas Ekonomi Daerah', 'deskripsi_paper'=>'Data panel 34 provinsi Indonesia 2018-2023 dengan Fixed Effect Model untuk mengukur elastisitas digitalisasi UMKM terhadap PDRB per kapita.',               'file_paper'=>null,'tipe_file_paper'=>'pdf', 'status_paper'=>'A','catatan_paper'=>'Paper sangat relevan, diterima untuk sesi pleno.',       'created_by_paper'=>'budisantoso','created_at'=>$now,'updated_at'=>$now],
            ['kode_paper'=>'PAP-2025-0004','kode_registrasi'=>'REG-2025-0002','kode_event'=>'EV260529145400','judul_paper'=>'Model Prediktif Kualitas Air Sungai Berbasis IoT dan Deep Learning',      'deskripsi_paper'=>'Sistem monitoring kualitas air real-time menggunakan sensor IoT dengan model LSTM untuk prediksi indeks kualitas air 24 jam ke depan pada Sungai Citarum.',   'file_paper'=>null,'tipe_file_paper'=>'pdf', 'status_paper'=>'R','catatan_paper'=>'Metodologi kurang detail, silakan revisi dan submit ulang.','created_by_paper'=>'budisantoso','created_at'=>$now,'updated_at'=>$now],
        ]);
    }
}
