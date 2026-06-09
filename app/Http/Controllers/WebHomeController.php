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
use Illuminate\Support\Str;



class WebHomeController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'about';

        $event = DB::table('t_event')
            ->where('status_event', 'Y')
            ->orderBy('created_at_event', 'asc')
            ->get();

        foreach ($event as $e) {
            $e->paket = DB::table('t_event_paket')
                ->where('event_kode_paket', $e->kode_event)
                ->orderBy('id_event_paket', 'asc')
                ->get();
                
            $e->kolaborasi = DB::table('t_event_kolaborasi')
            ->where('event_kode_kolaborasi', $e->kode_event)
            ->orderBy('id_event_kolaborasi', 'asc')
            ->get();
        }

        $data = [
            'menu' => 'Home',
            'menu_aktif' => $menu_aktif,
            'event' => $event,
            'set' => DB::table('app_setting')
                        ->where('kode', 'SETT')
                        ->first(),
        ];

        return view('web.home.main', $data);
    }

    public function detailEvent($key, Request $request)
    {
        $menu_aktif = 'about';
        $detail = DB::table('t_event')->where('kode_event', $key)->first();
        // dd($detail);
        $paket = DB::table('t_event_paket')
            ->where('event_kode_paket', $key)
            ->orderBy('id_event_paket', 'asc')
            ->get();

        $program = DB::table('t_event_program')
            ->where('event_kode_program', $key)
            ->orderBy('hari_program', 'asc')
            ->get();

        $kolaborasi = DB::table('t_event_kolaborasi')
            ->where('event_kode_kolaborasi', $key)
            ->orderBy('created_at_kolaborasi', 'asc')
            ->get();

        foreach ($program as $e) {
            $e->program = DB::table('t_event_program_detail')
                ->where('event_program_kode', $e->kode_event_program)
                ->orderBy('awal_program_detail', 'asc')
                ->get();
          
        }

        $data = [
            'menu' => 'Detail',
            'menu_aktif' => $menu_aktif,
            'detail' => $detail,
            'paket' => $paket,
            'program' => $program,
            'kolaborasi' => $kolaborasi,
            'set' => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.detail', $data);
    }

    public function addCartEvent(Request $request)
    {
        if (!session()->has('id_user')) {

            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ]);
        }

        $event = DB::table('t_event')
            ->where('kode_event', $request->kode_event)
            ->first();

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found.'
            ]);
        }

        $qty = (int) $request->quantity;

        if ($qty < 1) {
            $qty = 1;
        }
        $cek = DB::table('t_event_cart')
            ->where('id_user', session('id_user'))
            ->where('kode_event', $request->kode_event)
            ->first();

        if ($cek) {
            $kode_cart = $cek->kode_cart;
            DB::table('t_event_cart')
                ->where('id_event_cart', $cek->id_event_cart)
                ->update([
                    'qty' => $cek->qty + $qty,
                    'subtotal' => ($cek->qty + $qty) * $event->harga_event,
                    'updated_at' => now()
                ]);

        } else {
            $kode_cart = 'CRT' . date('YmdHis') . strtoupper(Str::random(5));

            DB::table('t_event_cart')->insert([
                'kode_cart' => $kode_cart ,
                'kode_event' => $event->kode_event,
                'id_user' => session('id_user'),
                'qty' => $qty,
                'harga' => $event->harga_event,
                'subtotal' => $qty * $event->harga_event,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully added to cart.',
            'kode_cart' => $kode_cart
        ]);
    }

    public function detailCartEvent($kode_cart, Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }
        $menu_aktif = 'about';
        $cart = DB::table('t_event_cart as c')
            ->join('t_event as e', 'e.kode_event', '=', 'c.kode_event')
            ->where('c.kode_cart', $kode_cart)
            ->first();

        $paket = DB::table('t_event_paket')
            ->where('event_kode_paket', $cart->kode_event)
            ->get();

        $selectedPaket = DB::table('t_event_cart_paket')
            ->where('kode_cart', $kode_cart)
            ->pluck('kode_event_paket')
            ->toArray();

        $data = [
            'menu'          => 'Detail',
            'menu_aktif'    => $menu_aktif,
            'cart'          => $cart,
            'paket'         => $paket,
            'selectedPaket' => $selectedPaket,
            'set'           => DB::table('app_setting')
                                ->where('kode', 'SETT')
                                ->first(),
        ];

        return view('web.home.event-cart', $data);
    }

    public function savePackageCart(Request $request)
    {
        if (!session()->has('id_user')) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ]);
        }

        DB::table('t_event_cart_paket')
            ->where('kode_cart', $request->kode_cart)
            ->delete();
        if (!empty($request->paket)) {
            foreach ($request->paket as $kodePaket) {
                $paket = DB::table('t_event_paket')->where('kode_paket', $kodePaket)->first();
                if ($paket) {
                    DB::table('t_event_cart_paket')->insert([
                        'kode_cart'        => $request->kode_cart,
                        'kode_event_paket' => $paket->kode_paket,
                        'judul_paket'      => $paket->judul_paket,
                        'harga_paket'      => $paket->harga_paket,
                        'event_kode'      => $paket->event_kode_paket,
                        'created_at'       => now(),
                        'updated_at'       => now()
                    ]);
                }
            }
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Package selection saved successfully.',
            'kode_cart' => $request->kode_cart
        ]);
    }

    
    public function detailCheckoutEvent($kode_cart, Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }
        $menu_aktif = 'about';
        $cart = DB::table('t_event_cart as c')
            ->join('t_event as e', 'e.kode_event', '=', 'c.kode_event')
            ->where('c.kode_cart', $kode_cart)
            ->select(
                'c.*',
                'e.judul_event',
                'e.lokasi_event',
                'e.tanggal_awal_event',
                'e.tanggal_akhir_event',
                'e.harga_event'
            )
            ->first();
        if (!$cart) {
            abort(404);
        }

        $addon = DB::table('t_event_cart_paket')->where('kode_cart', $kode_cart)->get();
        $subtotalAddon = $addon->sum('harga_paket') * $cart->qty;
        $grandTotal = $cart->subtotal + $subtotalAddon;
        // dd($cart);
        $data = [
            'menu'          => 'Checkout',
            'menu_aktif'    => $menu_aktif,
            'cart'          => $cart,
            'addon'         => $addon,
            'subtotalAddon' => $subtotalAddon,
            'grandTotal'    => $grandTotal,
            'set'           => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.event-checkout', $data);
    }

    public function myCart(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }
        $menu_aktif = 'cart';
        $cart = DB::table('t_event_cart as c')
            ->join('t_event as e', 'e.kode_event', '=', 'c.kode_event')
            ->leftJoin(
                DB::raw("
                    (
                        SELECT
                            kode_cart,
                            COALESCE(SUM(harga_paket),0) as total_paket
                        FROM t_event_cart_paket
                        GROUP BY kode_cart
                    ) p
                "),
                'p.kode_cart',
                '=',
                'c.kode_cart'
            )

            ->where('c.id_user', session('id_user'))

            ->orderBy('c.created_at', 'desc')

            ->select(
                'c.*',
                'e.judul_event',
                'e.lokasi_event',
                'e.tanggal_awal_event',
                'e.tanggal_akhir_event',
                'e.harga_event',
                DB::raw('COALESCE(p.total_paket,0) as total_paket'),
                DB::raw('
                    (
                        COALESCE(c.subtotal,0)
                        +
                        COALESCE(p.total_paket,0)
                    ) as grand_total
                ')
            )
            ->get();

        $data = [
            'menu'        => 'My Cart',
            'menu_aktif'  => $menu_aktif,
            'cart'        => $cart,
            'set'         => DB::table('app_setting')
                                ->where('kode', 'SETT')
                                ->first(),
        ];

        return view('web.home.my-cart', $data);
    }

    public function updateCartEvent(Request $request)
    {
        $cart = DB::table('t_event_cart')
            ->where('kode_cart', $request->kode_cart)
            ->first();

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart not found'
            ]);
        }

        $qty = max(1, (int)$request->qty);

        $harga = $cart->harga;

        DB::table('t_event_cart')
            ->where('kode_cart', $request->kode_cart)
            ->update([
                'qty' => $qty,
                'subtotal' => $harga * $qty,
                'updated_at' => now()
            ]);

        DB::table('t_event_cart_paket')
            ->where('kode_cart', $request->kode_cart)
            ->update([
                'harga_paket' => DB::raw('harga_paket')
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    public function deleteCartEvent(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('t_event_cart_paket')
                ->where('kode_cart', $request->kode_cart)
                ->delete();

            DB::table('t_event_cart')
                ->where('kode_cart', $request->kode_cart)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cart deleted successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
