<?php

namespace Midtrans;

use Illuminate\Support\Facades\Http;

/**
 * Midtrans Snap
 * Custom library - no composer package required
 */
class Snap
{
    /**
     * Get SNAP token from Midtrans
     *
     * @param  array $params Transaction parameters
     * @return string SNAP token
     * @throws \Exception
     */
    public static function getSnapToken(array $params): string
    {
        $result = self::createTransaction($params);
        return $result->token ?? '';
    }

    /**
     * Get SNAP redirect URL from Midtrans
     *
     * @param  array $params Transaction parameters
     * @return string Redirect URL
     * @throws \Exception
     */
    public static function getSnapRedirectUrl(array $params): string
    {
        $result = self::createTransaction($params);
        return $result->redirect_url ?? '';
    }

    /**
     * Create SNAP transaction
     *
     * @param  array $params
     * @return object
     * @throws \Exception
     */
    public static function createTransaction(array $params): object
    {
        $url = Config::getSnapUrl() . '/transactions';

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
                : ($body['message'] ?? 'SNAP API request failed. HTTP ' . $response->status());
            throw new \Exception($errorMsg);
        }

        return (object) $response->json();
    }
}
