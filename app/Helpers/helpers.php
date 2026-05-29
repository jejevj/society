<?php

if (!function_exists('storage_url')) {
    /**
     * Generate storage URL with /ldt-asset/storage prefix
     * 
     * @param string $path
     * @return string
     */
    function storage_url($path)
    {
        // Remove leading slash if exists
        $path = ltrim($path, '/');
        
        // Return URL with /ldt-asset/storage prefix
        return url('/ldt-asset/storage/' . $path);
    }
}

if (!function_exists('asset')) {
    /**
     * Override asset() to handle storage paths
     * 
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        // If path starts with 'storage/', rewrite to 'ldt-asset/storage/'
        if (str_starts_with($path, 'storage/')) {
            $path = 'ldt-asset/' . $path;
        }
        
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('url')) {
    /**
     * Override url() to handle storage paths
     * 
     * @param string|null $path
     * @param mixed $parameters
     * @param bool|null $secure
     * @return string|\Illuminate\Contracts\Routing\UrlGenerator
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app('url');
        }

        // If path starts with 'storage/', rewrite to 'ldt-asset/storage/'
        if (str_starts_with($path, 'storage/')) {
            $path = 'ldt-asset/' . $path;
        }

        return app('url')->to($path, $parameters, $secure);
    }
}
