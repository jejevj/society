<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_setting')->truncate();
        DB::table('app_setting')->insert([
            [
                'id_setting'            => 1,
                'logo'                  => 'image_setting/1757579765_logo-kemhan (1).png',
                'gambar_dashboard'      => 'image_setting/1757579782_statistic2 (1).png',
                'gambar_topik'          => 'image_setting/1777353459_ChatGPT Image 28 Apr 2026, 12.17.24.png',
                'deskripsi_topik'       => 'Temukan berbagai dataset dan infografis dari seluruh satuan dan unit kerja di lingkungan Kementrian Pertahanan Republik Indonesia.',
                'gambar_organisasi'     => 'image_setting/1778221639_bg-monitoring.png',
                'deskripsi_organisasi'  => 'Daftar dataset dan infografis pada masing-masing organisasi Kementerian Pertahanan',
                'gambar_permohonan'     => 'image_setting/1777514843_bg-monitoring.png',
                'deskripsi_permohonan'  => 'Silahkan isi data dibawah ini terlebih dahulu agar anda dapat cek status permohonan informasi anda',
                'gambar2_permohonan'    => 'image_setting/1777515140_side-monitoring.png',
                'gambar_hubungi'        => 'image_setting/1777517889_bg-monitoring.png',
                'deskripsi_hubungi'     => 'Silahkan isi data dibawah ini beserta pesan yang anda tujukan kepada kami',
                'gambar2_hubungi'       => 'image_setting/1777518026_side-hubungi.png',
                'gambar_tentang'        => 'image_setting/1777518725_bg-monitoring.png',
                'deskripsi_tentang'     => 'Portal Satu Data Pertahanan adalah Portal Data Terpadu Kementerian Pertahanan Republik Indonesia yang menyajikan data-data dari seluruh Satuan dan Unit Kerja.',
                'gambar2_tentang'       => 'image_setting/1777519330_side-tentang.png',
                'gambar_login'          => 'image_setting/1777520836_bg-monitoring.png',
                'deskripsi_login'       => 'Silahkan masukkan data dibawah ini untuk mengakses akun',
                'gambar2_login'         => 'image_setting/1778131965_side-login.png',
                'url_facebook'          => 'https://www.facebook.com/',
                'url_twitter'           => 'https://www.x.com',
                'url_instagram'         => 'https://www.instagram.com',
                'url_youtube'           => 'https://www.youtube.com',
                'url_linkedin'          => 'https://www.linkedin.com',
                'kode'                  => 'SETT',
                'cek_antivirus'         => 'N',
                'url_antivirus'         => 'http://10.1.100.131:13320/api/v1/scan',
                'url_chatbot'           => 'https://apps.syscloud.my.id/chatbot/',
                'created_at'            => '2025-09-10 00:00:00',
                'updated_at'            => '2026-05-13 04:42:21',
            ],
        ]);
    }
}
