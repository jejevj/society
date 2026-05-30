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
            ['id_akses_menu' => 4,  'role_id' => 1, 'menu_id' => 1,  'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:11:50', 'updated_at' => null],
            ['id_akses_menu' => 5,  'role_id' => 1, 'menu_id' => 2,  'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 9,  'role_id' => 1, 'menu_id' => 8,  'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 11, 'role_id' => 1, 'menu_id' => 10, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 14, 'role_id' => 1, 'menu_id' => 13, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 17, 'role_id' => 1, 'menu_id' => 16, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 18, 'role_id' => 1, 'menu_id' => 17, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 19, 'role_id' => 1, 'menu_id' => 18, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2025-08-29 09:12:02', 'updated_at' => null],
            ['id_akses_menu' => 44, 'role_id' => 1, 'menu_id' => 26, 'permit_r' => 1, 'permit_c' => 1, 'permit_u' => 1, 'permit_d' => 1, 'created_at' => '2026-05-19 04:53:36', 'updated_at' => null],
        ]);
    }
}
