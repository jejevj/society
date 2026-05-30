<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventPaketDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_paket_detail')->truncate();
        // Tidak ada data di SQL file untuk t_event_paket_detail
    }
}
