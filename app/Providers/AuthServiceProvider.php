<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // No policies registered
    ];

    public function boot()
    {
        $this->registerPolicies();
        // No gates defined
    }
}