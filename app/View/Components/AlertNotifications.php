<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\InboxMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class AlertNotifications extends Component
{
    public $alerts;

    public function __construct($noCache = false)
    {
        $query = InboxMessage::with('sender')
            ->where('sent_to', Auth::id())
            ->where('is_read', 0)
            ->where('priority_status', '>', 1)
            ->orderBy('created_at', 'desc');

        if ($noCache) {
            $this->alerts = $query->get();
        } else {
            $this->alerts = Cache::remember('inbox_alerts_'.Auth::id(), 120, function () use ($query) {
                return $query->get();
            });
        }
    }

    public function render()
    {
        return view('components.alert-notifications');
    }
}
