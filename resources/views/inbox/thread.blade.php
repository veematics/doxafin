<x-app-layout>
    @php

 
    
    // Get route parameters
    $routeParams = request()->route()->parameters();
    $message = $routeParams['message'] ?? null;
    $messageId = $message ? $message->id : null;
  
     $readCheckpoint = App\Models\InboxMessage::where('id', $messageId)
                  ->first();
       // Authorization check
       if ($readCheckpoint && ($readCheckpoint->sent_from !== Auth::id() && $readCheckpoint->sent_to !== Auth::id())) {
        // Log unauthorized access attempt
        \Log::warning('Unauthorized inbox access attempt', [
            'user_id' => Auth::id(),
            'message_id' => $messageId,
            'ip' => request()->ip()
        ]);
        
        // Create a custom response
        $response = response()->make(
            view('errors.403', [
                'message' => "You're not allowed to access this page, we've logged this action for security audit"
            ]),
            403
        );
        
        // Send the response immediately
        $response->send();
        exit;
    }
     if ($readCheckpoint) {
        // Debug output
        //echo "Debug - Message ID: " . $readCheckpoint->id . ", Is Read: " . $readCheckpoint->getRawOriginal('is_read') . "<br>";
        
        // Update read status if message is unread
        if ($readCheckpoint->getRawOriginal('is_read') === 0 && $readCheckpoint->sent_from !== Auth::id()) {
            $readCheckpoint->is_read = 1;
            $readCheckpoint->save();

            // This will now work since keys match
            Cache::forget('AlertNotification_' . Auth::id());
            Cache::forget('last_inbox_messages_' . Auth::id());

        }
     } else {
         echo "Debug - No message found with ID: " . request()->route('id') . "<br>";
    }
    @endphp

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 font-weight-bold mb-0">
                            Message Thread: {{ $messages->first()->subject }}
                        </h2>
                        <a href="{{ route('inbox.index') }}" class="btn btn-secondary">
                            <svg class="icon me-1">
                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-arrow-left"></use>
                            </svg> Back to Inbox
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Thread messages -->
                            <div class="message-thread">
                                @foreach($messages as $msg)
                                    <div class="message-item {{ $msg->sent_from === Auth::id() ? 'sent-message' : 'received-message' }} mb-4">
                                        <div class="card {{ $msg->sent_from === Auth::id() ? 'border-primary' : '' }}">
                                            <div class="card-header {{ $msg->sent_from === Auth::id() ? 'bg-light' : 'bg-light-subtle' }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <strong>{{ $msg->sent_from === Auth::id() ? 'You' : $msg->sender->name }}</strong>
                                                        <small class="text-muted ms-2">
                                                            {{ $msg->sent_from === Auth::id() ? 'to '.$msg->recipient->name : '' }}
                                                        </small>
                                                    </span>
                                                    <small class="text-muted">{{ $msg->created_at->format('M d, Y h:i A') }}</small>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <p>{!! nl2br($msg->message) !!}</p>
                                                </div>
                                                <div class="d-flex">
                                                    @if($msg->priority_status == 3)
                                                        <span class="badge bg-danger me-2">High Priority</span>
                                                    @elseif($msg->priority_status == 2)
                                                        <span class="badge bg-warning text-dark me-2">Need Attention</span>
                                                    @else
                                                        <span class="badge bg-secondary me-2">Normal</span>
                                                    @endif
                                                    <span class="badge bg-info">
                                                        {{ ucfirst($msg->message_category) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Reply form -->
                            @if($messages->first()->sent_from !== 1)
                            <div class="mt-5">
                                <h5 class="mb-3">Reply to this thread</h5>
                                <form action="{{ route('inbox.reply', $messages->first()) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="message_category" value="{{ $messages->first()->message_category }}">
                                    <input type="hidden" name="redirect_to_thread" value="1">
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Your Reply</label>
                                        <textarea class="form-control" name="message" rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">
                                            <svg class="icon me-1">
                                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-reply"></use>
                                            </svg> Send Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .message-thread {
            display: flex;
            flex-direction: column;
        }
        
        .sent-message {
            margin-left: auto;
            margin-right: 0;
            width: 80%;
        }
        
        .received-message {
            margin-right: auto;
            margin-left: 0;
            width: 80%;
        }
        
        .message-item {
            position: relative;
            margin-bottom: 1rem;
        }
    </style>

        <a href="{{ route('inbox.index') }}" class="btn btn-secondary mt-4"><< Back to Inbox</a>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for reply parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('reply')) {
            // Show toast notification
            if (typeof window.toast !== 'undefined') {
                window.toast.show('Reply sent successfully', 'success');
            } else {
                // Fallback if toast functionality isn't available
                alert('Reply sent successfully');
            }
        }
    });
</script>

</x-app-layout>