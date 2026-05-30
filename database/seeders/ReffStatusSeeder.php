<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reff_status')->truncate();
        DB::table('reff_status')->insert([
            ['id_status' => 1,  'kode_status' => 'M',   'keterangan_status' => 'Master Menu',                    'deskripsi_status' => '',  'jenis_status' => 'menu',               'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 2,  'kode_status' => 'S',   'keterangan_status' => 'Single Menu',                    'deskripsi_status' => '',  'jenis_status' => 'menu',               'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 3,  'kode_status' => 'D',   'keterangan_status' => 'Sub Menu',                       'deskripsi_status' => '',  'jenis_status' => 'menu',               'urutan_status' => 3, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 4,  'kode_status' => 'DT',  'keterangan_status' => 'Dataset',                        'deskripsi_status' => '',  'jenis_status' => 'tipe_data',          'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 5,  'kode_status' => 'IG',  'keterangan_status' => 'Infografis',                     'deskripsi_status' => '',  'jenis_status' => 'tipe_data',          'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 7,  'kode_status' => 'ST',  'keterangan_status' => 'Statistik',                      'deskripsi_status' => '',  'jenis_status' => 'kategori_data',      'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 8,  'kode_status' => 'THN', 'keterangan_status' => 'Tahunan',                        'deskripsi_status' => '',  'jenis_status' => 'frekuensi_data',     'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 9,  'kode_status' => 'BLN', 'keterangan_status' => 'Bulanan',                        'deskripsi_status' => '',  'jenis_status' => 'frekuensi_data',     'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 10, 'kode_status' => 'HRN', 'keterangan_status' => 'Harian',                         'deskripsi_status' => '',  'jenis_status' => 'frekuensi_data',     'urutan_status' => 3, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 11, 'kode_status' => 'V',   'keterangan_status' => 'Verifikasi',                     'deskripsi_status' => '',  'jenis_status' => 'status_data',        'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 12, 'kode_status' => 'Y',   'keterangan_status' => 'Disetujui',                      'deskripsi_status' => '',  'jenis_status' => 'status_data',        'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 13, 'kode_status' => 'N',   'keterangan_status' => 'Ditolak',                        'deskripsi_status' => '',  'jenis_status' => 'status_data',        'urutan_status' => 3, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 14, 'kode_status' => 'P',   'keterangan_status' => 'Proses',                         'deskripsi_status' => '',  'jenis_status' => 'status_pengaduan',   'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 15, 'kode_status' => 'Y',   'keterangan_status' => 'Diterima',                       'deskripsi_status' => '',  'jenis_status' => 'status_pengaduan',   'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 16, 'kode_status' => 'N',   'keterangan_status' => 'Ditolak',                        'deskripsi_status' => '',  'jenis_status' => 'status_pengaduan',   'urutan_status' => 3, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 17, 'kode_status' => 'P',   'keterangan_status' => 'Proses',                         'deskripsi_status' => '',  'jenis_status' => 'status_permohonan',  'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 18, 'kode_status' => 'Y',   'keterangan_status' => 'Diterima',                       'deskripsi_status' => '',  'jenis_status' => 'status_permohonan',  'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 19, 'kode_status' => 'N',   'keterangan_status' => 'Ditolak',                        'deskripsi_status' => '',  'jenis_status' => 'status_permohonan',  'urutan_status' => 3, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 20, 'kode_status' => 'KU',  'keterangan_status' => 'Keuangan',                       'deskripsi_status' => null,'jenis_status' => 'kategori_data',      'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 21, 'kode_status' => 'SP',  'keterangan_status' => 'Spasial',                        'deskripsi_status' => null,'jenis_status' => 'kategori_data',      'urutan_status' => 3, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 22, 'kode_status' => 'SM1', 'keterangan_status' => '-01-01||-06-30',                 'deskripsi_status' => 'Semester 1', 'jenis_status' => 'semester_report', 'urutan_status' => 1, 'created_at' => null, 'updated_at' => null],
            ['id_status' => 23, 'kode_status' => 'SM2', 'keterangan_status' => '-07-01||-12-31',                 'deskripsi_status' => 'Semester 2', 'jenis_status' => 'semester_report', 'urutan_status' => 2, 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
