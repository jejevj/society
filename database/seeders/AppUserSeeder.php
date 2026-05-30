<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_user')->truncate();
        DB::table('app_user')->insert([
            [
                'id_user'       => 1,
                'role_id'       => 1,
                'nama_user'     => 'admin aplikasi',
                'username_user' => 'admin',
                'password_user' => '$2y$12$DqH4QqdV1lsz72WeV99i0.8mMk/hP2YTVascrFs272K73qZ.gi37G',
                'foto_user'     => 'organisasi/1756960375_profile.png',
                'status_user'   => 1,
                'identitas_user'=> null,
                'file_identitas_user' => null,
                'telepon_user'  => null,
                'pekerjaan_user'=> null,
                'alamat_user'   => null,
                'organisasi_user' => null,
                'verify_token'  => null,
                'otp_user'      => null,
                'is_otp'        => 'N',
                'created_at'    => '2025-08-31 08:42:01',
                'updated_at'    => '2025-09-04 04:47:46',
            ],
            [
                'id_user'       => 2,
                'role_id'       => 3,
                'nama_user'     => 'organisasi pusdatin2',
                'username_user' => 'basrilhf@gmail.com',
                'password_user' => '$2y$12$pWB4sP7Y/buPwt0hgdJzCu5l/tEuQiPXx4r7iZKp0WPjx.qZdV6Jq',
                'foto_user'     => 'organisasi/1756874039_profile.png',
                'status_user'   => 1,
                'identitas_user'=> null,
                'file_identitas_user' => null,
                'telepon_user'  => null,
                'pekerjaan_user'=> null,
                'alamat_user'   => null,
                'organisasi_user' => null,
                'verify_token'  => null,
                'otp_user'      => '',
                'is_otp'        => 'N',
                'created_at'    => '2025-08-31 08:43:33',
                'updated_at'    => '2025-09-03 04:33:59',
            ],
            [
                'id_user'       => 7,
                'role_id'       => 1,
                'nama_user'     => 'admin aplikasi',
                'username_user' => 'business.basrilhafi@gmail.com',
                'password_user' => '$2y$12$Nxp8U6YjrVcC1AFfT27AsuGqotBhs3LTgQd8jMtFo2bxdv6MEqUTi',
                'foto_user'     => 'organisasi/1780035437_sponsor1.png',
                'status_user'   => 1,
                'identitas_user'=> null,
                'file_identitas_user' => null,
                'telepon_user'  => null,
                'pekerjaan_user'=> null,
                'alamat_user'   => null,
                'organisasi_user' => null,
                'verify_token'  => null,
                'otp_user'      => '1',
                'is_otp'        => 'N',
                'created_at'    => '2025-08-31 08:42:01',
                'updated_at'    => '2026-05-29 06:24:09',
            ],
        ]);
    }
}
