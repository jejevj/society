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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;



class WebHomeController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    // ── helper: ambil konfigurasi Midtrans aktif ──────────────────────────
    private function midtransConfig(): ?object
    {
        return DB::table('app_midtrans_config')
            ->where('id_midtrans', 1)
            ->where('is_active', 'Y')
            ->first();
    }

    // ── helper: ambil Snap API URL berdasarkan environment ────────────────
    private function snapApiUrl(object $cfg): string
    {
        return ($cfg->environment === 'production')
            ? 'https://api.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    // ── helper: ambil Core API base URL ───────────────────────────────────
    private function coreApiUrl(object $cfg): string
    {
        return ($cfg->environment === 'production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    // ── helper: generate Snap Token via HTTP (tanpa package midtrans-php) ─
    private function getSnapToken(object $cfg, array $params): ?string
    {
        try {
            $response = Http::withBasicAuth($cfg->server_key, '')
                ->timeout(30)
                ->post($this->snapApiUrl($cfg), $params);

            $res = $response->json();

            if ($response->failed() || empty($res['token'])) {
                $errMsg = isset($res['error_messages'])
                    ? implode(', ', (array) $res['error_messages'])
                    : ($res['message'] ?? json_encode($res));
                Log::error('Cart getSnapToken error: ' . $errMsg);
                return null;
            }

            return $res['token'];
        } catch (\Exception $e) {
            Log::error('Cart getSnapToken error: ' . $e->getMessage());
            return null;
        }
    }

    // ── helper: cek status transaksi via Core API ─────────────────────────
    private function getMidtransTransactionStatus(object $cfg, string $orderId): ?object
    {
        try {
            $response = Http::withBasicAuth($cfg->server_key, '')
                ->timeout(15)
                ->get($this->coreApiUrl($cfg) . '/v2/' . $orderId . '/status');

            $res = $response->json();
            if (empty($res['transaction_status'])) {
                return null;
            }
            return (object) $res;
        } catch (\Exception $e) {
            Log::error('getMidtransTransactionStatus error: ' . $e->getMessage());
            return null;
        }
    }

    private function setting(): ?object
    {
        return DB::table('app_setting')->where('kode', 'SETT')->first();
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
            'set'        => $this->setting(),
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

        // Cek apakah user yang login sudah terdaftar di event ini
        $is_registered = false;
        if (session()->has('id_user')) {
            $is_registered = DB::table('t_event_registrasi')
                ->where('kode_event', $key)
                ->where('id_user', session('id_user'))
                ->whereIn('status_registrasi', ['A', 'P'])
                ->exists();
        }

        $data = [
            'menu'          => 'Detail',
            'menu_aktif'    => $menu_aktif,
            'detail'        => $detail,
            'paket'         => $paket,
            'program'       => $program,
            'kolaborasi'    => $kolaborasi,
            'set'           => $this->setting(),
            'is_registered' => $is_registered,
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

        $cek = DB::table('t_event_cart')
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
            'set'           => $this->setting(),
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
        $menu_aktif     = 'about';
        $cart           = DB::table('t_event_cart as c')
            ->join('t_event as e', 'e.kode_event', '=', 'c.kode_event')
            ->where('c.kode_cart', $kode_cart)
            ->select('c.*', 'e.judul_event', 'e.lokasi_event',
                     'e.tanggal_awal_event', 'e.tanggal_akhir_event', 'e.harga_event')
            ->first();

        if (!$cart) abort(404);

        $addon         = DB::table('t_event_cart_paket')->where('kode_cart', $kode_cart)->get();
        $subtotalAddon = $addon->sum('harga_paket');
        $grandTotal    = $cart->subtotal + $subtotalAddon;

        $midtransConfig = $this->midtransConfig();
        $snapToken      = null;
        $orderId        = null;
        $pendingReg     = null;

        if ($midtransConfig && $grandTotal > 0) {
            // Cek registrasi PENDING yang sudah ada untuk cart ini
            $existingReg = DB::table('t_event_registrasi')
                ->where('kode_cart', $kode_cart)
                ->where('payment_status', 'PENDING')
                ->first();

            if ($existingReg) {
                // Sudah ada PENDING — gunakan snap_token yang tersimpan (jika ada)
                // atau regenerate dengan order_id yang SAMA
                $orderId    = $existingReg->midtrans_order_id;
                $pendingReg = $existingReg;

                // Coba ambil snap token dari DB jika tersimpan
                $snapToken = $existingReg->snap_token ?? null;

                // Jika snap_token belum tersimpan, generate ulang dengan order_id yang sama
                // Midtrans mengizinkan regenerate token untuk order_id yang sama selama belum expire
                if (!$snapToken) {
                    $user        = DB::table('app_user')->where('id_user', session('id_user'))->first();
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

                    $email = filter_var($user->email_user ?? '', FILTER_VALIDATE_EMAIL)
                        ? $user->email_user
                        : 'noreply@society-event.com';

                    $params = [
                        'transaction_details' => [
                            'order_id'     => $orderId,
                            'gross_amount' => (int) $grandTotal,
                        ],
                        'customer_details' => [
                            'first_name' => $user->nama_user  ?? 'User',
                            'email'      => $email,
                            'phone'      => $user->no_hp_user ?? '',
                        ],
                    ];
                    if (!empty($itemDetails)) {
                        $params['item_details'] = $itemDetails;
                    }
                    if (!empty($midtransConfig->payment_types)) {
                        $types = json_decode($midtransConfig->payment_types, true);
                        if (!empty($types)) {
                            $params['enabled_payments'] = $types;
                        }
                    }

                    $snapToken = $this->getSnapToken($midtransConfig, $params);

                    // Simpan snap_token ke DB agar tidak perlu regenerate terus
                    if ($snapToken) {
                        DB::table('t_event_registrasi')
                            ->where('midtrans_order_id', $orderId)
                            ->update(['snap_token' => $snapToken, 'updated_at' => now()]);
                    }
                }
            } else {
                // Belum ada PENDING — buat order_id baru dan registrasi baru
                $orderId = 'CART-' . strtoupper(Str::random(8)) . '-' . time();

                $user        = DB::table('app_user')->where('id_user', session('id_user'))->first();
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

                $email = filter_var($user->email_user ?? '', FILTER_VALIDATE_EMAIL)
                    ? $user->email_user
                    : 'noreply@society-event.com';

                $params = [
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => (int) $grandTotal,
                    ],
                    'customer_details' => [
                        'first_name' => $user->nama_user  ?? 'User',
                        'email'      => $email,
                        'phone'      => $user->no_hp_user ?? '',
                    ],
                ];
                if (!empty($itemDetails)) {
                    $params['item_details'] = $itemDetails;
                }
                if (!empty($midtransConfig->payment_types)) {
                    $types = json_decode($midtransConfig->payment_types, true);
                    if (!empty($types)) {
                        $params['enabled_payments'] = $types;
                    }
                }

                $snapToken = $this->getSnapToken($midtransConfig, $params);

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
                    'snap_token'        => $snapToken,
                    'payment_status'    => 'PENDING',
                    'status_registrasi' => 'P',
                    'confirmed_at'      => null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        } elseif ($grandTotal <= 0) {
            $this->enrollCartEvent($kode_cart, $cart, $addon, null, 'FREE');
            DB::table('t_event_cart_paket')->where('kode_cart', $kode_cart)->delete();
            DB::table('t_event_cart')->where('kode_cart', $kode_cart)->delete();
            return redirect()->route('cart-payment.success');
        }

        $data = [
            'menu'           => 'Checkout',
            'menu_aktif'     => $menu_aktif,
            'cart'           => $cart,
            'addon'          => $addon,
            'subtotalAddon'  => $subtotalAddon,
            'grandTotal'     => $grandTotal,
            'snapToken'      => $snapToken,
            'orderId'        => $orderId,
            'pendingReg'     => $pendingReg,
            'midtransConfig' => $midtransConfig,
            'set'            => $this->setting(),
        ];

        return view('web.home.event-checkout', $data);
    }

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

        $midtransConfig = $this->midtransConfig();
        if ($midtransConfig) {
            $mt          = $this->getMidtransTransactionStatus($midtransConfig, $orderId);
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
        }

        return response()->json(['status' => 'pending']);
    }

    public function cartPaymentCallback(Request $request)
    {
        $txStatus    = $request->input('transaction_status');
        $orderId     = $request->input('order_id');
        $fraudStatus = $request->input('fraud_status');

        if (!$txStatus || !$orderId) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        // Verifikasi ke Midtrans untuk mencegah pemalsuan notifikasi
        $midtransConfig = $this->midtransConfig();
        if ($midtransConfig) {
            $verified = $this->getMidtransTransactionStatus($midtransConfig, $orderId);
            if ($verified) {
                $txStatus    = $verified->transaction_status ?? $txStatus;
                $fraudStatus = $verified->fraud_status ?? $fraudStatus;
            }
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

    public function cartPaymentSuccess(Request $request)
    {
        return view('web.home.cart-payment-success', [
            'menu_aktif' => 'about',
            'menu'       => 'Payment Success',
            'set'        => $this->setting(),
        ]);
    }

    private function processCartPaid(object $reg, string $orderId): void
    {
        DB::table('t_event_registrasi')
            ->where('midtrans_order_id', $orderId)
            ->update([
                'payment_status'    => 'PAID',
                'status_registrasi' => 'A',
                'paid_at'           => now(),
                'confirmed_at'      => now(),
                'updated_at'        => now(),
            ]);

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

        DB::table('t_event_cart_paket')->where('kode_cart', $reg->kode_cart)->delete();
        DB::table('t_event_cart')->where('kode_cart', $reg->kode_cart)->delete();
    }

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
                'e.judul_event', 'e.lokasi_event',
                'e.tanggal_awal_event', 'e.tanggal_akhir_event', 'e.harga_event',
                DB::raw('COALESCE(p.total_paket,0) as total_paket'),
                DB::raw('(COALESCE(c.subtotal,0) + COALESCE(p.total_paket,0)) as grand_total')
            )
            ->get();

        $data = [
            'menu'       => 'My Cart',
            'menu_aktif' => $menu_aktif,
            'cart'       => $cart,
            'set'        => $this->setting(),
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
