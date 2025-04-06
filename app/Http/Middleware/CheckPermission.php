<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $featureCode, $permission)
    {
        if (!auth()->user()->hasPermission($featureCode, $permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}