<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Helpers\FeatureAccess;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated()
    {
        // Rebuild user permissions cache
        FeatureAccess::rebuildCache(auth()->id());

        // Clear and rebuild sidebar menu cache
        Cache::forget('sidebar_menu_items');
        Cache::rememberForever('sidebar_menu_items', function () {
            return Menu::where('name', 'Sidebar Menu')
                ->with(['menuItems' => function ($query) {
                    $query->orderBy('order')
                        ->with(['children' => function ($q) {
                            $q->orderBy('order');
                        }]);
                }])
                ->first()
                ->menuItems()
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
        });
    }

    protected function loggedOut()
    {
        Cache::forget('sidebar_menu_items');
    }
}