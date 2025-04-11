<!-- Data Source: {{ Cache::has('last_inbox_messages') ? 'Cached' : 'Fresh' }} -->
<div class="list-group list-group-flush">
    @php
        use App\Models\Inbox;
        use Carbon\Carbon;
        use Illuminate\Support\Str;
        
        $messages = Cache::remember('last_inbox_messages', 120, function () {
            return Inbox::with('sender')
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
        });
    @endphp

    @foreach($messages as $date => $dateMessages)
        <div class="list-group-item border-start-4 border-start-secondary bg-body-tertiary text-center fw-bold text-body-secondary text-uppercase small" data-coreui-i18n="{{ strtolower($date) }}">
            {{ $date }}
        </div>

        @foreach($dateMessages as $message)
         <a href="{{ route('inbox.thread', $message->id) }}" style="text-decoration: none">
            <div class="list-group-item border-start-4 {{ $message->priority_status == 3 ? 'border-start-danger' : ($message->priority_status == 2 ? 'border-start-warning' : '') }} list-group-item-divider">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">{{ $message->subject }}</h6>
                    <small>{{ Carbon::parse($message->created_at)->format('H:i') }}</small>
                </div>
  
                <small>From: {{ $message->sender->name }}</small>
            </div>
        </a>
        @endforeach
    @endforeach
</div>