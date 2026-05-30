<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MidtransConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_midtrans_config')->truncate();
        DB::table('app_midtrans_config')->insert([
            [
                'id_midtrans'           => 1,
                'server_key'            => '',
                'client_key'            => '',
                'environment'           => 'sandbox',
                'payment_types'         => json_encode([
                    'credit_card',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'mandiri_bill',
                    'permata_va',
                    'other_va',
                    'gopay',
                    'shopeepay',
                    'qris',
                    'indomaret',
                    'alfamart',
                ]),
                'merchant_id'           => '',
                'webhook_url'           => '',
                'finish_redirect_url'   => '',
                'unfinish_redirect_url' => '',
                'error_redirect_url'    => '',
                'is_active'             => 'N',
                'created_by'            => 'system',
                'updated_by'            => null,
                'created_at'            => now(),
                'updated_at'            => null,
            ],
        ]);
    }
}
