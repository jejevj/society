<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RewriteStorageUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process HTML responses
        if ($response->headers->get('Content-Type') && 
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            
            $content = $response->getContent();
            
            // Rewrite /storage/ to /ldt-asset/storage/
            $content = preg_replace(
                '/(href|src)=["\']([^"\']*?)\/storage\//',
                '$1="$2/ldt-asset/storage/',
                $content
            );
            
            $response->setContent($content);
        }

        return $response;
    }
}
