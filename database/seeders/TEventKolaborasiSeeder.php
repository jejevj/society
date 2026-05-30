<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventKolaborasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_kolaborasi')->truncate();
        DB::table('t_event_kolaborasi')->insert([
            [
                'id_event_kolaborasi'    => 1,
                'kode_kolaborasi'        => 'EV260529145401001',
                'event_kode_kolaborasi'  => 'EV260529145400',
                'nama_kolaborasi'        => 'In Collaboration with Indonesia BPOM',
                'gambar_kolaborasi'      => null,
                'keterangan_kolaborasi'  => null,
                'created_at_kolaborasi'  => '2026-05-29 16:32:52',
                'updated_at_kolaborasi'  => null,
            ],
            [
                'id_event_kolaborasi'    => 2,
                'kode_kolaborasi'        => 'EV260529145401002',
                'event_kode_kolaborasi'  => 'EV260529145400',
                'nama_kolaborasi'        => 'Ied by Prof Taruna Ikrar',
                'gambar_kolaborasi'      => null,
                'keterangan_kolaborasi'  => null,
                'created_at_kolaborasi'  => '2026-05-29 16:33:24',
                'updated_at_kolaborasi'  => null,
            ],
        ]);
    }
}
