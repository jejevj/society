<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reff_role')->truncate();
        DB::table('reff_role')->insert([
            [
                'id_role'       => 1,
                'nama_role'     => 'Super Admin',
                'kode_role'     => 'SADM',
                'deskripsi_role'=> 'role untuk super admin',
                'all_data_role' => 'Y',
                'created_at'    => '2025-08-28 07:22:00',
                'updated_at'    => '2026-03-31 02:57:47',
            ],
            [
                'id_role'       => 4,
                'nama_role'     => 'Public',
                'kode_role'     => 'PUB',
                'deskripsi_role'=> '-',
                'all_data_role' => 'N',
                'created_at'    => '2025-09-01 02:51:24',
                'updated_at'    => '2026-05-29 02:06:43',
            ],
        ]);
    }
}
