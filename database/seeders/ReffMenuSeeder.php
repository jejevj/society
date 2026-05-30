<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffMenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reff_menu')->truncate();
        DB::table('reff_menu')->insert([
            ['id_menu' => 1,  'nama_menu' => 'Dashboards',     'jenis_menu' => 'S', 'kode_menu' => 'dashboard',    'icon_menu' => 'fa fa-dashboard', 'parent_menu' => 0, 'urutan_menu' => 1, 'deskripsi_menu' => 'menu dashboard',   'created_at' => '2025-08-28 08:29:05', 'updated_at' => null],
            ['id_menu' => 8,  'nama_menu' => 'Reference',      'jenis_menu' => 'M', 'kode_menu' => 'referensi',    'icon_menu' => null,               'parent_menu' => 0, 'urutan_menu' => 5, 'deskripsi_menu' => null,               'created_at' => '2025-08-28 08:29:54', 'updated_at' => null],
            ['id_menu' => 10, 'nama_menu' => 'Users',          'jenis_menu' => 'D', 'kode_menu' => 'ref-pengguna', 'icon_menu' => 'bullet',           'parent_menu' => 8, 'urutan_menu' => 1, 'deskripsi_menu' => null,               'created_at' => '2025-08-28 08:30:51', 'updated_at' => null],
            ['id_menu' => 13, 'nama_menu' => 'Tags',           'jenis_menu' => 'D', 'kode_menu' => 'ref-topik',    'icon_menu' => 'bullet',           'parent_menu' => 8, 'urutan_menu' => 3, 'deskripsi_menu' => null,               'created_at' => '2025-08-28 08:30:51', 'updated_at' => null],
            ['id_menu' => 16, 'nama_menu' => 'Content Web',    'jenis_menu' => 'M', 'kode_menu' => 'konten',       'icon_menu' => null,               'parent_menu' => 0, 'urutan_menu' => 8, 'deskripsi_menu' => null,               'created_at' => '2025-08-28 08:29:54', 'updated_at' => null],
            ['id_menu' => 17, 'nama_menu' => 'Settings',       'jenis_menu' => 'D', 'kode_menu' => 'setting',      'icon_menu' => 'bullet',           'parent_menu' => 16,'urutan_menu' => 1, 'deskripsi_menu' => null,               'created_at' => '2025-08-28 08:30:51', 'updated_at' => null],
            ['id_menu' => 18, 'nama_menu' => 'Link',           'jenis_menu' => 'D', 'kode_menu' => 'tautan',       'icon_menu' => 'bullet',           'parent_menu' => 16,'urutan_menu' => 2, 'deskripsi_menu' => null,               'created_at' => '2025-08-28 08:30:51', 'updated_at' => null],
            ['id_menu' => 26, 'nama_menu' => 'Sponsor',        'jenis_menu' => 'D', 'kode_menu' => 'ref-sponsor',  'icon_menu' => 'bullet',           'parent_menu' => 8, 'urutan_menu' => 2, 'deskripsi_menu' => null,               'created_at' => null,                  'updated_at' => null],
        ]);
    }
}
