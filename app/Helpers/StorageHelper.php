<?php

namespace App\Helpers;

class StorageHelper
{
    /**
     * Generate storage URL with /ldt prefix
     * 
     * @param string $path
     * @return string
     */
    public static function url($path)
    {
        // Remove leading slash if exists
        $path = ltrim($path, '/');
        
        // Return URL with /ldt prefix
        return url('/ldt/storage/' . $path);
    }
}
