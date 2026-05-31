<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class MidtransConfigController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService, Request $request)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $menu_aktif = 'midtrans-config||setting-group';
        if (!$request->session()->has('id')) {
            $prefix = trim(env('APP_ROUTE'), '/');
            return Redirect::to(($prefix ? '/' . $prefix : '') . '/admin-panel');
        }
        $cek    = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
        $navbar = $this->dataService->getMenuHTML($menu_aktif, Session::getFacadeRoot());

        $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();

        $allPaymentTypes = [
            'credit_card'   => 'Credit Card (Visa, Mastercard, JCB, Amex)',
            'bca_va'        => 'Bank Transfer - BCA Virtual Account',
            'bni_va'        => 'Bank Transfer - BNI Virtual Account',
            'bri_va'        => 'Bank Transfer - BRI Virtual Account',
            'mandiri_bill'  => 'Bank Transfer - Mandiri Bill Payment',
            'permata_va'    => 'Bank Transfer - Permata Virtual Account',
            'other_va'      => 'Bank Transfer - Other Virtual Account',
            'gopay'         => 'E-Wallet - GoPay',
            'shopeepay'     => 'E-Wallet - ShopeePay',
            'qris'          => 'QRIS (Quick Response Indonesian Standard)',
            'indomaret'     => 'Convenience Store - Indomaret',
            'alfamart'      => 'Convenience Store - Alfamart',
        ];

        $selectedTypes = [];
        if ($config && $config->payment_types) {
            $selectedTypes = json_decode($config->payment_types, true) ?? [];
        }

        $tabCounts = ['all'=>0,'pending'=>0,'settlement'=>0,'cancel'=>0,'expire'=>0,'deny'=>0,'refund'=>0];
        try {
            if (Schema::hasTable('app_midtrans_transaction')) {
                $tabCounts = [
                    'all'        => DB::table('app_midtrans_transaction')->count(),
                    'pending'    => DB::table('app_midtrans_transaction')->where('transaction_status', 'pending')->count(),
                    'settlement' => DB::table('app_midtrans_transaction')->where('transaction_status', 'settlement')->count(),
                    'cancel'     => DB::table('app_midtrans_transaction')->where('transaction_status', 'cancel')->count(),
                    'expire'     => DB::table('app_midtrans_transaction')->where('transaction_status', 'expire')->count(),
                    'deny'       => DB::table('app_midtrans_transaction')->where('transaction_status', 'deny')->count(),
                    'refund'     => DB::table('app_midtrans_transaction')->where('transaction_status', 'refund')->count(),
                ];
            }
        } catch (\Exception $e) {
            // table not yet migrated — defaults above are used
        }

        $data = [
            'menu'            => 'Midtrans Configurations',
            'menu_aktif'      => $menu_aktif,
            'navbar'          => $navbar,
            'cek_permit'      => $cek,
            'config'          => $config,
            'allPaymentTypes' => $allPaymentTypes,
            'selectedTypes'   => $selectedTypes,
            'tabCounts'       => $tabCounts,
        ];

        if (!$cek['r']) {
            return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.midtrans.main', $data);
    }

    public function getTableTransaksi(Request $request)
    {
        if (!$request->session()->has('id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $status = $request->get('status', 'all');
        $search = $request->get('search', ['value' => ''])['value'] ?? '';
        $start  = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);
        $order  = $request->get('order', [['column' => 0, 'dir' => 'desc']]);

        $columns = ['id_transaksi', 'order_id', 'transaction_status', 'payment_type', 'gross_amount', 'transaction_time', 'updated_at'];
        $orderCol = $columns[$order[0]['column'] ?? 0] ?? 'id_transaksi';
        $orderDir = in_array(strtolower($order[0]['dir'] ?? 'desc'), ['asc', 'desc']) ? $order[0]['dir'] : 'desc';

        try {
            if (!Schema::hasTable('app_midtrans_transaction')) {
                return response()->json(['draw' => (int)$request->get('draw'), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            }

            $query = DB::table('app_midtrans_transaction');

            if ($status !== 'all') {
                $query->where('transaction_status', $status);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_id', 'like', "%{$search}%")
                      ->orWhere('transaction_id', 'like', "%{$search}%")
                      ->orWhere('payment_type', 'like', "%{$search}%")
                      ->orWhere('transaction_status', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();
            $recordsTotal    = DB::table('app_midtrans_transaction')->count();

            $data = $query->orderBy($orderCol, $orderDir)
                          ->skip($start)->take($length)
                          ->get();

            $rows = [];
            foreach ($data as $i => $row) {
                $statusBadge = $this->getStatusBadge($row->transaction_status);
                $amount      = 'Rp ' . number_format($row->gross_amount, 0, ',', '.');
                $rows[] = [
                    'DT_RowIndex' => $start + $i + 1,
                    'order_id'           => '<span class="fw-bold">' . e($row->order_id) . '</span>',
                    'transaction_id'     => e($row->transaction_id ?? '-'),
                    'transaction_status' => $statusBadge,
                    'payment_type'       => '<span class="badge badge-light-info">' . e($row->payment_type ?? '-') . '</span>',
                    'gross_amount'       => $amount,
                    'transaction_time'   => $row->transaction_time ? \Carbon\Carbon::parse($row->transaction_time)->format('d M Y H:i') : '-',
                    'aksi' => '
                        <button class="btn btn-sm btn-light-primary btn-sync-row me-1" data-order="' . e($row->order_id) . '" title="Sync Status">
                            <i class="fa fa-sync-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-light-info btn-detail-row" data-order="' . e($row->order_id) . '" title="Detail">
                            <i class="fa fa-eye"></i>
                        </button>',
                ];
            }

            return response()->json([
                'draw'            => (int) $request->get('draw'),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $rows,
            ]);
        } catch (\Exception $e) {
            return response()->json(['draw' => (int)$request->get('draw'), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => $e->getMessage()]);
        }
    }

    /**
     * Fetch a list of transactions directly from Midtrans API by multiple order IDs.
     * Midtrans does not expose a "list all" endpoint — so we fetch each order_id stored locally.
     * For bulk refresh, iterates all local order_ids and re-syncs status from Midtrans.
     */
    public function fetchMidtransTransactionsAction(Request $request)
    {
        if (!$request->session()->has('id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
        if (!$config || empty($config->server_key)) {
            return response()->json(['success' => false, 'message' => 'Midtrans belum dikonfigurasi. Simpan server key terlebih dahulu.']);
        }

        if (!Schema::hasTable('app_midtrans_transaction')) {
            return response()->json(['success' => false, 'message' => 'Tabel transaksi belum tersedia. Jalankan migration terlebih dahulu.']);
        }

        $baseUrl = $config->environment === 'production'
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        // Ambil semua order_id dari DB lokal lalu re-fetch status dari Midtrans
        $orderIds = DB::table('app_midtrans_transaction')->pluck('order_id');

        $synced  = 0;
        $failed  = 0;
        $errors  = [];

        foreach ($orderIds as $orderId) {
            try {
                $response = Http::withBasicAuth($config->server_key, '')
                    ->timeout(10)
                    ->get($baseUrl . '/v2/' . $orderId . '/status');

                $res = $response->json();

                if (!isset($res['transaction_status'])) {
                    $failed++;
                    $errors[] = $orderId . ': ' . ($res['status_message'] ?? 'No status returned');
                    continue;
                }

                $payload = [
                    'transaction_id'     => $res['transaction_id'] ?? null,
                    'transaction_status' => $res['transaction_status'] ?? null,
                    'payment_type'       => $res['payment_type'] ?? null,
                    'gross_amount'       => isset($res['gross_amount']) ? (float) $res['gross_amount'] : null,
                    'currency'           => $res['currency'] ?? 'IDR',
                    'fraud_status'       => $res['fraud_status'] ?? null,
                    'status_message'     => $res['status_message'] ?? null,
                    'bank'               => $res['bank'] ?? null,
                    'masked_card'        => $res['masked_card'] ?? null,
                    'approval_code'      => $res['approval_code'] ?? null,
                    'raw_response'       => json_encode($res),
                    'transaction_time'   => isset($res['transaction_time']) ? \Carbon\Carbon::parse($res['transaction_time']) : null,
                    'settlement_time'    => isset($res['settlement_time']) ? \Carbon\Carbon::parse($res['settlement_time']) : null,
                    'updated_by'         => session('nama'),
                    'updated_at'         => now(),
                ];

                DB::table('app_midtrans_transaction')
                    ->where('order_id', $orderId)
                    ->update($payload);

                $synced++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = $orderId . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Selesai. Berhasil sync: {$synced}, Gagal: {$failed}.",
            'synced'  => $synced,
            'failed'  => $failed,
            'errors'  => $errors,
        ]);
    }

    public function syncTransaksiAction(Request $request)
    {
        if (!$request->session()->has('id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), ['order_id' => 'required|string']);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
        if (!$config || empty($config->server_key)) {
            return response()->json(['success' => false, 'message' => 'Midtrans not configured']);
        }

        $baseUrl = $config->environment === 'production'
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        try {
            $response = Http::withBasicAuth($config->server_key, '')
                ->timeout(15)
                ->get($baseUrl . '/v2/' . $request->order_id . '/status');

            $res = $response->json();

            if (!isset($res['transaction_status'])) {
                return response()->json(['success' => false, 'message' => $res['status_message'] ?? 'Failed to get status']);
            }

            $exists = DB::table('app_midtrans_transaction')->where('order_id', $res['order_id'] ?? $request->order_id)->exists();

            $payload = [
                'transaction_id'     => $res['transaction_id'] ?? null,
                'transaction_status' => $res['transaction_status'] ?? null,
                'payment_type'       => $res['payment_type'] ?? null,
                'gross_amount'       => isset($res['gross_amount']) ? (float) $res['gross_amount'] : null,
                'currency'           => $res['currency'] ?? 'IDR',
                'fraud_status'       => $res['fraud_status'] ?? null,
                'status_message'     => $res['status_message'] ?? null,
                'bank'               => $res['bank'] ?? null,
                'masked_card'        => $res['masked_card'] ?? null,
                'approval_code'      => $res['approval_code'] ?? null,
                'raw_response'       => json_encode($res),
                'transaction_time'   => isset($res['transaction_time']) ? \Carbon\Carbon::parse($res['transaction_time']) : null,
                'settlement_time'    => isset($res['settlement_time']) ? \Carbon\Carbon::parse($res['settlement_time']) : null,
                'updated_by'         => session('nama'),
                'updated_at'         => now(),
            ];

            if ($exists) {
                DB::table('app_midtrans_transaction')
                    ->where('order_id', $res['order_id'] ?? $request->order_id)
                    ->update($payload);
            } else {
                $payload['order_id']    = $res['order_id'] ?? $request->order_id;
                $payload['created_by']  = session('nama');
                $payload['created_at']  = now();
                DB::table('app_midtrans_transaction')->insert($payload);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction synced: ' . ($res['transaction_status'] ?? '-'),
                'status'  => $res['transaction_status'] ?? '-',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function getStatusBadge(?string $status): string
    {
        $map = [
            'pending'        => 'warning',
            'settlement'     => 'success',
            'capture'        => 'success',
            'cancel'         => 'danger',
            'deny'           => 'danger',
            'expire'         => 'secondary',
            'refund'         => 'info',
            'partial_refund' => 'info',
            'authorize'      => 'primary',
        ];
        $color = $map[strtolower($status ?? '')] ?? 'secondary';
        return '<span class="badge badge-light-' . $color . '">' . strtoupper($status ?? '-') . '</span>';
    }

    public function updateMidtransConfigAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $menu_aktif = 'midtrans-config||setting-group';
            $cek = $this->dataService->cekPermit($menu_aktif, Session::getFacadeRoot());
            if (!$cek['u']) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 422);
            }

            $validator = Validator::make($request->all(), [
                'server_key'            => 'required|string',
                'client_key'            => 'required|string',
                'environment'           => 'required|in:sandbox,production',
                'payment_types'         => 'required|array|min:1',
                'payment_types.*'       => 'string',
                'merchant_id'           => 'nullable|string|max:100',
                'webhook_url'           => 'nullable|url|max:500',
                'finish_redirect_url'   => 'nullable|url|max:500',
                'unfinish_redirect_url' => 'nullable|url|max:500',
                'error_redirect_url'    => 'nullable|url|max:500',
                'is_active'             => 'required|in:Y,N',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $updateData = [
                'server_key'            => $request->server_key,
                'client_key'            => $request->client_key,
                'environment'           => $request->environment,
                'payment_types'         => json_encode($request->payment_types),
                'merchant_id'           => $request->merchant_id,
                'webhook_url'           => $request->webhook_url,
                'finish_redirect_url'   => $request->finish_redirect_url,
                'unfinish_redirect_url' => $request->unfinish_redirect_url,
                'error_redirect_url'    => $request->error_redirect_url,
                'is_active'             => $request->is_active,
                'updated_by'            => session('nama'),
                'updated_at'            => now(),
            ];

            $exists = DB::table('app_midtrans_config')->where('id_midtrans', 1)->exists();
            if ($exists) {
                $dt_exist = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
                DB::table('app_midtrans_config')->where('id_midtrans', 1)->update($updateData);
            } else {
                $updateData['created_by'] = session('nama');
                $updateData['created_at'] = now();
                DB::table('app_midtrans_config')->insert($updateData);
                $dt_exist = null;
            }

            $this->dataService->createLog($request, 'updateMidtransConfigAction', 'Midtrans config updated', json_encode($updateData), json_encode($dt_exist));
            return response()->json(['success' => true, 'message' => 'Midtrans configuration saved successfully']);
        }
    }

    public function testConnectionAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            if (!$config || empty($config->server_key)) {
                return response()->json(['success' => false, 'message' => 'Server key not configured yet']);
            }
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(10)->get($baseUrl . '/v2/payment-types');
                if ($response->status() === 401) return response()->json(['success' => false, 'message' => 'Invalid server key (401 Unauthorized)']);
                return response()->json(['success' => true, 'message' => 'Connection successful! Environment: ' . strtoupper($config->environment), 'status_code' => $response->status()]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
            }
        }
    }

    public function getTransactionStatusAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), ['order_id' => 'required|string']);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            if (!$config || empty($config->server_key)) return response()->json(['success' => false, 'message' => 'Midtrans not configured']);
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->get($baseUrl . '/v2/' . $request->order_id . '/status');
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }

    public function approveTransactionAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), ['order_id' => 'required|string']);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config  = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->post($baseUrl . '/v2/' . $request->order_id . '/approve');
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }

    public function cancelTransactionAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), ['order_id' => 'required|string']);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config  = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->post($baseUrl . '/v2/' . $request->order_id . '/cancel');
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }

    public function refundTransactionAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), ['order_id' => 'required|string', 'amount' => 'required|numeric|min:1', 'reason' => 'required|string|max:255']);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config  = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->post($baseUrl . '/v2/' . $request->order_id . '/refund', ['amount' => (int) $request->amount, 'reason' => $request->reason]);
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }

    public function expireTransactionAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), ['order_id' => 'required|string']);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config  = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->post($baseUrl . '/v2/' . $request->order_id . '/expire');
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }

    /**
     * Create Snap token, open payment popup, and save order to DB immediately.
     * Saving to DB first (status=pending) ensures the order is trackable even if
     * the user closes the browser before completing payment.
     */
    public function createSnapTokenAction(Request $request)
    {
        if (!$request->session()->has('id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'order_id'   => 'required|string|max:100',
            'amount'     => 'required|numeric|min:1',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
        if (!$config || empty($config->server_key)) {
            return response()->json(['success' => false, 'message' => 'Midtrans belum dikonfigurasi. Simpan server key terlebih dahulu.']);
        }
        if ($config->is_active !== 'Y') {
            return response()->json(['success' => false, 'message' => 'Midtrans tidak aktif. Aktifkan konfigurasi terlebih dahulu.']);
        }

        // Check duplicate order_id
        if (Schema::hasTable('app_midtrans_transaction')) {
            if (DB::table('app_midtrans_transaction')->where('order_id', $request->order_id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Order ID sudah ada. Gunakan Order ID yang berbeda.']);
            }
        }

        $baseUrl = $config->environment === 'production'
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';

        $enabledPayments = json_decode($config->payment_types, true) ?? [];

        $payload = [
            'transaction_details' => [
                'order_id'    => $request->order_id,
                'gross_amount' => (int) $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name ?? '',
                'email'      => $request->email,
                'phone'      => $request->phone,
            ],
            'callbacks' => [
                'finish'   => $config->finish_redirect_url   ?: url('/'),
                'unfinish' => $config->unfinish_redirect_url ?: url('/'),
                'error'    => $config->error_redirect_url    ?: url('/'),
            ],
        ];

        // Only send enabled_payments if configured — empty array causes Midtrans error
        if (!empty($enabledPayments)) {
            $payload['enabled_payments'] = $enabledPayments;
        }

        try {
            $response = Http::withBasicAuth($config->server_key, '')
                ->timeout(20)
                ->post($baseUrl . '/snap/v1/transactions', $payload);

            $res = $response->json();

            // Midtrans returns token + redirect_url on success
            if (!isset($res['token'])) {
                $errMsg = $res['error_messages'][0] ?? ($res['message'] ?? 'Gagal mendapatkan token dari Midtrans.');
                return response()->json(['success' => false, 'message' => $errMsg, 'midtrans_response' => $res]);
            }

            // Save pending order to DB immediately
            if (Schema::hasTable('app_midtrans_transaction')) {
                DB::table('app_midtrans_transaction')->insert([
                    'order_id'           => $request->order_id,
                    'transaction_id'     => null,
                    'transaction_status' => 'pending',
                    'payment_type'       => null,
                    'gross_amount'       => (float) $request->amount,
                    'currency'           => 'IDR',
                    'fraud_status'       => null,
                    'status_message'     => 'Snap token generated — awaiting payment',
                    'raw_response'       => json_encode($res),
                    'transaction_time'   => now(),
                    'settlement_time'    => null,
                    'bank'               => null,
                    'masked_card'        => null,
                    'approval_code'      => null,
                    'created_by'         => session('nama'),
                    'created_at'         => now(),
                    'updated_by'         => session('nama'),
                    'updated_at'         => now(),
                ]);
            }

            return response()->json([
                'success'      => true,
                'token'        => $res['token'],
                'redirect_url' => $res['redirect_url'] ?? null,
                'data'         => $res,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    public function createChargeAction(Request $request)
    {
        if (!$request->session()->has('id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|string',
            'order_id'     => 'required|string',
            'amount'       => 'required|numeric|min:1',
            'first_name'   => 'required|string|max:100',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
        if (!$config || empty($config->server_key)) {
            return response()->json(['success' => false, 'message' => 'Midtrans belum dikonfigurasi.']);
        }
        if ($config->is_active !== 'Y') {
            return response()->json(['success' => false, 'message' => 'Midtrans tidak aktif.']);
        }

        $baseUrl = $config->environment === 'production'
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        $payload = [
            'payment_type'        => $request->payment_type,
            'transaction_details' => [
                'order_id'     => $request->order_id,
                'gross_amount' => (int) $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
            ],
        ];

        // Map payment type to bank key for bank_transfer payments
        $bankMap = [
            'bca_va'     => 'bca',
            'bni_va'     => 'bni',
            'bri_va'     => 'bri',
            'permata_va' => 'permata',
            'other_va'   => 'other',
        ];

        if (isset($bankMap[$request->payment_type])) {
            $payload['bank_transfer'] = ['bank' => $bankMap[$request->payment_type]];
        }

        try {
            $response = Http::withBasicAuth($config->server_key, '')
                ->timeout(15)
                ->post($baseUrl . '/v2/charge', $payload);

            $res = $response->json();

            if (isset($res['status_code']) && !in_array($res['status_code'], ['200', '201', '202'])) {
                $errMsg = $res['status_message'] ?? 'Charge gagal.';
                return response()->json(['success' => false, 'message' => $errMsg, 'midtrans_response' => $res]);
            }

            // Save to DB
            if (Schema::hasTable('app_midtrans_transaction') && isset($res['order_id'])) {
                $exists = DB::table('app_midtrans_transaction')->where('order_id', $res['order_id'])->exists();
                $dbPayload = [
                    'transaction_id'     => $res['transaction_id'] ?? null,
                    'transaction_status' => $res['transaction_status'] ?? 'pending',
                    'payment_type'       => $res['payment_type'] ?? $request->payment_type,
                    'gross_amount'       => isset($res['gross_amount']) ? (float) $res['gross_amount'] : (float) $request->amount,
                    'currency'           => $res['currency'] ?? 'IDR',
                    'fraud_status'       => $res['fraud_status'] ?? null,
                    'status_message'     => $res['status_message'] ?? null,
                    'raw_response'       => json_encode($res),
                    'transaction_time'   => now(),
                    'updated_by'         => session('nama'),
                    'updated_at'         => now(),
                ];

                if ($exists) {
                    DB::table('app_midtrans_transaction')->where('order_id', $res['order_id'])->update($dbPayload);
                } else {
                    $dbPayload['order_id']   = $res['order_id'];
                    $dbPayload['created_by'] = session('nama');
                    $dbPayload['created_at'] = now();
                    DB::table('app_midtrans_transaction')->insert($dbPayload);
                }
            }

            return response()->json(['success' => true, 'data' => $res]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
