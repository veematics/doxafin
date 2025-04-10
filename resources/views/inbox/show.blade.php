@extends('layouts.app')

@section('content')
<div class="container-lg px-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $message->subject }}</h5>
            @if ($message->canBeDeleted())
                <form action="{{ route('inbox.destroy', $message) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this message?')">
                        Delete
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <strong>From:</strong> 
                        {{ $message->isSystemMessage() ? 'System' : $message->sender->name }}
                    </div>
                    <div>
                        <small class="text-body-secondary">
                            {{ $message->created_at->format('M d, Y H:i') }}
                        </small>
                    </div>
                </div>
                <div class="message-content">
                    {!! nl2br(e($message->message)) !!}
                </div>
            </div>

            @if ($message->canBeRepliedTo())
                <div class="reply-form mt-4">
                    <h6>Reply</h6>
                    <form action="{{ route('inbox.reply', $message) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror" 
                                    placeholder="Type your reply..."></textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            @endif

            @if ($message->replies->count() > 0)
                <div class="replies mt-4">
                    <h6>Previous Replies</h6>
                    @foreach ($message->replies as $reply)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <small><strong>{{ $reply->sender->name }}</strong></small>
                                    <small class="text-body-secondary">
                                        {{ $reply->created_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                                <p class="mb-0">{!! nl2br(e($reply->message)) !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection