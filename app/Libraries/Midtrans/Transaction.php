<?php

namespace Midtrans;

use Illuminate\Support\Facades\Http;

/**
 * Midtrans Transaction
 * Custom library - no composer package required
 */
class Transaction
{
    /**
     * Get transaction status by order ID
     *
     * @param  string $orderId
     * @return object
     * @throws \Exception
     */
    public static function status(string $orderId): object
    {
        $url = Config::getBaseUrl() . '/v2/' . $orderId . '/status';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Accept'        => 'application/json',
        ])
        ->timeout(15)
        ->get($url);

        if ($response->failed()) {
            $body = $response->json();
            throw new \Exception($body['status_message'] ?? 'Failed to get transaction status. HTTP ' . $response->status());
        }

        return (object) $response->json();
    }

    /**
     * Approve a transaction (for credit card pre-authorize)
     *
     * @param  string $orderId
     * @return object
     * @throws \Exception
     */
    public static function approve(string $orderId): object
    {
        $url = Config::getBaseUrl() . '/v2/' . $orderId . '/approve';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Accept'        => 'application/json',
        ])
        ->timeout(15)
        ->post($url);

        if ($response->failed()) {
            $body = $response->json();
            throw new \Exception($body['status_message'] ?? 'Failed to approve transaction.');
        }

        return (object) $response->json();
    }

    /**
     * Cancel a transaction
     *
     * @param  string $orderId
     * @return object
     * @throws \Exception
     */
    public static function cancel(string $orderId): object
    {
        $url = Config::getBaseUrl() . '/v2/' . $orderId . '/cancel';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Accept'        => 'application/json',
        ])
        ->timeout(15)
        ->post($url);

        if ($response->failed()) {
            $body = $response->json();
            throw new \Exception($body['status_message'] ?? 'Failed to cancel transaction.');
        }

        return (object) $response->json();
    }

    /**
     * Expire a transaction
     *
     * @param  string $orderId
     * @return object
     * @throws \Exception
     */
    public static function expire(string $orderId): object
    {
        $url = Config::getBaseUrl() . '/v2/' . $orderId . '/expire';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Accept'        => 'application/json',
        ])
        ->timeout(15)
        ->post($url);

        if ($response->failed()) {
            $body = $response->json();
            throw new \Exception($body['status_message'] ?? 'Failed to expire transaction.');
        }

        return (object) $response->json();
    }

    /**
     * Refund a transaction
     *
     * @param  string $orderId
     * @param  array  $params  ['amount' => int, 'reason' => string]
     * @return object
     * @throws \Exception
     */
    public static function refund(string $orderId, array $params = []): object
    {
        $url = Config::getBaseUrl() . '/v2/' . $orderId . '/refund';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])
        ->timeout(15)
        ->post($url, $params);

        if ($response->failed()) {
            $body = $response->json();
            throw new \Exception($body['status_message'] ?? 'Failed to refund transaction.');
        }

        return (object) $response->json();
    }

    /**
     * Charge a transaction via Core API
     *
     * @param  array $params
     * @return object
     * @throws \Exception
     */
    public static function charge(array $params): object
    {
        $url = Config::getBaseUrl() . '/v2/charge';

        $response = Http::withHeaders([
            'Authorization' => Config::getAuthHeader(),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])
        ->timeout(30)
        ->post($url, $params);

        if ($response->failed()) {
            $body = $response->json();
            $errorMsg = isset($body['error_messages'])
                ? implode(', ', (array) $body['error_messages'])
                : ($body['status_message'] ?? 'Charge failed.');
            throw new \Exception($errorMsg);
        }

        return (object) $response->json();
    }
}
