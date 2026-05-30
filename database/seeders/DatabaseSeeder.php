<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ReffRoleSeeder::class,
            ReffMenuSeeder::class,
            ReffStatusSeeder::class,
            ReffAksesMenuSeeder::class,
            AppUserSeeder::class,
            AppEmailSeeder::class,
            AppSettingSeeder::class,
            AppSliderSeeder::class,
            TSponsorSeeder::class,
            TEventSeeder::class,
            TEventKolaborasiSeeder::class,
            TEventPaketSeeder::class,
            TEventPaketDetailSeeder::class,
            TEventProgramSeeder::class,
            TEventProgramDetailSeeder::class,
            ReffTopikSeeder::class,
        ]);
    }
}
