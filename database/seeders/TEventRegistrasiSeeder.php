<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventRegistrasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_registrasi')
            ->where('kode_event', 'EV260529145400')
            ->where('payment_status', 'PENDING')
            ->where('status_registrasi', 'P')
            ->update([
                'payment_status'    => 'PAID',
                'status_registrasi' => 'A',
                'confirmed_at'      => now(),
                'updated_at'        => now(),
            ]);
    }
}
