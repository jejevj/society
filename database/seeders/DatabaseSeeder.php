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
            ReffAksesMenuSeeder::class,
            AppUserSeeder::class,
            TautanSeeder::class,
            TEventSeeder::class,
            PesertaSeeder::class,
        ]);
    }
}
