<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TautanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_tautan')->truncate();
        DB::table('t_tautan')->insert([
            [
                'nama_tautan'   => 'Instagram',
                'link_tautan'   => 'https://instagram.com',
                'urutan_tautan' => 1,
                'gambar_tautan' => null,
                'created_at'    => now(),
                'updated_at'    => null,
            ],
            [
                'nama_tautan'   => 'Facebook',
                'link_tautan'   => 'https://facebook.com',
                'urutan_tautan' => 2,
                'gambar_tautan' => null,
                'created_at'    => now(),
                'updated_at'    => null,
            ],
            [
                'nama_tautan'   => 'YouTube',
                'link_tautan'   => 'https://youtube.com',
                'urutan_tautan' => 3,
                'gambar_tautan' => null,
                'created_at'    => now(),
                'updated_at'    => null,
            ],
            [
                'nama_tautan'   => 'Twitter / X',
                'link_tautan'   => 'https://x.com',
                'urutan_tautan' => 4,
                'gambar_tautan' => null,
                'created_at'    => now(),
                'updated_at'    => null,
            ],
        ]);
    }
}
