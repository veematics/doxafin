<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use App\Models\AppSetup;

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
        // Share cached AppSetup with all views
        View::composer('*', function ($view) {
            $appSetup = Cache::rememberForever('app_setup', function () {
                return AppSetup::first();
            });
            
            $view->with('appSetup', $appSetup);
        });

        // Listen for AppSetup model changes to invalidate cache
        AppSetup::updated(function () {
            Cache::forget('app_setup');
        });

        AppSetup::created(function () {
            Cache::forget('app_setup');
        });

        AppSetup::deleted(function () {
            Cache::forget('app_setup');
        });
    }
}
