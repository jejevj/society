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
        ])->delete();
        DB::table('t_event_registrasi')->whereIn('kode_registrasi', [
            'REG-2025-0001','REG-2025-0002','REG-2025-0003',
            'REG-2025-0004','REG-2025-0005','REG-2025-0006',
        ])->delete();
        DB::table('app_user')->whereIn('id_user', [10,11,12,13,14,15])->delete();

        $password = Hash::make('password');
        $now      = now();

        // ── 6 akun peserta (role_id = 4) ─────────────────────────────────────
        DB::table('app_user')->insert([
            ['id_user'=>10,'role_id'=>4,'nama_user'=>'Andi Pratama',   'username_user'=>'andi.pratama@example.com',   'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'081234560001','pekerjaan_user'=>'Mahasiswa', 'alamat_user'=>'Bandung',    'organisasi_user'=>'Universitas Padjadjaran','verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>11,'role_id'=>4,'nama_user'=>'Budi Santoso',   'username_user'=>'budi.santoso@example.com',   'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'081234560002','pekerjaan_user'=>'Peneliti',  'alamat_user'=>'Jakarta',    'organisasi_user'=>'LIPI',                   'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>12,'role_id'=>4,'nama_user'=>'Citra Dewi',     'username_user'=>'citra.dewi@example.com',     'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'081234560003','pekerjaan_user'=>'Dosen',     'alamat_user'=>'Yogyakarta', 'organisasi_user'=>'UGM',                    'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>13,'role_id'=>4,'nama_user'=>'Dian Kurniawan', 'username_user'=>'dian.kurniawan@example.com', 'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'081234560004','pekerjaan_user'=>'Mahasiswa', 'alamat_user'=>'Surabaya',   'organisasi_user'=>'ITS',                    'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>14,'role_id'=>4,'nama_user'=>'Eka Fitriani',   'username_user'=>'eka.fitriani@example.com',   'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'081234560005','pekerjaan_user'=>'Peneliti',  'alamat_user'=>'Makassar',   'organisasi_user'=>'Universitas Hasanuddin', 'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
            ['id_user'=>15,'role_id'=>4,'nama_user'=>'Fajar Nugroho',  'username_user'=>'fajar.nugroho@example.com',  'password_user'=>$password,'foto_user'=>null,'status_user'=>1,'identitas_user'=>null,'file_identitas_user'=>null,'telepon_user'=>'081234560006','pekerjaan_user'=>'Praktisi',  'alamat_user'=>'Semarang',   'organisasi_user'=>'PT Teknologi Nusantara', 'verify_token'=>null,'otp_user'=>null,'is_otp'=>'N','created_at'=>$now,'updated_at'=>$now],
        ]);

        // ── Registrasi ke event EV260529145400 (ScienceBank Society) ─────────
        // 2 Approved, 2 Rejected, 2 Pending
        DB::table('t_event_registrasi')->insert([
            ['kode_registrasi'=>'REG-2025-0001','kode_event'=>'EV260529145400','nama_peserta'=>'Andi Pratama',   'email_peserta'=>'andi.pratama@example.com',   'instansi_peserta'=>'Universitas Padjadjaran','no_hp_peserta'=>'081234560001','status_registrasi'=>'A','catatan_registrasi'=>'Registrasi diterima, dokumen lengkap.',          'created_by_registrasi'=>'andi.pratama@example.com',  'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0002','kode_event'=>'EV260529145400','nama_peserta'=>'Budi Santoso',   'email_peserta'=>'budi.santoso@example.com',   'instansi_peserta'=>'LIPI',                  'no_hp_peserta'=>'081234560002','status_registrasi'=>'A','catatan_registrasi'=>'Registrasi diterima, peneliti aktif.',           'created_by_registrasi'=>'budi.santoso@example.com',  'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0003','kode_event'=>'EV260529145400','nama_peserta'=>'Citra Dewi',     'email_peserta'=>'citra.dewi@example.com',     'instansi_peserta'=>'UGM',                   'no_hp_peserta'=>'081234560003','status_registrasi'=>'R','catatan_registrasi'=>'Dokumen tidak lengkap, silakan submit ulang.',    'created_by_registrasi'=>'citra.dewi@example.com',    'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0004','kode_event'=>'EV260529145400','nama_peserta'=>'Dian Kurniawan', 'email_peserta'=>'dian.kurniawan@example.com', 'instansi_peserta'=>'ITS',                   'no_hp_peserta'=>'081234560004','status_registrasi'=>'R','catatan_registrasi'=>'Kuota event telah penuh untuk kategori ini.',   'created_by_registrasi'=>'dian.kurniawan@example.com','created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0005','kode_event'=>'EV260529145400','nama_peserta'=>'Eka Fitriani',   'email_peserta'=>'eka.fitriani@example.com',   'instansi_peserta'=>'Universitas Hasanuddin','no_hp_peserta'=>'081234560005','status_registrasi'=>'P','catatan_registrasi'=>null,                                              'created_by_registrasi'=>'eka.fitriani@example.com',  'created_at'=>$now,'updated_at'=>$now],
            ['kode_registrasi'=>'REG-2025-0006','kode_event'=>'EV260529145400','nama_peserta'=>'Fajar Nugroho',  'email_peserta'=>'fajar.nugroho@example.com',  'instansi_peserta'=>'PT Teknologi Nusantara','no_hp_peserta'=>'081234560006','status_registrasi'=>'P','catatan_registrasi'=>null,                                              'created_by_registrasi'=>'fajar.nugroho@example.com', 'created_at'=>$now,'updated_at'=>$now],
        ]);

        // ── Paper — hanya peserta Approved (REG-2025-0001 & REG-2025-0002) ───
        DB::table('t_paper')->insert([
            [
                'kode_paper'       => 'PAP-2025-0001',
                'kode_registrasi'  => 'REG-2025-0001',
                'kode_event'       => 'EV260529145400',
                'judul_paper'      => 'Implementasi Machine Learning untuk Deteksi Penyakit Tanaman Padi',
                'deskripsi_paper'  => 'Penelitian ini mengusulkan model CNN untuk mendeteksi penyakit tanaman padi secara real-time menggunakan dataset citra daun yang dikumpulkan dari lahan pertanian di Jawa Barat dengan akurasi mencapai 94.7%.',
                'file_paper'       => null,
                'tipe_file_paper'  => 'pdf',
                'status_paper'     => 'A',
                'catatan_paper'    => 'Paper diterima, presentasi dijadwalkan sesi pagi.',
                'created_by_paper' => 'andi.pratama@example.com',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_paper'       => 'PAP-2025-0002',
                'kode_registrasi'  => 'REG-2025-0001',
                'kode_event'       => 'EV260529145400',
                'judul_paper'      => 'Optimasi Algoritma Gradient Boosting pada Dataset Tabular Medis',
                'deskripsi_paper'  => 'Studi komparatif XGBoost, LightGBM, dan CatBoost pada tiga dataset medis publik untuk prediksi risiko diabetes dan hipertensi dengan evaluasi AUC-ROC dan F1-Score.',
                'file_paper'       => null,
                'tipe_file_paper'  => 'pptx',
                'status_paper'     => 'P',
                'catatan_paper'    => null,
                'created_by_paper' => 'andi.pratama@example.com',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_paper'       => 'PAP-2025-0003',
                'kode_registrasi'  => 'REG-2025-0002',
                'kode_event'       => 'EV260529145400',
                'judul_paper'      => 'Analisis Dampak Digitalisasi UMKM terhadap Produktivitas Ekonomi Daerah',
                'deskripsi_paper'  => 'Penelitian menggunakan data panel 34 provinsi Indonesia periode 2018-2023 dengan metode Fixed Effect Model untuk mengukur elastisitas digitalisasi UMKM terhadap PDRB per kapita.',
                'file_paper'       => null,
                'tipe_file_paper'  => 'pdf',
                'status_paper'     => 'A',
                'catatan_paper'    => 'Paper sangat relevan, diterima untuk sesi pleno.',
                'created_by_paper' => 'budi.santoso@example.com',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'kode_paper'       => 'PAP-2025-0004',
                'kode_registrasi'  => 'REG-2025-0002',
                'kode_event'       => 'EV260529145400',
                'judul_paper'      => 'Model Prediktif Kualitas Air Sungai Berbasis IoT dan Deep Learning',
                'deskripsi_paper'  => 'Sistem monitoring kualitas air real-time menggunakan sensor IoT yang terhubung ke model LSTM untuk prediksi indeks kualitas air 24 jam ke depan pada Sungai Citarum.',
                'file_paper'       => null,
                'tipe_file_paper'  => 'pdf',
                'status_paper'     => 'R',
                'catatan_paper'    => 'Metodologi kurang detail, silakan revisi dan submit ulang.',
                'created_by_paper' => 'budi.santoso@example.com',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);
    }
}
