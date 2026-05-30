<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Core references
            ReffRoleSeeder::class,
            ReffMenuSeeder::class,
            ReffAksesMenuSeeder::class,
            ReffStatusSeeder::class,
            ReffTopikSeeder::class,

            // App settings
            AppSettingSeeder::class,
            AppEmailSeeder::class,
            AppSliderSeeder::class,
            MidtransConfigSeeder::class,

            // Users
            AppUserSeeder::class,

            // Content
            TautanSeeder::class,
            TSponsorSeeder::class,

            // Event
            TEventSeeder::class,
            TEventKolaborasiSeeder::class,
            TEventPaketSeeder::class,
            TEventPaketDetailSeeder::class,
            TEventProgramSeeder::class,
            TEventProgramDetailSeeder::class,

            // Peserta & Registrasi
            PesertaSeeder::class,
        ]);
    }
}
