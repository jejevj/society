<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Jenis menu: S = Single (standalone), M = Menu parent, D = Child/dropdown
        $menus = [
            [
                'id_menu'       => 1,
                'kode_menu'     => 'dashboard',
                'nama_menu'     => 'Dashboard',
                'icon_menu'     => 'ki-outline ki-home',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 1,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 2,
                'kode_menu'     => 'event',
                'nama_menu'     => 'Events',
                'icon_menu'     => 'ki-outline ki-calendar',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 2,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 3,
                'kode_menu'     => 'ref-sponsor',
                'nama_menu'     => 'Sponsor',
                'icon_menu'     => 'ki-outline ki-star',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 3,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 4,
                'kode_menu'     => 'ref-pengguna',
                'nama_menu'     => 'Pengguna',
                'icon_menu'     => 'ki-outline ki-people',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 4,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 5,
                'kode_menu'     => 'ref-role',
                'nama_menu'     => 'Role',
                'icon_menu'     => 'ki-outline ki-shield',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 5,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 6,
                'kode_menu'     => 'ref-menu',
                'nama_menu'     => 'Menu',
                'icon_menu'     => 'ki-outline ki-menu',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 6,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 7,
                'kode_menu'     => 'setting',
                'nama_menu'     => 'Setting',
                'icon_menu'     => 'ki-outline ki-setting-2',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 7,
                'status_menu'   => 'A',
            ],
            [
                'id_menu'       => 8,
                'kode_menu'     => 'tautan',
                'nama_menu'     => 'Tautan',
                'icon_menu'     => 'ki-outline ki-link',
                'jenis_menu'    => 'S',
                'parent_menu'   => null,
                'urutan_menu'   => 8,
                'status_menu'   => 'A',
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('reff_menu')->upsert($menu, ['id_menu'], array_keys($menu));
        }
    }
}
