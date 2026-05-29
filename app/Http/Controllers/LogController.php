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



class LogController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'log||';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/login-backend');
        }
        $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());
        
        $data = [
            'menu' => 'Log Aktivitas',
            'menu_aktif' => $menu_aktif,
            'navbar' => $navbar,
            'breadcrumb' => '<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
								<li class="breadcrumb-item text-white fw-bold lh-1">
									<a class="text-white text-hover-primary">
										<i class="ki-outline ki-home text-white fs-3"></i>
									</a>
								</li>
								<li class="breadcrumb-item">
									<i class="ki-outline ki-right fs-4 text-white mx-n1"></i>
								</li>
								<li class="breadcrumb-item text-white fw-bold lh-1">Log Aktivitas</li>					
							</ul>'
        ];
        if (!$cek['r']) {
             return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.log.main', $data);

        
    }

    public function getTableLog(Request $request)
    {
        if ($request->session()->has('id')) {

            $menu_aktif = 'log||';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());

            $query = DB::table('app_log_aktivitas as a')
                ->selectRaw('*');

            if ($request->filled('nama')) {
                $query->where('a.deskripsi_log', 'like', '%' . $request->input('nama') . '%');
            }

            $query->orderBy('a.id_log', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('data_lama_log', function ($row) {

                    if (empty($row->data_lama_log)) {
                        return '-';
                    }

                    $json = json_decode($row->data_lama_log, true);

                    if (!$json) {
                        return $row->data_lama_log;
                    }

                    $pretty = json_encode($json, JSON_PRETTY_PRINT);

                    return '
                        <button class="btn btn-sm btn-marron-submit mb-1" onclick="toggleJson(this)">Lihat</button>
                        <pre class="json-log d-none">' . e($pretty) . '</pre>
                    ';
                })

                // ================= DATA BARU =================
                ->addColumn('data_baru_log', function ($row) {

                    if (empty($row->data_baru_log)) {
                        return '-';
                    }

                    $json = json_decode($row->data_baru_log, true);

                    if (!$json) {
                        return $row->data_baru_log;
                    }

                    $pretty = json_encode($json, JSON_PRETTY_PRINT);

                    return '
                        <button class="btn btn-sm btn-marron-submit mb-1" onclick="toggleJson(this)">Lihat</button>
                        <pre class="json-log d-none">' . e($pretty) . '</pre>
                    ';
                })

                ->addColumn('action', function ($row) {
                    $id_hash = Crypt::encrypt($row->id_log);
                    $infoUrl = route('editTopik', $id_hash);

                    return '
                        <a href="' . $infoUrl . '" class="btn btn-light-warning btn-sm">
                            <span class="fa fa-pencil"></span>
                        </a> 
                        <button class="btn btn-danger btn-delete-topik btn-sm" data-id="' . $id_hash . '">
                            <span class="fa fa-trash"></span>
                        </button>
                    ';
                })

                ->rawColumns(['data_lama_log', 'data_baru_log', 'action'])
                ->make(true);
        }
    }

    


}
