<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share $set ke semua view yang menggunakan layouts header/footer web
        View::composer('*', function ($view) {
            try {
                $set = DB::table('app_setting')->where('id_setting', 1)->first();
            } catch (\Exception $e) {
                $set = null;
            }
            $view->with('set', $set);
        });
    }
}
