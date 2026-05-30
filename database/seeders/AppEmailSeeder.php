<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppEmailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_email')->truncate();
        DB::table('app_email')->insert([
            [
                'id_app_email'     => 1,
                'smtp_host'        => 'smtp.gmail.com',
                'smtp_port'        => '587',
                'smtp_encryption'  => 'tls',
                'smtp_username'    => 'work.basrilhafi@gmail.com',
                'smtp_password'    => 'fmwvtodportjulmx',
                'smtp_from_address'=> 'work.basrilhafi@gmail.com',
                'smtp_from_name'   => 'Satu Data Pertahanan',
            ],
        ]);
    }
}
