<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reff_role')->upsert([
            [
                'id_role'       => 1,
                'nama_role'     => 'Super Admin',
                'keterangan_role' => 'Akses penuh ke semua menu',
                'all_data'      => 'Y',
                'status_role'   => 'A',
            ],
            [
                'id_role'       => 2,
                'nama_role'     => 'Admin',
                'keterangan_role' => 'Akses admin biasa',
                'all_data'      => 'Y',
                'status_role'   => 'A',
            ],
        ], ['id_role'], ['nama_role', 'keterangan_role', 'all_data', 'status_role']);
    }
}
