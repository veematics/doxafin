<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;

class TranslationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Lang::addNamespace('custom', base_path('lang'));
    }
}