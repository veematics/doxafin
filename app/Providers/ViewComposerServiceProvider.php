<?php

namespace App\Providers;

use App\Models\AppSetup;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $appSetup = AppSetup::firstOrCreate(
                ['AppsID' => 1],
                [
                    'AppsName' => 'DoxaApp',
                    'AppsTitle' => 'Doxa Application',
                    'AppsSubTitle' => 'Enterprise Application',
                ]
            );
            $view->with('appSetup', $appSetup);
        });
    }
}