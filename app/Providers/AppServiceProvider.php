<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Add Blade directive for storage assets
        \Blade::directive('storage', function ($expression) {
            return "<?php echo url('/ldt-asset/storage/' . ltrim($expression, '/')); ?>";
        });
    }
}