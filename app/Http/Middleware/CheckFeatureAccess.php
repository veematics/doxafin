<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\FeatureAccess;

class CheckFeatureAccess
{
    public function handle(Request $request, Closure $next, $feature, $permission)
    {
        if (!FeatureAccess::check(auth()->id(), $feature, $permission)) {
            abort(403, 'Unauthorized access');
        }
        return $next($request);
    }
}