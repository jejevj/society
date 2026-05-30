<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventAddonSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Seed event_addon - linked to events from TEventSeeder
        DB::table('event_addon')->insertOrIgnore([
            [
                'kode_addon'      => 'ADDON-001',
                'kode_event'      => 'EV260529145400',
                'nama_addon'      => 'Workshop Kit',
                'deskripsi_addon' => 'Paket alat tulis dan buku catatan eksklusif untuk peserta workshop.',
                'gambar_addon'    => null,
                'harga_addon'     => 75000,
                'status_addon'    => 'A',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon'      => 'ADDON-002',
                'kode_event'      => 'EV260529145400',
                'nama_addon'      => 'Lunch Package',
                'deskripsi_addon' => 'Paket makan siang selama 2 hari penuh termasuk snack.',
                'gambar_addon'    => null,
                'harga_addon'     => 150000,
                'status_addon'    => 'A',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon'      => 'ADDON-003',
                'kode_event'      => 'EV260529145400',
                'nama_addon'      => 'Sertifikat Digital',
                'deskripsi_addon' => 'Sertifikat digital berformat PDF dengan tanda tangan elektronik.',
                'gambar_addon'    => null,
                'harga_addon'     => 0,
                'status_addon'    => 'A',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon'      => 'ADDON-004',
                'kode_event'      => 'EV260529145401',
                'nama_addon'      => 'T-Shirt Peserta',
                'deskripsi_addon' => 'Kaos resmi event dengan pilihan ukuran S, M, L, XL.',
                'gambar_addon'    => null,
                'harga_addon'     => 100000,
                'status_addon'    => 'A',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon'      => 'ADDON-005',
                'kode_event'      => 'EV260529145401',
                'nama_addon'      => 'Rekaman Sesi',
                'deskripsi_addon' => 'Akses rekaman video semua sesi selama 30 hari setelah event.',
                'gambar_addon'    => null,
                'harga_addon'     => 50000,
                'status_addon'    => 'N',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);

        // Seed event_addon_registrasi - linked to registrasi dari PesertaSeeder
        DB::table('event_addon_registrasi')->insertOrIgnore([
            [
                'kode_addon_reg'  => 'ADDREG-001',
                'kode_registrasi' => 'REG-2025-0001',
                'kode_addon'      => 'ADDON-001',
                'status'          => 'A',
                'catatan'         => 'Pembayaran dikonfirmasi.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon_reg'  => 'ADDREG-002',
                'kode_registrasi' => 'REG-2025-0001',
                'kode_addon'      => 'ADDON-002',
                'status'          => 'A',
                'catatan'         => 'Pembayaran dikonfirmasi.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon_reg'  => 'ADDREG-003',
                'kode_registrasi' => 'REG-2025-0002',
                'kode_addon'      => 'ADDON-002',
                'status'          => 'P',
                'catatan'         => null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_addon_reg'  => 'ADDREG-004',
                'kode_registrasi' => 'REG-2025-0003',
                'kode_addon'      => 'ADDON-003',
                'status'          => 'R',
                'catatan'         => 'Data registrasi tidak valid.',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}
