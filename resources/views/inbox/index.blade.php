<x-app-layout>
@push('styles')
    @vite(['resources/css/select2.css'])
@endpush

@push('scripts')
    @vite(['resources/js/inboxselect2.js'])
@endpush

<!-- In the modal body -->
<div class="mb-4">
    <label for="recipient" class="form-label">To</label>
    <select class="form-select select2-recipient" name="sent_to" id="recipient" required>
        <option value="">Search for recipient...</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
</div>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
        <!-- Header section with title and send button -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h4 font-weight-bold mb-0">
                        Inbox Messages
                    </h2>
                    <!-- Update the button to trigger modal -->
                    <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#sendMessageModal">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-envelope-letter"></use>
                        </svg> Send Message
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content row with sidebar and messages -->
        <div class="row">
            <!-- Inbox Sidebar -->
            <div class="col-md-3">
                <div class="list-group mb-4">
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('inbox.index') ? 'active' : '' }}" href="{{ route('inbox.index') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-inbox"></use>
                        </svg> Inbox Message
                    </a>
                    <!-- Update the Sent link with proper route -->
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('inbox.sent') ? 'active' : '' }}" href="{{ route('inbox.sent') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-paper-plane"></use>
                        </svg> Sent 
                    </a>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-trash"></use>
                            </svg> Trash
                        </div>
                        <a href="#" class="btn btn-sm btn-link text-danger p-0">[empty trash]</a>
                    </div>
                </div>
            </div>

            <!-- Message List -->
            <div class="col-md-9">
                <!-- Rest of the message list content remains the same -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                {{ request()->routeIs('inbox.sent') ? 'Sent Messages' : 'Inbox Messages' }}
                            </h5>
                            <div class="btn-group">
                                <button class="btn btn-light btn-sm">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-reload"></use>
                                    </svg>
                                </button>
                                <button class="btn btn-light btn-sm">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-options"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add search and filter section here -->
                    <div class="card-body border-bottom">
                        <form id="searchForm" action="{{ request()->routeIs('inbox.sent') ? route('inbox.sent') : route('inbox.index') }}" method="GET" class="row g-3">
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="search" placeholder="Search in title or content..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-search"></use>
                                    </svg>Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Message list content -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 border-0">
                                <tbody>
                                    @forelse($messages as $message)
                                        <tr class="{{ $message->read_at ? '' : 'fw-bold' }}" style="cursor: pointer;" 
                                            data-coreui-toggle="modal" 
                                            data-coreui-target="#viewMessageModal" 
                                            data-message-id="{{ $message->id }}"
                                            data-message-subject="{{ $message->subject }}"
                                            data-message-content="{{ $message->message }}"
                                            data-message-sender="{{ $message->sender->name }}"
                                            data-message-date="{{ $message->created_at->format('M d, Y h:i A') }}">
                                            <td class="ps-3" style="width: 40px;">
                                                @if($message->priority_status == 3)
                                                    <span class="text-danger">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-flag-alt"></use>
                                                        </svg>
                                                    </span>
                                                @elseif($message->priority_status == 2)
                                                    <span class="text-warning">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-flag-alt"></use>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $message->subject }}</span>
                                                    <small class="text-muted">{{ Str::limit($message->message, 50) }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end pe-3">
                                                <small class="text-muted">{{ $message->created_at->format('M d') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <svg class="icon icon-xl text-muted mb-2">
                                                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-inbox"></use>
                                                    </svg>
                                                    <span class="text-muted">No messages found</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    @if($messages->hasPages())
                        <div class="card-footer">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="sendMessageForm" action="{{ route('inbox.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="sendMessageModalLabel">Send New Message</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- In the modal body, update the select element -->
                <div class="modal-body">
                    <input type="hidden" name="message_category" value="personal">
                    
                    <div class="mb-4">
                        <label for="recipient" class="form-label">To</label>
                        <select class="form-select select2-recipient" name="sent_to" id="recipient" required>
                            <option value="">Search for recipient...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" name="priority_status">
                            <option value="1">Normal</option>
                            <option value="2">High</option>
                            <option value="3">Super Priority</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
    <!-- Modal content remains the same -->
</div>

<!-- Scripts remain the same -->
</x-app-layout>