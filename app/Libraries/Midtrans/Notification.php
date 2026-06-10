<?php

namespace Midtrans;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

/**
 * Midtrans Notification
 * Custom library - no composer package required
 *
 * Usage:
 *   $notif = new \Midtrans\Notification();
 *   $txStatus = $notif->transaction_status;
 *   $orderId  = $notif->order_id;
 */
class Notification
{
    /** @var object Raw notification payload */
    protected object $response;

    /** @var string */
    public string $transaction_status = '';

    /** @var string */
    public string $order_id = '';

    /** @var string|null */
    public ?string $fraud_status = null;

    /** @var string|null */
    public ?string $payment_type = null;

    /** @var string|null */
    public ?string $transaction_id = null;

    /** @var string|null */
    public ?string $gross_amount = null;

    /** @var string|null */
    public ?string $currency = null;

    /** @var string|null */
    public ?string $status_message = null;

    /** @var string|null */
    public ?string $status_code = null;

    /** @var string|null */
    public ?string $signature_key = null;

    /**
     * Initialize and fetch verified notification from Midtrans
     *
     * @throws \Exception if signature is invalid or request fails
     */
    public function __construct()
    {
        // Read raw POST body
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        // Fallback to Laravel request
        if (empty($data)) {
            $data = Request::all();
        }

        $orderId = $data['order_id'] ?? null;
        if (!$orderId) {
            throw new \Exception('Notification: order_id not found in payload.');
        }

        // Verify by fetching status directly from Midtrans API
        $url = Config::getBaseUrl() . '/v2/' . $orderId . '/status';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Accept'        => 'application/json',
        ])
        ->timeout(15)
        ->get($url);

        if ($response->failed()) {
            throw new \Exception('Notification: Failed to verify transaction status from Midtrans API.');
        }

        $verified = $response->json();

        $this->response           = (object) $verified;
        $this->transaction_status = $verified['transaction_status'] ?? '';
        $this->order_id           = $verified['order_id']           ?? $orderId;
        $this->fraud_status       = $verified['fraud_status']       ?? null;
        $this->payment_type       = $verified['payment_type']       ?? null;
        $this->transaction_id     = $verified['transaction_id']     ?? null;
        $this->gross_amount       = $verified['gross_amount']       ?? null;
        $this->currency           = $verified['currency']           ?? null;
        $this->status_message     = $verified['status_message']     ?? null;
        $this->status_code        = $verified['status_code']        ?? null;
        $this->signature_key      = $data['signature_key']          ?? null;
    }

    /**
     * Magic getter for accessing any property from raw response
     */
    public function __get(string $name): mixed
    {
        return $this->response->{$name} ?? null;
    }
}
