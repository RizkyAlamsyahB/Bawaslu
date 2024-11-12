<?php

namespace App\Providers;

use App\Models\TipePemilihan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // AppServiceProvider.php
    public function boot()
    {
        View::composer('components.sidebar', function ($view) {
            $view->with('tipePemilihans', TipePemilihan::all());
        });
    }
}
