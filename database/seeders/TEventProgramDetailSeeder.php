<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventProgramDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_program_detail')->truncate();
        // Tidak ada data di SQL file untuk t_event_program_detail
    }
}
