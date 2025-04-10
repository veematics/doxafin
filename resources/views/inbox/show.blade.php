<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 font-weight-bold mb-0">
                            Message Details
                        </h2>
                        <a href="{{ route('inbox.index') }}" class="btn btn-outline-secondary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-arrow-left"></use>
                            </svg> Back to Inbox
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">{{ $message->subject }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <strong>From:</strong> {{ $message->sender ? $message->sender->name : 'System' }}
                                </div>
                                <div>
                                    <small class="text-muted">{{ $message->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-4">
                                <p>{!! nl2br(e($message->message)) !!}</p>
                            </div>
                            <div class="d-flex">
                                <span class="badge me-2 
                                    @if($message->priority_status == 3)
                                        bg-danger
                                    @elseif($message->priority_status == 2)
                                        bg-warning text-dark
                                    @else
                                        bg-secondary
                                    @endif">
                                    @if($message->priority_status == 3)
                                        High Priority
                                    @elseif($message->priority_status == 2)
                                        Need Attention
                                    @else
                                        Normal
                                    @endif
                                </span>
                                <span class="badge bg-info">
                                    {{ ucfirst($message->message_category) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{ route('inbox.index') }}" class="btn btn-secondary me-2">Back</a>
                            <a href="{{ route('inbox.reply.form', $message->id) }}" class="btn btn-primary">Reply</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>