<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffAksesMenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reff_akses_menu')->truncate();
        DB::table('reff_akses_menu')->insert([
            // =====================
            // Super Admin (role_id = 1) - Full access semua menu
            // =====================
            ['id_akses_menu' => 1,  'role_id' => 1, 'menu_id' => 1,  'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:11:50', 'updated_at' => null], // dashboard
            ['id_akses_menu' => 2,  'role_id' => 1, 'menu_id' => 8,  'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // referensi (master)
            ['id_akses_menu' => 3,  'role_id' => 1, 'menu_id' => 9,  'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // ref-role
            ['id_akses_menu' => 4,  'role_id' => 1, 'menu_id' => 10, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // ref-pengguna
            ['id_akses_menu' => 5,  'role_id' => 1, 'menu_id' => 11, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // ref-menu
            ['id_akses_menu' => 6,  'role_id' => 1, 'menu_id' => 13, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // ref-topik
            ['id_akses_menu' => 7,  'role_id' => 1, 'menu_id' => 26, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2026-05-19 04:53:36', 'updated_at' => null], // ref-sponsor
            ['id_akses_menu' => 8,  'role_id' => 1, 'menu_id' => 16, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // konten (master)
            ['id_akses_menu' => 9,  'role_id' => 1, 'menu_id' => 17, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // setting
            ['id_akses_menu' => 10, 'role_id' => 1, 'menu_id' => 18, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null], // tautan
            ['id_akses_menu' => 11, 'role_id' => 1, 'menu_id' => 27, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2026-05-29 00:00:00', 'updated_at' => null], // event (master)
            ['id_akses_menu' => 12, 'role_id' => 1, 'menu_id' => 28, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2026-05-29 00:00:00', 'updated_at' => null], // event list
        ]);
    }
}
