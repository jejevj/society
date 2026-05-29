<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use App\Models\ReffMenu;
use App\Models\ReffStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;       
use Illuminate\Support\Facades\Crypt;   
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class WebTopikController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        // if (!$request->session()->has('id')) {
        //     return Redirect::to('/login-backend');
        // }
        $menu_aktif = 'topik';
        // $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Organisasi',
            'menu_aktif' => $menu_aktif,
            'topik' => DB::table('reff_topik as ro')
                            ->select(
                                'ro.*',
                                DB::raw("
                                    (
                                        SELECT COUNT(*) 
                                        FROM t_master_data tm 
                                        INNER JOIN t_data_tag tdt 
                                            ON tdt.kode_data_tag = tm.kode_data_master
                                        WHERE tdt.kode_tag = ro.id_topik::text
                                        AND sifat_master IN ('TERBUKA','TERBATAS')
                                        AND status_master = 'Y'
                                        AND tipe_master = 'DT'
                                    ) as total_dataset,

                                    (
                                        SELECT COUNT(*) 
                                        FROM t_master_data tm 
                                        INNER JOIN t_data_tag tdt 
                                            ON tdt.kode_data_tag = tm.kode_data_master
                                        WHERE tdt.kode_tag = ro.id_topik::text
                                        AND sifat_master IN ('TERBUKA','TERBATAS')
                                        AND status_master = 'Y'
                                        AND tipe_master = 'IG'
                                    ) as total_infografis
                                ")
                            )
                            ->orderBy('id_topik', 'asc') 
                            ->paginate(10),
            'set' =>  DB::table('app_setting')->where('id_setting', 1)->first(),

        ];

        return view('web.topik', $data);

        
    }


    


}
