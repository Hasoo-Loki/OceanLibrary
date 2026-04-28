<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View as ViewFacade; // ← Pakai alias
use App\Models\Notifikasi;

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
        // Share data notifikasi ke layout admin
        ViewFacade::composer('layouts.admin', function ($view) {
            if (auth()->check() && auth()->user()->role == 'admin') {
                $view->with('jml_notif_belum_dibaca', Notifikasi::where('dibaca', false)->count());
                $view->with('notif_terbaru', Notifikasi::with('user', 'book')->latest()->limit(5)->get());
            }
        });
    }
}