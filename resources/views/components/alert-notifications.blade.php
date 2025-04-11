@php
    use App\Models\InboxMessage;
    use Carbon\Carbon;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Request;

    // Change this line
    $cacheKey = 'AlertNotification_' . Auth::id();
    $cacheDuration = now()->addMinutes(5);

    $query = InboxMessage::with('sender')
        ->select('inbox_messages.*')
        ->where('sent_to', Auth::id())
        ->where('is_read', 0)
        ->where('priority_status', '>', 1)
        ->orderBy('created_at', 'desc');

    // Check for no_cache URL parameter
    if(Request::get('no_cache') == '1') {
        $alerts = $query->get();
        Cache::put($cacheKey, $alerts, $cacheDuration);
    } else {
        $alerts = Cache::remember($cacheKey, $cacheDuration, function () use ($query) {
            return $query->get();
        });
    }
@endphp

@if($alerts->isEmpty())
<div class="dropdown-menu dropdown-menu-end pt-0 show" style="min-width: 600px; display: block !important;">
    <div class="dropdown-item text-center py-3">
        <i class="fas fa-bell-slash me-2"></i>
        <span class="text-body-secondary">No Alert At This Moment</span>
    </div>
</div>
@else
    <div class="dropdown-menu dropdown-menu-end pt-0 show" style="min-width: 600px;">
        @foreach($alerts->groupBy('message_category') as $category => $messages)
            <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                {{ ucfirst($category) ?: 'Uncategorized' }}
            </div>
            @foreach($messages as $message)
                <a class="dropdown-item border-start-4 {{ $message->priority_status == 3 ? 'border-start-danger' : 'border-start-warning' }}" 
                   style="border-left-style:solid" 
                   href="{{ route('inbox.thread', $message->id) }}">
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <div class="fw-semibold">{{ Str::limit($message->subject, 50) }}</div>
                            @if(ucfirst($category)=="Personal")
                            <small class="text-body-secondary">From: {{ $message->sender->name }}</small>
                            @endif
                        </div>
                        <small class="text-body-secondary">{{ Carbon::parse($message->created_at)->format('H:i') }}</small>
                    </div>
                </a>
            @endforeach
        @endforeach
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBadge = document.getElementById('alertNotification');
    const hasAlerts = @json(!$alerts->isEmpty());

    if (alertBadge) {
        if (hasAlerts) {
            alertBadge.classList.add('bg-danger');
        } else {
            alertBadge.classList.remove('bg-danger');
        }
    }
});
</script>