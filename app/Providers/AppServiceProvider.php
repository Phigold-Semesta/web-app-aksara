<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon; // Menambahkan import Carbon

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
    public function boot(): void
    {
        // Mengatur locale ke bahasa Indonesia agar diffForHumans() dan format lainnya otomatis berbahasa Indonesia
        Carbon::setLocale('id');
    }
}