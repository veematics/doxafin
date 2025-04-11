<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class DebugInfo extends Component
{
    public $user;
    public $userRole;
    public $userRoles;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->userRoles = $this->user?->roles()->pluck('name')->toArray() ?? [];
        $this->userRole = implode(', ', $this->userRoles);
    }

    public function render()
    {
        return view('components.debug-info');
    }
}