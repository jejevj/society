<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReffAksesMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin (role_id=1): akses penuh ke semua menu
        $superAdminMenus = [1, 2, 3, 4, 5, 6, 7, 8];

        foreach ($superAdminMenus as $menuId) {
            $exists = DB::table('reff_akses_menu')
                ->where('role_id', 1)
                ->where('menu_id', $menuId)
                ->exists();

            if (!$exists) {
                DB::table('reff_akses_menu')->insert([
                    'role_id'  => 1,
                    'menu_id'  => $menuId,
                    'permit_c' => 1,
                    'permit_r' => 1,
                    'permit_u' => 1,
                    'permit_d' => 1,
                ]);
            } else {
                DB::table('reff_akses_menu')
                    ->where('role_id', 1)
                    ->where('menu_id', $menuId)
                    ->update([
                        'permit_c' => 1,
                        'permit_r' => 1,
                        'permit_u' => 1,
                        'permit_d' => 1,
                    ]);
            }
        }

        // Admin (role_id=2): event, sponsor, setting
        $adminMenus = [
            2 => ['c' => 1, 'r' => 1, 'u' => 1, 'd' => 0],
            3 => ['c' => 1, 'r' => 1, 'u' => 1, 'd' => 0],
            7 => ['c' => 0, 'r' => 1, 'u' => 1, 'd' => 0],
        ];

        foreach ($adminMenus as $menuId => $permit) {
            $exists = DB::table('reff_akses_menu')
                ->where('role_id', 2)
                ->where('menu_id', $menuId)
                ->exists();

            if (!$exists) {
                DB::table('reff_akses_menu')->insert([
                    'role_id'  => 2,
                    'menu_id'  => $menuId,
                    'permit_c' => $permit['c'],
                    'permit_r' => $permit['r'],
                    'permit_u' => $permit['u'],
                    'permit_d' => $permit['d'],
                ]);
            } else {
                DB::table('reff_akses_menu')
                    ->where('role_id', 2)
                    ->where('menu_id', $menuId)
                    ->update([
                        'permit_c' => $permit['c'],
                        'permit_r' => $permit['r'],
                        'permit_u' => $permit['u'],
                        'permit_d' => $permit['d'],
                    ]);
            }
        }
    }
}
