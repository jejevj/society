<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Session\SessionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class DataService
{
    public function getMenuHTML($menu_aktif, SessionManager $session)
    {
        // $id_role = '1';
        session()->regenerateToken();
        $id_role = $session->get('id_role');
        
        $pecah_menu_aktif = explode("||", $menu_aktif);
        $route_url = env('APP_ROUTE');
        $menus = DB::select("SELECT B.* FROM reff_akses_menu A inner join reff_menu B on A.menu_id = B.id_menu WHERE B.jenis_menu in('S','M') and A.role_id =" . $id_role . " order by B.urutan_menu ASC");
        $html = '';
        foreach ($menus as $menu) {
            if ($menu->jenis_menu == 'M') {
                if ($pecah_menu_aktif[1] == $menu->kode_menu) {
                    $html .= "
                        <div data-kt-menu-trigger=\"{default: 'click', lg: 'hover'}\" data-kt-menu-placement=\"bottom-start\" class=\"menu-item menu-lg-down-accordion active menu-sub-lg-down-indention me-0 me-lg-2\">
                            <span class=\"menu-link\">
                                <span class=\"menu-title text-maroon-active\">{$menu->nama_menu}</span>
                                <span class=\"menu-arrow d-lg-none\"></span>
                            </span>
                            <div class=\"menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px\">";
                } else {
                    $html .= "
                        <div data-kt-menu-trigger=\"{default: 'click', lg: 'hover'}\" data-kt-menu-placement=\"bottom-start\" class=\"menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2\">
                            <span class=\"menu-link\">
                                <span class=\"menu-title\">{$menu->nama_menu}</span>
                                <span class=\"menu-arrow d-lg-none\"></span>
                            </span>
                            <div class=\"menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px\">";
                }

                $submenu = DB::select("SELECT B.* FROM reff_akses_menu A 
                                    INNER JOIN reff_menu B ON A.menu_id = B.id_menu 
                                    WHERE B.jenis_menu IN('D') 
                                        AND B.parent_menu = '{$menu->id_menu}' 
                                        AND A.role_id = {$id_role} 
                                    ORDER BY B.urutan_menu ASC");

                foreach ($submenu as $child) {
                    if ($pecah_menu_aktif[0] == $child->kode_menu) {
                        $html .= "
                            <div class=\"menu-item\">
                                <a class=\"menu-link\" href=\"{$route_url}/{$child->kode_menu}\" data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" data-bs-dismiss=\"click\" data-bs-placement=\"right\">
                                    <span class=\"menu-icon\">
                                        <span class=\"{$child->icon_menu} w-10px h-10px\"></span>
                                    </span>
                                    <span class=\"menu-title text-maroon-child-active\">{$child->nama_menu}</span>
                                </a>
                            </div>";
                    } else {
                        $html .= "
                            <div class=\"menu-item\">
                                <a class=\"menu-link\" href=\"{$route_url}/{$child->kode_menu}\" data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" data-bs-dismiss=\"click\" data-bs-placement=\"right\">
                                    <span class=\"menu-icon\">
                                        <span class=\"{$child->icon_menu} w-10px h-10px\"></span>
                                    </span>
                                    <span class=\"menu-title\">{$child->nama_menu}</span>
                                </a>
                            </div>";
                    }
                }
                $html .= "</div></div>";
            } else {
                if ($pecah_menu_aktif[0] == $menu->kode_menu) {
                    $html .= "
                        <div class=\"menu-item me-0 me-lg-2\">
                            <a href=\"{$route_url}/{$menu->kode_menu}\" class=\"menu-link\">
                                <span class=\"menu-title text-maroon-active\">{$menu->nama_menu}</span>
                            </a>
                        </div>";
                } else {
                    $html .= "
                        <div class=\"menu-item me-0 me-lg-2\">
                            <a href=\"{$route_url}/{$menu->kode_menu}\" class=\"menu-link\">
                                <span class=\"menu-title\">{$menu->nama_menu}</span>
                            </a>
                        </div>";
                }
            }
        }


        return $html;
    }

    public function createLog(Request $request, $fungsi=null, $keterangan=null, $data_baru=null, $data_lama=null){
        $userId = session('user_id');
        DB::table('app_log_aktivitas')->insert([
            'ip_log' => $request->ip(),
            'user_log' => session('nama'),
            'user_id' => session('id'),
            'fungsi_log' => $fungsi,
            'deskripsi_log' => $keterangan,
            'data_lama_log'=> $data_lama,
            'data_baru_log' => $data_baru,
            'created_at' => now(), 
        ]);
    }

    public function createLogWeb(Request $request, $fungsi=null, $keterangan=null, $data_baru=null, $data_lama=null){
        $userId = session('user_id');
        DB::table('app_log_aktivitas')->insert([
            'ip_log' => $request->ip(),
            'user_log' => session('nama_user'),
            'user_id' => session('id_user'),
            'fungsi_log' => $fungsi,
            'deskripsi_log' => $keterangan,
            'data_lama_log'=> $data_lama,
            'data_baru_log' => $data_baru,
            'created_at' => now(), 
        ]);
    }

    public function cekPermit($menu_aktif, SessionManager $session)
    {
        $id_role = $session->get('id_role');
        $pecah_menu_aktif = explode("||", $menu_aktif);

        $akses = DB::table('reff_akses_menu as A')
            ->join('reff_menu as B', 'A.menu_id', '=', 'B.id_menu')
            ->where('B.kode_menu', $pecah_menu_aktif[0])
            ->where('A.role_id', $id_role)
            ->select(
                'A.permit_c',
                'A.permit_r',
                'A.permit_u',
                'A.permit_d'
            )
            ->first();

        if (!$akses) {
            return [
                'c' => false,
                'r' => false,
                'u' => false,
                'd' => false,
            ];
        }else{
            return [
                'c' => (bool) $akses->permit_c,
                'r' => (bool) $akses->permit_r,
                'u' => (bool) $akses->permit_u,
                'd' => (bool) $akses->permit_d,
            ];
        }
    }

    public function cekSetting(){
        return DB::table('app_setting')->where('kode', 'SETT')->first();
    }

    public static function getNotifVerifikasi()
    {
        $id_role = session('id_role');
        $all_data = session('all_data');
        $idRole = session('id_role');

        $cekAkses = DB::table('reff_akses_menu as A')->join('reff_menu as B', 'A.menu_id', '=', 'B.id_menu')->where('B.kode_menu', 'validasi')->where('A.role_id', $id_role)->exists();

        $cekAkses2 = DB::table('reff_akses_menu as A')->join('reff_menu as B', 'A.menu_id', '=', 'B.id_menu')->where('B.kode_menu', 'request')->where('A.role_id', $id_role)->exists();

        if (!$cekAkses && !$cekAkses2) {
            return [
                'data' => collect(),
                'count' => 0
            ];
        }
        
        $dataMaster = DB::table('t_master_data')
            ->select(
                DB::raw("'master' as tipe"),
                'id_master_data as id',
                'judul_master as judul',
                'created_at'
            )
            ->where('status_master', 'V');

        $dataPermohonan = DB::table('t_permohonan as p')
            ->select(
                DB::raw("'permohonan' as tipe"),
                'p.id_permohonan as id',
                'm.judul_master as judul',
                'p.created_at'
            )
            ->join('t_master_data as m', 'm.kode_data_master', '=', 'p.kode_data_permohonan')
            ->where('p.status_permohonan', 'P');

        if ($all_data !== 'Y') {

            $dataMaster->where('organisasi_master', $idRole);
            $dataPermohonan->where('m.organisasi_master', $idRole);
        }

        $data = $dataMaster
            ->unionAll($dataPermohonan);

        $data = DB::query()
            ->fromSub($data, 'x')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $countMaster = DB::table('t_master_data')
            ->where('status_master', 'V');

        $countPermohonan = DB::table('t_permohonan as p')
            ->join('t_master_data as m', 'm.kode_data_master', '=', 'p.kode_data_permohonan')
            ->where('status_permohonan', 'P');

        if ($all_data !== 'Y') {

            $countMaster->where('organisasi_master', $idRole);

            $countPermohonan->where('m.organisasi_master', $idRole);
        }

        $count = $countMaster->count() + $countPermohonan->count();
        return [
            'data' => $data,
            'count' => $count,
            'akses_validasi' => $cekAkses,
            'akses_permohonan' => $cekAkses2,
        ];
    }

    public function scanAntivirus($file_post)
    {
        $cekSetting = DB::table('app_setting')->first();

        if ($cekSetting && $cekSetting->cek_antivirus == 'Y') {

            $scanResponse = Http::timeout(60)
                ->connectTimeout(60)
                ->attach(
                    'FILES',
                    file_get_contents($file_post->getRealPath()),
                    $file_post->getClientOriginalName()
                )
                ->post($cekSetting->url_antivirus);

            if ($scanResponse->failed()) {

                return [
                    'success' => false,
                    'message' => 'Gagal menghubungi antivirus service',
                    'error'   => $scanResponse->body(),
                    'code'    => 500
                ];
            }

            $scanResult = $scanResponse->json();

            if (
                !isset($scanResult['success']) ||
                $scanResult['success'] !== true ||
                !isset($scanResult['data']['result'][0]['isInfected'])
            ) {

                return [
                    'success' => false,
                    'message' => 'Response antivirus tidak valid',
                    'code'    => 500
                ];
            }

            if ($scanResult['data']['result'][0]['isInfected'] === true) {
                $virusList = $scanResult['data']['result'][0]['viruses'] ?? [];
                return [
                    'success' => false,
                    'message' => 'File terdeteksi virus',
                    'virus'   => $virusList,
                    'code'    => 422
                ];
            }
        }

        return [
            'success' => true
        ];
    }

    public function setMailConfig()
    {
        $setting = DB::table('app_email')->first();

        config([
            'mail.default' => 'smtp',

            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $setting->smtp_host,
            'mail.mailers.smtp.port' => $setting->smtp_port,
            'mail.mailers.smtp.encryption' => 
                $setting->smtp_encryption != '' 
                    ? $setting->smtp_encryption 
                    : null,
            'mail.mailers.smtp.username' => $setting->smtp_username,
            'mail.mailers.smtp.password' => $setting->smtp_password,

            'mail.from.address' => $setting->smtp_from_address,
            'mail.from.name' => $setting->smtp_from_name,
        ]);
    }
}
