<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffTopikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reff_topik')->truncate();
        DB::table('reff_topik')->insert([
            [
                'id_topik'      => 8,
                'kode_topik'    => 'TG260529044146',
                'nama_topik'    => 'Riset2',
                'urutan_topik'  => 12,
                'deskripsi_topik' => null,
                'status_topik'  => 1,
                'created_at'    => '2026-05-29 04:41:46',
                'updated_at'    => '2026-05-29 04:46:36',
            ],
        ]);
    }
}
