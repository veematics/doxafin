<x-app-layout>
<div class="container-lg">
<div class="row mb-4">
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

            <!-- Send Message Modal -->
            <div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Add this before the modal -->
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const form = document.getElementById('sendMessageForm');
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                fetch('{{ route("inbox.store") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(Object.fromEntries(new FormData(form)))
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        window.toast.show(data.message, 'success');
                                        
                                        // Close modal and reset form
                                        form.reset();
                                        const modal = coreui.Modal.getInstance(document.getElementById('sendMessageModal'));
                                        modal.hide();
                                        
                                        // Optionally refresh the messages list
                                        window.location.reload();
                                    } else {
                                        throw new Error(data.message);
                                    }
                                })
                                .catch(error => {
                                    window.toast.show(error.message || 'Failed to send message', 'error');
                                });
                            });
                        });
                        </script>
                        
                        <!-- Update the form ID in the modal -->
                        <form id="sendMessageForm" action="{{ route('inbox.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="sendMessageModalLabel">Send New Message</h5>
                                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="message_category" value="personal">
                                <div class="mb-3">
                                    <label for="recipient" class="form-label">To</label>
                                    <select class="form-select" name="sent_to" required>
                                        <option value="">Select Recipient</option>
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
        </div>
</div>
</div>


    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
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
                        <!-- In the search form, add per-page selector -->
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

                       
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse ($messages as $message)
                                    <a href="#" class="message-item list-group-item list-group-item-action border-start-0 border-end-0 {{ !$message->is_read ? 'bg-light' : '' }}"
                                       data-message-id="{{ $message->id }}"
                                       data-from="{{ $message->isSystemMessage() ? 'System' : $message->sender->name }}"
                                       data-to="{{ $message->recipient->name }}"
                                       data-subject="{{ $message->subject }}"
                                       data-priority="{{ $message->priority_status == 1 ? 'Normal' : ($message->priority_status == 2 ? 'High' : 'Super Priority') }}"
                                       data-date="{{ $message->created_at->format('F j, Y g:i A') }}"
                                       data-content="{{ $message->message }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if(!$message->is_read)
                                                    <span class="badge bg-primary rounded-pill me-2"></span>
                                                @endif
                                                <div>
                                                    @if(request()->routeIs('inbox.sent'))
                                                        <small class="text-body-secondary d-block mb-1">To: {{ $message->recipient->name }}</small>
                                                    @endif
                                                    <div class="d-flex align-items-center mb-1">
                                                        <svg class="icon me-2 text-body-secondary">
                                                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ !$message->is_read ? 'cil-envelope-closed' : 'cil-envelope-open' }}"></use>
                                                        </svg>
                                                        <h6 class="mb-0 {{ !$message->is_read ? 'fw-bold' : '' }}">{{ $message->subject }}</h6>
                                                        @if($message->priority_status == 3)
                                                            <span class="badge bg-danger ms-2">High Priority</span>
                                                        @elseif($message->priority_status == 2)
                                                            <span class="badge bg-warning ms-2">Need Attention</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-body-secondary me-2">
                                                            @if(!request()->routeIs('inbox.sent'))
                                                                {{ $message->isSystemMessage() ? 'System' : $message->sender->name }}
                                                            @endif
                                                        </small>
                                                        <small class="text-truncate text-body-secondary" style="max-width: 500px;">
                                                        {{ Str::limit($message->message, 160, '...') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="text-body-secondary text-nowrap ms-3">
                                                {{ $message->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-5">
                                        <svg class="icon icon-xl text-body-secondary mb-3">
                                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-inbox"></use>
                                        </svg>
                                        <p class="text-body-secondary">No messages found</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @if($messages->hasPages())
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">Show</span>
                                        <select class="form-select form-select-sm" style="width: 80px;" onchange="document.getElementById('searchForm').submit()" name="per_page" form="searchForm">
                                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                        <span class="ms-2">entries</span>
                                    </div>
                                    <nav class="d-flex justify-content-end" aria-label="Page Navigation">
                                        {{ $messages->appends(request()->except('page'))->onEachSide(1)->links('vendor.pagination.coreui') }}
                                    </nav>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Move modal and script inside the layout -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMessageModalLabel">Message Details</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">From</label>
                        <p id="messageFrom" class="mb-1"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">To</label>
                        <p id="messageTo" class="mb-1"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Subject</label>
                        <p id="messageSubject" class="mb-1"></p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Priority</label>
                            <p id="messagePriority" class="mb-1"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Date</label>
                            <p id="messageDate" class="mb-1"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Message</label>
                        <p id="messageContent" class="mb-1"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="replyToMessage()">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-reply"></use>
                        </svg>Reply
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Move script inside the layout -->
    <!-- Update the view message modal script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.viewModal = new coreui.Modal(document.getElementById('viewMessageModal'));
            
            document.querySelectorAll('.message-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Mark message as read
                    const messageId = this.dataset.messageId;
                    fetch(`/inbox/${messageId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI to show message as read
                            this.classList.remove('bg-light');
                            this.querySelector('.badge.bg-primary')?.remove();
                            this.querySelector('use').setAttribute('xlink:href', "{{ asset('assets/icons/free/free.svg') }}#cil-envelope-open");
                        }
                    });

                    const message = {
                        from: this.dataset.from,
                        to: this.dataset.to,
                        subject: this.dataset.subject,
                        priority: this.dataset.priority,
                        date: this.dataset.date,
                        content: this.dataset.content
                    };
                    
                    document.getElementById('messageFrom').textContent = message.from;
                    document.getElementById('messageTo').textContent = message.to;
                    document.getElementById('messageSubject').textContent = message.subject;
                    document.getElementById('messagePriority').textContent = message.priority;
                    document.getElementById('messageDate').textContent = message.date;
                    document.getElementById('messageContent').textContent = message.content;
                    
                    window.viewModal.show();
                });
            });
        });
    
        function closeViewModal() {
            const modal = coreui.Modal.getInstance(document.getElementById('viewMessageModal'));
            if (modal) {
                modal.hide();
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        }
    </script>

    <!-- After all the content, modals, and scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('sendMessageForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                fetch('{{ route("inbox.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(new FormData(form)))
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.toast.show(data.message, 'success');
                        
                        // Close modal and reset form
                        form.reset();
                        const modal = coreui.Modal.getInstance(document.getElementById('sendMessageModal'));
                        modal.hide();
                        
                        // Optionally refresh the messages list
                        window.location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    window.toast.show(error.message || 'Failed to send message', 'error');
                });
            });
        });

        
    function replyToMessage() {
                const from = document.getElementById('messageFrom').textContent;
                const subject = document.getElementById('messageSubject').textContent;
                
                // Close view modal
                closeViewModal();
                
                // Open send message modal
                const sendModal = new coreui.Modal(document.getElementById('sendMessageModal'));
                const form = document.getElementById('sendMessageForm');
                
                // Set recipient
                const recipientSelect = form.querySelector('select[name="sent_to"]');
                Array.from(recipientSelect.options).forEach(option => {
                    if (option.text === from) {
                        option.selected = true;
                    }
                });
                
                // Set subject with Re: prefix if not already present
                const replySubject = subject.startsWith('Re:') ? subject : `Re: ${subject}`;
                form.querySelector('input[name="subject"]').value = replySubject;
                
                sendModal.show();
            }
    </script>

</x-app-layout>