<?php

namespace Midtrans;

/**
 * Midtrans Config
 * Custom library - no composer package required
 */
class Config
{
    /** @var string Midtrans server key */
    public static $serverKey = '';

    /** @var string Midtrans client key */
    public static $clientKey = '';

    /** @var bool Production or Sandbox */
    public static $isProduction = false;

    /** @var bool Sanitize request */
    public static $isSanitized = true;

    /** @var bool 3DS enabled */
    public static $is3ds = true;

    /** @var string|null Override SNAP URL */
    public static $overrideNotifUrl = null;

    /** @var string|null Append notification URL */
    public static $appendNotifUrl = null;

    const SANDBOX_BASE_URL    = 'https://api.sandbox.midtrans.com';
    const PRODUCTION_BASE_URL = 'https://api.midtrans.com';

    const SANDBOX_SNAP_URL    = 'https://app.sandbox.midtrans.com/snap/v1';
    const PRODUCTION_SNAP_URL = 'https://app.midtrans.com/snap/v1';

    /**
     * Get base API URL based on environment
     */
    public static function getBaseUrl(): string
    {
        return static::$isProduction
            ? self::PRODUCTION_BASE_URL
            : self::SANDBOX_BASE_URL;
    }

    /**
     * Get SNAP API URL based on environment
     */
    public static function getSnapUrl(): string
    {
        return static::$isProduction
            ? self::PRODUCTION_SNAP_URL
            : self::SANDBOX_SNAP_URL;
    }

    /**
     * Get Authorization header value (Base64 encoded server key)
     */
    public static function getAuthHeader(): string
    {
        return 'Basic ' . base64_encode(static::$serverKey . ':');
    }
}
