<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Helpers\FeatureAccess;

class RebuildUserPermissionsCache
{
    public function handle(Login $event)
    {
        FeatureAccess::rebuildCache($event->user->id);
    }
}