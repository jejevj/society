<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSliderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_slider')->truncate();
        // Tidak ada data di SQL file untuk app_slider
        // Tambahkan data awal di sini jika diperlukan
    }
}
