<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TEventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('t_event')->truncate();
        DB::table('t_event')->insert([
            [
                'id_event'            => 1,
                'kode_event'          => 'EV260529145400',
                'judul_event'         => 'ScienceBank Society',
                'sub_judul_event'     => 'Inagural President & Summit',
                'keterangan_event'    => 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software including versions of Lorem Ipsum',
                'lokasi_event'        => 'Bali Beach Convention Center',
                'tanggal_awal_event'  => '2026-07-01',
                'tanggal_akhir_event' => '2026-07-04',
                'harga_event'         => 500000,
                'status_event'        => 'Y',
                'background_event'    => 'event/bg-scbank.jpeg',
                'created_by_event'    => null,
                'created_at_event'    => '2026-05-29 15:01:10',
                'updated_at_event'    => null,
            ],
            [
                'id_event'            => 2,
                'kode_event'          => 'EV260529145401',
                'judul_event'         => 'ScienceBank Society 2',
                'sub_judul_event'     => 'Inagural President & Summit',
                'keterangan_event'    => 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software including versions of Lorem Ipsum',
                'lokasi_event'        => 'Bali Beach Convention Center',
                'tanggal_awal_event'  => '2026-07-01',
                'tanggal_akhir_event' => '2026-07-04',
                'harga_event'         => 750000,
                'status_event'        => 'Y',
                'background_event'    => 'event/bg-scbank.jpeg',
                'created_by_event'    => null,
                'created_at_event'    => '2026-05-29 15:26:42',
                'updated_at_event'    => null,
            ],
            [
                'id_event'            => 3,
                'kode_event'          => 'EV260529145402',
                'judul_event'         => 'ScienceBank Society 3',
                'sub_judul_event'     => 'Annual Conference & Workshop',
                'keterangan_event'    => 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset\'s Body Type sheets. It has survived not only many decades, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised thanks to these sheets and more recently with desktop publishing software including versions of Lorem Ipsum',
                'lokasi_event'        => 'Jakarta Convention Center',
                'tanggal_awal_event'  => '2026-08-10',
                'tanggal_akhir_event' => '2026-08-12',
                'harga_event'         => 1000000,
                'status_event'        => 'Y',
                'background_event'    => 'event/bg-scbank.jpeg',
                'created_by_event'    => null,
                'created_at_event'    => '2026-06-10 08:00:00',
                'updated_at_event'    => null,
            ],
        ]);
    }
}
