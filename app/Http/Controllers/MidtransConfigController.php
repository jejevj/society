<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

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

        $data = [
            'menu'            => 'Midtrans Configurations',
            'menu_aktif'      => $menu_aktif,
            'navbar'          => $navbar,
            'cek_permit'      => $cek,
            'config'          => $config,
            'allPaymentTypes' => $allPaymentTypes,
            'selectedTypes'   => $selectedTypes,
        ];

        if (!$cek['r']) {
            return view('admin-panel.error_page.403-page', $data);
        }
        return view('admin-panel.midtrans.main', $data);
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
