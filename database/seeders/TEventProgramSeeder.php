<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventProgramSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_program')->truncate();
        // Tidak ada data di SQL file untuk t_event_program
    }
}
