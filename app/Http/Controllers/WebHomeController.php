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
use Illuminate\Support\Facades\Log;
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
            'menu'       => 'Home',
            'menu_aktif' => $menu_aktif,
            'event'      => $event,
            'set'        => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.main', $data);
    }

    public function detailEvent($key, Request $request)
    {
        $menu_aktif = 'about';
        $detail     = DB::table('t_event')->where('kode_event', $key)->first();

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
            'menu'       => 'Detail',
            'menu_aktif' => $menu_aktif,
            'detail'     => $detail,
            'paket'      => $paket,
            'program'    => $program,
            'kolaborasi' => $kolaborasi,
            'set'        => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.detail', $data);
    }

    public function addCartEvent(Request $request)
    {
        if (!session()->has('id_user')) {
            return response()->json(['status' => false, 'message' => 'Please login first.']);
        }

        $event = DB::table('t_event')->where('kode_event', $request->kode_event)->first();
        if (!$event) {
            return response()->json(['status' => false, 'message' => 'Event not found.']);
        }

        // Always qty = 1 per user per event
        $qty  = 1;
        $cek  = DB::table('t_event_cart')
            ->where('id_user', session('id_user'))
            ->where('kode_event', $request->kode_event)
            ->first();

        if ($cek) {
            $kode_cart = $cek->kode_cart;
            DB::table('t_event_cart')
                ->where('id_event_cart', $cek->id_event_cart)
                ->update([
                    'qty'        => 1,
                    'subtotal'   => $event->harga_event,
                    'updated_at' => now(),
                ]);
        } else {
            $kode_cart = 'CRT' . date('YmdHis') . strtoupper(Str::random(5));
            DB::table('t_event_cart')->insert([
                'kode_cart'  => $kode_cart,
                'kode_event' => $event->kode_event,
                'id_user'    => session('id_user'),
                'qty'        => 1,
                'harga'      => $event->harga_event,
                'subtotal'   => $event->harga_event,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Successfully added to cart.',
            'kode_cart' => $kode_cart,
        ]);
    }

    public function detailCartEvent($kode_cart, Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }
        $menu_aktif = 'about';
        $cart       = DB::table('t_event_cart as c')
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
            'set'           => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.event-cart', $data);
    }

    public function savePackageCart(Request $request)
    {
        if (!session()->has('id_user')) {
            return response()->json(['status' => false, 'message' => 'Please login first.']);
        }

        DB::table('t_event_cart_paket')->where('kode_cart', $request->kode_cart)->delete();

        if (!empty($request->paket)) {
            foreach ($request->paket as $kodePaket) {
                $paket = DB::table('t_event_paket')->where('kode_paket', $kodePaket)->first();
                if ($paket) {
                    DB::table('t_event_cart_paket')->insert([
                        'kode_cart'        => $request->kode_cart,
                        'kode_event_paket' => $paket->kode_paket,
                        'judul_paket'      => $paket->judul_paket,
                        'harga_paket'      => $paket->harga_paket,
                        'event_kode'       => $paket->event_kode_paket,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                }
            }
        }

        return response()->json([
            'status'    => true,
            'message'   => 'Package selection saved successfully.',
            'kode_cart' => $request->kode_cart,
        ]);
    }

    public function detailCheckoutEvent($kode_cart, Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }
        $menu_aktif = 'about';
        $cart       = DB::table('t_event_cart as c')
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

        if (!$cart) abort(404);

        $addon         = DB::table('t_event_cart_paket')->where('kode_cart', $kode_cart)->get();
        $subtotalAddon = $addon->sum('harga_paket');
        $grandTotal    = $cart->subtotal + $subtotalAddon;

        // Generate Snap Token
        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();
        $snapToken      = null;
        $orderId        = null;

        if ($midtransConfig && $grandTotal > 0) {
            // Cek apakah sudah ada PENDING order sebelumnya untuk cart ini
            $existingReg = DB::table('t_event_registrasi')
                ->where('kode_cart', $kode_cart)
                ->where('payment_status', 'PENDING')
                ->first();

            if ($existingReg) {
                $orderId = $existingReg->midtrans_order_id;
            } else {
                $orderId = 'CART-' . strtoupper(Str::random(8)) . '-' . time();
            }

            \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
            \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $user = DB::table('app_user')->where('id_user', session('id_user'))->first();

            $itemDetails = [];
            if ($cart->harga_event > 0) {
                $itemDetails[] = [
                    'id'       => $cart->kode_event,
                    'price'    => (int) $cart->harga_event,
                    'quantity' => 1,
                    'name'     => mb_substr($cart->judul_event, 0, 50),
                ];
            }
            foreach ($addon as $ad) {
                if ($ad->harga_paket > 0) {
                    $itemDetails[] = [
                        'id'       => $ad->kode_event_paket,
                        'price'    => (int) $ad->harga_paket,
                        'quantity' => 1,
                        'name'     => mb_substr($ad->judul_paket, 0, 50),
                    ];
                }
            }

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $user->nama_user  ?? 'User',
                    'email'      => $user->email_user ?? '',
                    'phone'      => $user->no_hp_user ?? '',
                ],
            ];
            if (!empty($itemDetails)) {
                $params['item_details'] = $itemDetails;
            }

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                Log::error('Cart getSnapToken error: ' . $e->getMessage());
            }

            // Simpan/update pending registrasi
            if (!$existingReg) {
                $kodeRegistrasi = 'REG' . date('ymdHis') . strtoupper(Str::random(4));
                DB::table('t_event_registrasi')->insert([
                    'kode_registrasi'   => $kodeRegistrasi,
                    'kode_event'        => $cart->kode_event,
                    'kode_cart'         => $kode_cart,
                    'id_user'           => session('id_user'),
                    'nama_peserta'      => $user->nama_user       ?? '',
                    'email_peserta'     => $user->email_user      ?? '',
                    'instansi_peserta'  => $user->organisasi_user ?? null,
                    'no_hp_peserta'     => $user->no_hp_user      ?? null,
                    'total_bayar'       => (float) $grandTotal,
                    'midtrans_order_id' => $orderId,
                    'payment_status'    => 'PENDING',
                    'status_registrasi' => 'P',
                    'confirmed_at'      => null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        } elseif ($grandTotal <= 0) {
            // Gratis — langsung enroll
            $this->enrollCartEvent($kode_cart, $cart, $addon, null, 'FREE');
            // Hapus cart
            DB::table('t_event_cart_paket')->where('kode_cart', $kode_cart)->delete();
            DB::table('t_event_cart')->where('kode_cart', $kode_cart)->delete();
            return redirect()->route('cart-payment.success');
        }

        $data = [
            'menu'          => 'Checkout',
            'menu_aktif'    => $menu_aktif,
            'cart'          => $cart,
            'addon'         => $addon,
            'subtotalAddon' => $subtotalAddon,
            'grandTotal'    => $grandTotal,
            'snapToken'     => $snapToken,
            'orderId'       => $orderId,
            'set'           => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.event-checkout', $data);
    }

    // ─── AJAX: cek status pembayaran cart (polling) ───
    public function cartCheckPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json(['status' => 'error', 'message' => 'Order ID tidak ditemukan.']);
        }

        $reg = DB::table('t_event_registrasi')->where('midtrans_order_id', $orderId)->first();
        if (!$reg) {
            return response()->json(['status' => 'not_found']);
        }

        if ($reg->payment_status === 'PAID' && $reg->status_registrasi === 'A') {
            return response()->json(['status' => 'paid']);
        }

        if (in_array($reg->payment_status, ['FAILED', 'CANCEL', 'EXPIRE'])) {
            return response()->json(['status' => 'failed', 'payment_status' => $reg->payment_status]);
        }

        // Cross-check ke Midtrans
        $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();
        if ($midtransConfig) {
            try {
                \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
                \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);

                $mt          = \Midtrans\Transaction::status($orderId);
                $txStatus    = $mt->transaction_status ?? '';
                $fraudStatus = $mt->fraud_status ?? null;

                if (in_array($txStatus, ['capture', 'settlement'])
                    && ($fraudStatus === 'accept' || $fraudStatus === null)) {
                    $this->processCartPaid($reg, $orderId);
                    return response()->json(['status' => 'paid']);
                }

                if (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                    DB::table('t_event_registrasi')
                        ->where('midtrans_order_id', $orderId)
                        ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
                    return response()->json(['status' => 'failed', 'payment_status' => $txStatus]);
                }
            } catch (\Exception $e) {
                Log::error('cartCheckPayment error: ' . $e->getMessage());
            }
        }

        return response()->json(['status' => 'pending']);
    }

    // ─── Midtrans Webhook untuk pembayaran cart ───
    public function cartPaymentCallback(Request $request)
    {
        try {
            $midtransConfig = DB::table('app_midtrans_config')->where('status_config', 'Y')->first();
            if ($midtransConfig) {
                \Midtrans\Config::$serverKey    = $midtransConfig->server_key;
                \Midtrans\Config::$isProduction = (bool) ($midtransConfig->is_production ?? false);
            }
            $notif       = new \Midtrans\Notification();
            $txStatus    = $notif->transaction_status;
            $orderId     = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
        } catch (\Exception $e) {
            $txStatus    = $request->input('transaction_status');
            $orderId     = $request->input('order_id');
            $fraudStatus = $request->input('fraud_status');
        }

        $isPaid = in_array($txStatus, ['capture', 'settlement'])
            && ($fraudStatus === 'accept' || $fraudStatus === null || $fraudStatus === '');

        $reg = DB::table('t_event_registrasi')->where('midtrans_order_id', $orderId)->first();
        if (!$reg) {
            return response()->json(['status' => 'order_not_found'], 404);
        }

        if ($isPaid && $reg->payment_status !== 'PAID') {
            $this->processCartPaid($reg, $orderId);
        } elseif (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
            DB::table('t_event_registrasi')
                ->where('midtrans_order_id', $orderId)
                ->update(['payment_status' => 'FAILED', 'updated_at' => now()]);
        }

        return response()->json(['status' => 'ok']);
    }

    // ─── Halaman sukses setelah bayar via cart ───
    public function cartPaymentSuccess(Request $request)
    {
        $set = DB::table('app_setting')->where('kode', 'SETT')->first();
        return view('web.home.cart-payment-success', compact('set'));
    }

    // ─── Helper: proses assign event setelah PAID ───
    private function processCartPaid(object $reg, string $orderId): void
    {
        // Update status registrasi
        DB::table('t_event_registrasi')
            ->where('midtrans_order_id', $orderId)
            ->update([
                'payment_status'    => 'PAID',
                'status_registrasi' => 'A',
                'paid_at'           => now(),
                'confirmed_at'      => now(),
                'updated_at'        => now(),
            ]);

        // Simpan add-on ke t_event_addon
        $addon = DB::table('t_event_cart_paket')
            ->where('kode_cart', $reg->kode_cart)
            ->get();

        foreach ($addon as $ad) {
            $exists = DB::table('t_event_addon')
                ->where('kode_registrasi', $reg->kode_registrasi)
                ->where('kode_paket', $ad->kode_event_paket)
                ->exists();

            if (!$exists) {
                DB::table('t_event_addon')->insert([
                    'id_user'         => $reg->id_user,
                    'kode_event'      => $reg->kode_event,
                    'kode_registrasi' => $reg->kode_registrasi,
                    'kode_paket'      => $ad->kode_event_paket,
                    'nama_addon'      => $ad->judul_paket,
                    'harga_addon'     => (float) $ad->harga_paket,
                    'qty'             => 1,
                    'subtotal'        => (float) $ad->harga_paket,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }

        // Bersihkan cart
        DB::table('t_event_cart_paket')->where('kode_cart', $reg->kode_cart)->delete();
        DB::table('t_event_cart')->where('kode_cart', $reg->kode_cart)->delete();
    }

    // ─── Helper: enroll gratis via cart ───
    private function enrollCartEvent(string $kode_cart, object $cart, $addon, ?string $orderId, string $paymentStatus = 'FREE'): void
    {
        $userId = (int) session('id_user');
        $user   = DB::table('app_user')->where('id_user', $userId)->first();

        $alreadyReg = DB::table('t_event_registrasi')
            ->where('kode_event', $cart->kode_event)
            ->where('id_user', $userId)
            ->exists();

        if ($alreadyReg) return;

        $kodeRegistrasi = 'REG' . date('ymdHis') . strtoupper(Str::random(4));
        $grandTotal     = $cart->subtotal + $addon->sum('harga_paket');

        DB::table('t_event_registrasi')->insert([
            'kode_registrasi'   => $kodeRegistrasi,
            'kode_event'        => $cart->kode_event,
            'kode_cart'         => $kode_cart,
            'id_user'           => $userId,
            'nama_peserta'      => $user->nama_user       ?? '',
            'email_peserta'     => $user->email_user      ?? '',
            'instansi_peserta'  => $user->organisasi_user ?? null,
            'no_hp_peserta'     => $user->no_hp_user      ?? null,
            'total_bayar'       => (float) $grandTotal,
            'midtrans_order_id' => $orderId,
            'payment_status'    => $paymentStatus,
            'status_registrasi' => 'A',
            'confirmed_at'      => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        foreach ($addon as $ad) {
            DB::table('t_event_addon')->insert([
                'id_user'         => $userId,
                'kode_event'      => $cart->kode_event,
                'kode_registrasi' => $kodeRegistrasi,
                'kode_paket'      => $ad->kode_event_paket,
                'nama_addon'      => $ad->judul_paket,
                'harga_addon'     => (float) $ad->harga_paket,
                'qty'             => 1,
                'subtotal'        => (float) $ad->harga_paket,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }

    public function myCart(Request $request)
    {
        if (!$request->session()->has('id_user')) {
            return redirect()->route('login');
        }
        $menu_aktif = 'cart';
        $cart       = DB::table('t_event_cart as c')
            ->join('t_event as e', 'e.kode_event', '=', 'c.kode_event')
            ->leftJoin(
                DB::raw("
                    (
                        SELECT kode_cart, COALESCE(SUM(harga_paket),0) as total_paket
                        FROM t_event_cart_paket
                        GROUP BY kode_cart
                    ) p
                "),
                'p.kode_cart', '=', 'c.kode_cart'
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
                DB::raw('(COALESCE(c.subtotal,0) + COALESCE(p.total_paket,0)) as grand_total')
            )
            ->get();

        $data = [
            'menu'       => 'My Cart',
            'menu_aktif' => $menu_aktif,
            'cart'       => $cart,
            'set'        => DB::table('app_setting')->where('kode', 'SETT')->first(),
        ];

        return view('web.home.my-cart', $data);
    }

    public function updateCartEvent(Request $request)
    {
        $cart = DB::table('t_event_cart')->where('kode_cart', $request->kode_cart)->first();
        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart not found']);
        }
        DB::table('t_event_cart')
            ->where('kode_cart', $request->kode_cart)
            ->update(['qty' => 1, 'subtotal' => $cart->harga, 'updated_at' => now()]);

        return response()->json(['status' => true, 'message' => 'Cart updated successfully']);
    }

    public function deleteCartEvent(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('t_event_cart_paket')->where('kode_cart', $request->kode_cart)->delete();
            DB::table('t_event_cart')->where('kode_cart', $request->kode_cart)->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Cart deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
