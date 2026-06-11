# Midtrans Implementation Guide — Society Event Platform
> Dokumen ini mendeskripsikan implementasi Midtrans **tanpa Composer package** (tanpa `midtrans/midtrans-php`) di proyek Society Event (Laravel 10.x). Semua komunikasi ke Midtrans dilakukan via **Laravel HTTP Client (`Illuminate\Support\Facades\Http`)** langsung ke Midtrans REST API.
>
> File utama: `app/Http/Controllers/MidtransConfigController.php`  
> Diperbarui: 2026-06-11

---

## 1. Pendekatan: Tanpa Composer Package

Mayoritas tutorial Midtrans di Laravel menggunakan package resmi:

```bash
composer require midtrans/midtrans-php
```

Implementasi ini **sengaja tidak menggunakan package tersebut** dan menggantinya dengan:

```php
use Illuminate\Support\Facades\Http;

Http::withBasicAuth($config->server_key, '')
    ->timeout(30)
    ->post($snapApiUrl, $payload);
```

### Keuntungan pendekatan ini
- Tidak ada dependency tambahan — ringan dan transparan
- Tidak perlu `\Midtrans\Snap::getSnapToken()` atau konfigurasi global `Config::$serverKey`
- Lebih mudah dikontrol per-request (timeout, retry, logging)
- Server key diambil langsung dari DB (`app_midtrans_config`), bukan dari `.env` hardcode

### Kelemahan yang perlu diperhatikan
- Tidak ada validasi signature notifikasi bawaan — **harus implementasi manual** di webhook
- Tidak ada method helper seperti `Notification::createFromPayment()` — semua parsing JSON dilakukan manual
- Jika Midtrans API berubah, tidak ada auto-update dari package

---

## 2. Konfigurasi Midtrans (Database-Driven)

Konfigurasi disimpan di tabel `app_midtrans_config` (row id=1), **bukan di `.env`**.

### Kolom tabel `app_midtrans_config`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id_midtrans` | int | Primary key (selalu 1) |
| `server_key` | string | Midtrans Server Key (Sandbox/Production) |
| `client_key` | string | Midtrans Client Key (untuk SNAP JS di frontend) |
| `environment` | enum(`sandbox`,`production`) | Menentukan base URL API |
| `payment_types` | json | Array payment method yang diaktifkan |
| `merchant_id` | string | Merchant ID dari Midtrans Dashboard |
| `webhook_url` | string | URL notifikasi (belum dikonfirmasi active — lihat Gap) |
| `finish_redirect_url` | string | Redirect setelah pembayaran sukses |
| `unfinish_redirect_url` | string | Redirect jika pembayaran belum selesai |
| `error_redirect_url` | string | Redirect jika pembayaran error |
| `is_active` | enum(`Y`,`N`) | Status aktif konfigurasi |

### Payment Methods yang Didukung

```php
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
```

---

## 3. Endpoint API yang Digunakan

Controller menggunakan base URL dinamis berdasarkan environment:

```php
// SNAP API
$snapApiUrl = $config->environment === 'production'
    ? 'https://api.midtrans.com/snap/v1/transactions'
    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

// Core API
$baseUrl = $config->environment === 'production'
    ? 'https://api.midtrans.com'
    : 'https://api.sandbox.midtrans.com';
```

### Ringkasan Endpoint

| Method | Endpoint | Fungsi |
|---|---|---|
| POST | `/snap/v1/transactions` | Generate SNAP token |
| POST | `/v2/charge` | Direct Charge (Core API) |
| GET | `/v2/{order_id}/status` | Cek status transaksi |
| POST | `/v2/{order_id}/approve` | Approve transaksi (pre-authorize) |
| POST | `/v2/{order_id}/cancel` | Cancel transaksi |
| POST | `/v2/{order_id}/refund` | Refund transaksi |
| POST | `/v2/{order_id}/expire` | Expire transaksi |
| GET | `/v2/payment-types` | Test koneksi (cek server key valid) |

---

## 4. Actions di Controller

### 4.1 `createSnapTokenAction` — SNAP Popup

Alur:
1. Validasi input: `order_id`, `amount`, `first_name`, `email`, `phone`
2. Ambil config dari DB
3. Cek duplikasi `order_id` di tabel lokal
4. Build payload + `enabled_payments` dari config
5. POST ke SNAP API dengan Basic Auth (`server_key` + password kosong)
6. Jika sukses → simpan ke `app_midtrans_transaction` dengan status `pending`
7. Return `token` + `redirect_url` ke frontend

```php
$response = Http::withBasicAuth($config->server_key, '')
    ->timeout(30)
    ->post($snapApiUrl, $payload);
```

Frontend menggunakan token ini untuk membuka SNAP Popup:
```javascript
snap.pay(token, { /* callbacks */ });
```

> **Client Key** untuk inisialisasi SNAP JS di view harus diambil dari `app_midtrans_config.client_key`, bukan dari `.env`.

---

### 4.2 `createChargeAction` — Direct Charge (Core API)

Berbeda dari SNAP, Direct Charge tidak menampilkan popup — langsung charge ke payment method yang dipilih.

Input tambahan: `payment_type`, `bank` (opsional untuk bank transfer).

```php
// Contoh untuk bank transfer BCA:
$payload = [
    'payment_type' => 'bank_transfer',
    'transaction_details' => ['order_id' => $orderId, 'gross_amount' => 150000],
    'bank_transfer' => ['bank' => 'bca'],
    'customer_details' => [...],
];
```

---

### 4.3 `fetchMidtransTransactionsAction` — Bulk Sync

> ⚠️ **Penting**: Midtrans **tidak menyediakan endpoint list semua transaksi**. Bulk sync dilakukan dengan cara loop seluruh `order_id` dari DB lokal, lalu hit `/v2/{order_id}/status` satu per satu.

```php
$orderIds = DB::table('app_midtrans_transaction')->pluck('order_id');

foreach ($orderIds as $orderId) {
    $response = Http::withBasicAuth($config->server_key, '')
        ->timeout(10)
        ->get($baseUrl . '/v2/' . $orderId . '/status');
    // ... update DB lokal
}
```

**Konsekuensi**: Semakin banyak transaksi, semakin lama proses sync (N HTTP requests). Untuk production dengan ribuan transaksi, ini akan sangat lambat dan berpotensi timeout. Solusi yang disarankan: **batasi sync ke transaksi yang statusnya masih `pending`** saja.

---

### 4.4 `syncTransaksiAction` — Single Sync per Order

Sync satu transaksi berdasarkan `order_id`. Jika transaksi belum ada di DB lokal, otomatis di-insert (upsert logic manual).

---

### 4.5 `getTableTransaksi` — DataTables Server-Side

Endpoint untuk DataTables dengan:
- Filter per `transaction_status` (tab: all, pending, settlement, cancel, expire, deny, refund)
- Search fulltext pada: `order_id`, `transaction_id`, `payment_type`, `transaction_status`
- Server-side pagination dan sorting

---

## 5. Tabel `app_midtrans_transaction`

Kolom yang digunakan dari response Midtrans:

| Kolom | Sumber |
|---|---|
| `order_id` | `transaction_details.order_id` |
| `transaction_id` | `res['transaction_id']` |
| `transaction_status` | `res['transaction_status']` |
| `payment_type` | `res['payment_type']` |
| `gross_amount` | `res['gross_amount']` (cast float) |
| `currency` | `res['currency']` (default: IDR) |
| `fraud_status` | `res['fraud_status']` |
| `bank` | `res['bank']` |
| `masked_card` | `res['masked_card']` |
| `approval_code` | `res['approval_code']` |
| `snap_token` | token dari SNAP API |
| `redirect_url` | redirect_url dari SNAP API |
| `raw_response` | `json_encode($res)` — full response disimpan |
| `transaction_time` | parsed via `Carbon::parse()` |
| `settlement_time` | parsed via `Carbon::parse()` |
| `created_by` / `updated_by` | `session('nama')` |

---

## 6. Authentication: Basic Auth

Semua request ke Midtrans menggunakan **HTTP Basic Auth** dengan `server_key` sebagai username dan **password kosong**:

```php
Http::withBasicAuth($config->server_key, '')
```

Ini adalah standar Midtrans — password memang dikosongkan. Midtrans mengenkode `server_key:` (dengan titik dua tanpa password) ke Base64 sebagai Authorization header.

---

## 7. Webhook / Notifikasi Otomatis (GAP — Belum Aktif)

> ⚠️ **Status: Belum dikonfirmasi ada endpoint webhook di routes**

Midtrans akan POST ke `webhook_url` saat status transaksi berubah (pembayaran diterima, expired, dll.). Controller saat ini **belum memiliki** method untuk menerima notifikasi ini.

### Yang perlu dibuat:

```php
// Tambahkan method baru di MidtransConfigController atau buat WebhookController terpisah
public function handleWebhookNotification(Request $request)
{
    // 1. Verifikasi signature
    $signatureKey = hash('sha512', 
        $request->order_id . 
        $request->status_code . 
        $request->gross_amount . 
        $config->server_key
    );
    
    if ($signatureKey !== $request->signature_key) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    // 2. Update status di DB lokal
    DB::table('app_midtrans_transaction')
        ->where('order_id', $request->order_id)
        ->update([
            'transaction_status' => $request->transaction_status,
            'fraud_status'       => $request->fraud_status,
            'raw_response'       => json_encode($request->all()),
            'updated_at'         => now(),
        ]);

    return response()->json(['message' => 'OK']);
}
```

**Route yang perlu ditambahkan** (harus tanpa middleware auth):
```php
Route::post('/webhook/midtrans', [MidtransConfigController::class, 'handleWebhookNotification'])
    ->name('webhook.midtrans')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

**URL yang perlu di-set di Midtrans Dashboard:**  
`https://your-domain.com/{APP_ROUTE}/webhook/midtrans`

---

## 8. Gap & Rekomendasi

| Item | Status | Rekomendasi |
|---|---|---|
| Webhook endpoint | ❌ Belum ada | Buat `handleWebhookNotification` + route tanpa CSRF |
| Signature verification | ❌ Belum ada | Implementasi di webhook handler (lihat section 7) |
| Bulk sync performance | ⚠️ Lambat untuk data besar | Filter hanya transaksi `pending` saat sync |
| Client Key di view | ⚠️ Perlu dicek | Pastikan SNAP JS `<script>` menggunakan `$config->client_key`, bukan hardcode |
| Server key enkripsi | ⚠️ Tersimpan plaintext di DB | Pertimbangkan enkripsi dengan `encrypt()`/`decrypt()` Laravel |
| Idempotency order_id | ✅ Sudah ada cek duplikasi | Baik |
| Sandbox/Production switch | ✅ Dynamic base URL | Baik |
| Timeout handling | ✅ Sudah set timeout per request | Baik |
| Raw response disimpan | ✅ `raw_response` kolom tersedia | Baik untuk debugging |

---

## 9. Cara Setup Awal

1. Pastikan migration `app_midtrans_config` dan `app_midtrans_transaction` sudah dijalankan
2. Buka halaman admin: `/society-event/midtrans-config`
3. Isi **Server Key** dan **Client Key** dari [Midtrans Dashboard](https://dashboard.midtrans.com)
4. Pilih environment: **Sandbox** untuk testing, **Production** untuk live
5. Pilih minimal satu payment method
6. Klik **Test Connection** untuk verifikasi server key valid
7. Simpan konfigurasi

### Cek koneksi via Test Connection
```
GET /v2/payment-types
→ 401 = server key salah
→ 200/405 = server key valid (405 karena endpoint tidak support GET, tapi auth berhasil)
```

---

> **Catatan Developer**: Karena tidak menggunakan package Midtrans resmi, pastikan selalu merujuk ke [dokumentasi Midtrans API](https://docs.midtrans.com) untuk perubahan endpoint atau payload structure. Simpan `raw_response` sangat berguna untuk debugging perbedaan response antar payment method.
