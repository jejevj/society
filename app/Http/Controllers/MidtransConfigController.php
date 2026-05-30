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

        // Safe tabCounts — works even if migration has not been run yet
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

    public function createSnapTokenAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|string', 'amount' => 'required|numeric|min:1',
                'first_name' => 'required|string|max:100', 'last_name' => 'nullable|string|max:100',
                'email' => 'required|email|max:255', 'phone' => 'required|string|max:20',
            ]);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            if (!$config || empty($config->server_key)) return response()->json(['success' => false, 'message' => 'Midtrans not configured']);
            $baseUrl = $config->environment === 'production' ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
            $payload = [
                'transaction_details' => ['order_id' => $request->order_id, 'gross_amount' => (int) $request->amount],
                'customer_details'    => ['first_name' => $request->first_name, 'last_name' => $request->last_name ?? '', 'email' => $request->email, 'phone' => $request->phone],
                'enabled_payments'    => json_decode($config->payment_types, true) ?? [],
                'callbacks'           => ['finish' => $config->finish_redirect_url ?: url('/'), 'unfinish' => $config->unfinish_redirect_url ?: url('/'), 'error' => $config->error_redirect_url ?: url('/')],
            ];
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->post($baseUrl . '/snap/v1/transactions', $payload);
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }

    public function createChargeAction(Request $request)
    {
        if ($request->session()->has('id')) {
            $validator = Validator::make($request->all(), [
                'payment_type' => 'required|string', 'order_id' => 'required|string',
                'amount' => 'required|numeric|min:1', 'first_name' => 'required|string|max:100',
                'email' => 'required|email|max:255', 'phone' => 'required|string|max:20',
            ]);
            if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            $config = DB::table('app_midtrans_config')->where('id_midtrans', 1)->first();
            if (!$config || empty($config->server_key)) return response()->json(['success' => false, 'message' => 'Midtrans not configured']);
            $baseUrl = $config->environment === 'production' ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
            $payload = [
                'payment_type'        => $request->payment_type,
                'transaction_details' => ['order_id' => $request->order_id, 'gross_amount' => (int) $request->amount],
                'customer_details'    => ['first_name' => $request->first_name, 'email' => $request->email, 'phone' => $request->phone],
            ];
            if (in_array($request->payment_type, ['bca_va','bni_va','bri_va','permata_va','other_va'])) {
                $payload['bank_transfer'] = ['bank' => str_replace('_va', '', $request->payment_type)];
            }
            try {
                $response = Http::withBasicAuth($config->server_key, '')->timeout(15)->post($baseUrl . '/v2/charge', $payload);
                return response()->json(['success' => true, 'data' => $response->json()]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()]); }
        }
    }
}
