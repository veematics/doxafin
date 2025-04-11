<?php

namespace App\View\Components;

use App\Models\InboxMessage;
use Carbon\Carbon;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class LastMessages extends Component
{
    public $messages;

    public function __construct()
    {
        if (request()->get('no_cache') == 1) {
            Cache::forget('last_inbox_messages');
        }
        
        $this->messages = Cache::remember('last_inbox_messages', 120, function () {
            return $this->getMessages();
        });
    }

    private function getMessages()
    {
        return InboxMessage::with('sender')
            ->where('sent_to', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->groupBy(function($message) {
                $date = Carbon::parse($message->created_at)->startOfDay();
                $now = Carbon::now()->startOfDay();
                
                if ($date->eq($now)) {
                    return 'Today';
                } elseif ($date->eq($now->copy()->subDay())) {
                    return 'Yesterday';
                }
                return $date->format('M d, Y');
            });
    }

    public function render()
    {
        return view('components.last-messages');
    }
}