<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TSponsorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_sponsor')->truncate();
        DB::table('t_sponsor')->insert([
            [
                'id_sponsor' => 1,
                'nama'       => 'INTELLEGENT SCIENCE',
                'logo'       => 'sponsor/1780023279_sponsor1.png',
                'urutan'     => 1,
                'created_at' => '2026-05-29 02:54:40',
                'updated_at' => null,
            ],
            [
                'id_sponsor' => 2,
                'nama'       => 'BioNexus',
                'logo'       => 'sponsor/1780025601_sponsor2.png',
                'urutan'     => 2,
                'created_at' => '2026-05-29 10:34:11',
                'updated_at' => '2026-05-29 03:34:11',
            ],
        ]);
    }
}
