<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Reply to: {{ $message->subject }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-light rounded mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>
                                        <strong>From:</strong> {{ $message->sender ? $message->sender->name : 'System' }}
                                    </span>
                                    <small class="text-muted">{{ $message->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                <div>
                                    {!! nl2br(e($message->message)) !!}
                                </div>
                            </div>

                            <form action="{{ route('inbox.reply', $message->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Reply</label>
                                    <textarea name="message" id="message" rows="6" class="form-control" required></textarea>
                                    <small class="form-text text-muted">
                                        Support: new line, and auto link for url
                                    </small>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('inbox.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>