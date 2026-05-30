<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id_role'        => 1,
                'nama_role'      => 'Super Admin',
                'kode_role'      => 'SUPERADMIN',
                'deskripsi_role' => 'Akses penuh ke semua menu',
                'all_data_role'  => 'Y',
            ],
            [
                'id_role'        => 2,
                'nama_role'      => 'Admin',
                'kode_role'      => 'ADMIN',
                'deskripsi_role' => 'Akses admin biasa',
                'all_data_role'  => 'Y',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('reff_role')->upsert(
                $role,
                ['id_role'],
                ['nama_role', 'kode_role', 'deskripsi_role', 'all_data_role']
            );
        }
    }
}
