<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventPaketSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event_paket')->truncate();
        DB::table('t_event_paket')->insert([
            [
                'id_event_paket'   => 1,
                'kode_paket'       => 'EV260529145401001',
                'event_kode_paket' => 'EV260529145401',
                'judul_paket'      => 'Golf Experience',
                'sub_judul_paket'  => 'World-class course in scenic surroundings.',
                'keterangan_paket' => 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
                'gambar_paket'     => 'event/golf.png',
                'icon_paket'       => 'event/golf-icon.png',
                'lokasi_paket'     => 'Bali National Golf Club',
                'created_at_paket' => '2026-05-29 16:04:09',
                'updated_at_paket' => null,
            ],
            [
                'id_event_paket'   => 2,
                'kode_paket'       => 'EV260529145400002',
                'event_kode_paket' => 'EV260529145400',
                'judul_paket'      => 'Beach Activities',
                'sub_judul_paket'  => 'Water Sports and beachside relaxion.',
                'keterangan_paket' => 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
                'gambar_paket'     => 'event/beach.png',
                'icon_paket'       => 'event/beach-icon.png',
                'lokasi_paket'     => 'Nusa Dua Beach',
                'created_at_paket' => '2026-05-29 15:13:52',
                'updated_at_paket' => null,
            ],
            [
                'id_event_paket'   => 3,
                'kode_paket'       => 'EV260529145400003',
                'event_kode_paket' => 'EV260529145400',
                'judul_paket'      => 'Spa & Traditional Massage',
                'sub_judul_paket'  => 'Traditional Balinese wellness treatment.',
                'keterangan_paket' => 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
                'gambar_paket'     => 'event/spa.png',
                'icon_paket'       => 'event/spa-icon.png',
                'lokasi_paket'     => 'Balinese Wellnese',
                'created_at_paket' => '2026-05-29 15:52:11',
                'updated_at_paket' => null,
            ],
            [
                'id_event_paket'   => 4,
                'kode_paket'       => 'EV260529145400004',
                'event_kode_paket' => 'EV260529145400',
                'judul_paket'      => 'Diving Adventure',
                'sub_judul_paket'  => 'Reef exploration and marine adventures',
                'keterangan_paket' => 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
                'gambar_paket'     => 'event/diving.png',
                'icon_paket'       => 'event/diving-icon.png',
                'lokasi_paket'     => 'Tanjung Benoa',
                'created_at_paket' => '2026-05-29 15:10:12',
                'updated_at_paket' => null,
            ],
            [
                'id_event_paket'   => 5,
                'kode_paket'       => 'EV260529145400005',
                'event_kode_paket' => 'EV260529145400',
                'judul_paket'      => 'Bali Cultural Tour',
                'sub_judul_paket'  => 'Sunset tour and cultural discovery.',
                'keterangan_paket' => 'making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
                'gambar_paket'     => 'event/cultural.png',
                'icon_paket'       => 'event/cultural-icon.png',
                'lokasi_paket'     => 'Uluwatu Temple',
                'created_at_paket' => '2026-05-29 15:10:12',
                'updated_at_paket' => null,
            ],
        ]);
    }
}
