<x-app-layout>

    <!-- Remove select2.css reference as it's imported directly in JS -->
    <style>
        .select2-container .select2-selection--single {
            border: 1px solid #DBDFE6;
            border-radius: 0.25rem;
            height:37px !important;
         
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
           padding-top:3px;
            
        }</style>



@push('scripts')
    @vite(['resources/js/inboxselect2.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if page was reloaded
            if (performance.navigation.type === 1) {
                window.toast.show('Messages reloaded successfully', 'success');
            }
            
            // Check for reply parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('reply') === '1') {
                window.toast.show('Reply sent successfully', 'success');
            }
            
            // Initialize Select2 for search dropdowns
            setTimeout(function() {
                try {
                    $('.select2-filter').select2({
                        placeholder: 'Filter by {{ request()->routeIs("inbox.sent") ? "recipient" : "sender" }}...',
                        allowClear: true,
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-light',
                        minimumResultsForSearch: 0
                    });
                } catch(e) {
                    console.error('Error initializing sender/recipient Select2:', e);
                }
            }, 500);
        });
    </script>
@endpush


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
                        <span class="badge bg-primary rounded-pill float-end">{{ $unreadCount ?? 0 }}</span>
                    </a>
                    <a class="list-group-item list-group-item-action {{ request()->routeIs('inbox.sent') ? 'active' : '' }}" href="{{ route('inbox.sent') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-paper-plane"></use>
                        </svg> Sent Messages
                        <span class="badge bg-secondary rounded-pill float-end">{{ $unreadSentCount ?? 0 }}</span>
                    </a>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <a href="{{ route('inbox.trash') }}" class="text-decoration-none text-body d-flex align-items-center flex-grow-1">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-trash"></use>
                            </svg> Trash
                            <span class="badge bg-danger rounded-pill ms-2">{{ $trashCount ?? 0 }}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-link text-danger p-0">[empty]</a>
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
                                @if(isset($view_type) && $view_type == 'trash')
                                    Trash
                                @elseif(request()->routeIs('inbox.sent'))
                                    Sent Messages
                                @else
                                    Inbox Messages
                                @endif
                            </h5>
                            <div class="btn-group">
                                <button class="btn btn-light btn-sm" onclick="location.reload(true)">
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
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search in title or content..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="priority">
                                    <option value="">All Priority</option>
                                    <option value="3" {{ request('priority') == '3' ? 'selected' : '' }}>High Priority</option>
                                    <option value="2" {{ request('priority') == '2' ? 'selected' : '' }}>Need Attention</option>
                                    <option value="1" {{ request('priority') == '1' ? 'selected' : '' }}>Normal</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select select2-filter" name="sender">
                                    <option value="">All {{ request()->routeIs('inbox.sent') ? 'Recipients' : 'Senders' }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('sender') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                            <table class="table table-hover table-striped mb-0 border-0">
                                <tbody>
                                    @forelse($messages as $message)
                                        <!-- In the message list section, update the row with read/unread icons -->
                                        <tr id="message-item-{{ $message->id }}" 
                                            class="{{ !$message->is_read ? 'fw-bold' : '' }}" 
                                            style="cursor: pointer;" 
                                            @if(!(isset($view_type) && $view_type == 'trash'))
                                                data-coreui-toggle="modal" 
                                                data-coreui-target="#viewMessageModal"
                                                data-message-id="{{ $message->id }}"
                                                data-message-subject="{{ $message->subject }}"
                                                data-message-content="{{ $message->message }}"
                                                data-message-sender="{{ $message->sender->name }}"
                                                data-message-date="{{ $message->created_at->format('M d, Y h:i A') }}"
                                                data-message-priority="{{ $message->priority_status }}"
                                                data-message-category="{{ $message->message_category }}"
                                                onclick="markMessageAsRead({{ $message->id }})"
                                            @endif
                                            >
                                            <td class="ps-3" style="width: 40px;">
                                                <!-- Add the envelope icon here -->
                                                <svg class="icon message-status-icon" id="message-icon-{{ $message->id }}">
                                                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ !$message->is_read ? 'cil-envelope-closed' : 'cil-envelope-open' }}"></use>
                                                </svg>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div>
                                                        <span>{{ $message->subject }}</span>
                                                        @if($message->priority_status == 3)
                                                            <span class="badge bg-danger ms-2">High Priority</span>
                                                        @elseif($message->priority_status == 2)
                                                            <span class="badge bg-warning text-dark ms-2">Need Attention</span>
                                                        @endif
                                                        
                                                        @if(request()->routeIs('inbox.index'))
                                                            <small class="text-muted ms-2">From: {{ $message->sender->name }}</small>
                                                        @elseif(request()->routeIs('inbox.sent'))
                                                            <small class="text-muted ms-2">To: {{ $message->recipient->name }}</small>
                                                        @else
                                                            <small class="text-muted ms-2">
                                                                From: {{ $message->sender->name }} | 
                                                                To: {{ $message->recipient->name }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">{{ Str::limit($message->message, 50) }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if(isset($view_type) && $view_type == 'trash')
                                                    <button class="btn btn-sm btn-outline-success recover-button me-2" 
                                                            data-message-id="{{ $message->id }}"
                                                            onclick="recoverMessage({{ $message->id }}); event.stopPropagation();">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-action-undo"></use>
                                                        </svg> Recover
                                                    </button>
                                                @endif
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $diffInHours = $message->created_at->diffInHours($now);
                                                    
                                                    if ($diffInHours < 24) {
                                                        $dateDisplay = $message->created_at->diffForHumans();
                                                    } else {
                                                        $dateDisplay = $message->created_at->format('M d, Y h:i A');
                                                    }
                                                @endphp
                                                <small class="text-muted">{{ $dateDisplay }}</small>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div style="width: 120px">
                                    <select class="form-select form-select-sm" onchange="window.location.href=this.value">
                                        @foreach([20, 50, 100] as $size)
                                            <option value="{{ request()->fullUrlWithQuery(['per_page' => $size]) }}" 
                                                {{ request('per_page', 20) == $size ? 'selected' : '' }}>
                                                {{ $size }} / page
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <nav aria-label="Message navigation">
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

<!-- Send Message Modal -->
<!-- Change the form ID to match the JavaScript -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Fix the form attributes -->
                <form id="sendMessageForm" action="{{ route('inbox.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="sendMessageModalLabel">Send New Message</h5>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add this hidden field -->
                        <input type="hidden" name="message_category" value="personal">
                        
                        <div class="mb-4">
                            <label for="recipient" class="form-label">To</label>
                            <select class="form-select select2-recipient" name="sent_to" id="recipient" required>
                               
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
                            <small class="form-text text-muted">
                                Support: new line, and auto link for url
                            </small>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewMessageModalLabel">Message Details</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h4 id="modalMessageSubject" class="fw-bold"></h4>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted" id="modalMessageSender"></small>
                        <small class="text-muted" id="modalMessageDate"></small>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <p id="modalMessageContent" class="mb-4"></p>
                </div>
                <div class="d-flex">
                    <span class="badge me-2" id="modalMessagePriority"></span>
                    <span class="badge" id="modalMessageCategory"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="trashButton" data-message-id="{{ $message->id ?? '' }}">
                    <svg class="icon me-1">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-trash"></use>
                    </svg> Move to Trash
                </button>
                <button type="button" class="btn btn-success" id="threadButton">
                    <svg class="icon me-1">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-list-rich"></use>
                    </svg> Display as Thread
                </button>
                <button type="button" class="btn btn-primary" id="replyButton">
                    <svg class="icon me-1">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-reply"></use>
                    </svg> Reply
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if page was reloaded
            if (performance.navigation.type === 1) {
                window.toast.show('Messages reloaded successfully', 'success');
            }
            
            
        });
        document.addEventListener('DOMContentLoaded', function() {
    // Check if page was reloaded
    if (performance.navigation.type === 1) {
        window.toast.show('Messages reloaded successfully', 'success');
    }
    
    // Check for trash parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('trash')) {
        window.toast.show('Message moved to trash successfully', 'success');
    }
    if (urlParams.has('recovered')) {
        window.toast.show('Message recovered successfully', 'success');
    }
});

        // Add recover message function
        function recoverMessage(messageId) {
            if (confirm('Are you sure you want to recover this message?')) {
                fetch(`/inbox/${messageId}/recover`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the message row from the UI
                        const messageRow = document.getElementById(`message-item-${messageId}`);
                        if (messageRow) {
                            messageRow.remove();
                        }
                        
                        // Update trash count in sidebar
                        const trashBadge = document.querySelector('.list-group-item .badge.bg-danger');
                        if (trashBadge) {
                            const currentCount = parseInt(trashBadge.textContent);
                            if (currentCount > 0) {
                                trashBadge.textContent = currentCount - 1;
                            }
                        }
                        
                        // Show success message
                        window.toast.show('Message recovered successfully', 'success');
                        
                        // If no more messages in trash, reload to show empty state
                        const messageRows = document.querySelectorAll('table tbody tr:not(.no-messages)');
                        if (messageRows.length <= 1) {
                            window.location.href = "{{ route('inbox.trash') }}?recovered=1";
                        }
                    } else {
                        window.toast.show('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    window.toast.show('Error recovering message', 'error');
                    console.error('Error:', error);
                });
            }
        }
        
        // Store current message ID globally
        let currentMessageId = null;

        document.querySelectorAll('[data-coreui-toggle="modal"]').forEach(element => {
            element.addEventListener('click', function() {
                currentMessageId = this.getAttribute('data-message-id');
                const subject = this.getAttribute('data-message-subject');
                const content = this.getAttribute('data-message-content');
                const sender = this.getAttribute('data-message-sender');
                const date = this.getAttribute('data-message-date');
                
                // Set modal content
                document.getElementById('modalMessageSubject').textContent = subject;
                document.getElementById('modalMessageContent').innerHTML = 
                    content
                        .replace(/\n/g, '<br>')
                        .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
                document.getElementById('modalMessageSender').textContent = 'From: ' + sender;
                document.getElementById('modalMessageDate').textContent = date;
                
                // Set priority badge
                const priorityBadge = document.getElementById('modalMessagePriority');
                const priority = this.getAttribute('data-message-priority');
                if(priority == '3') {
                    priorityBadge.className = 'badge bg-danger me-2';
                    priorityBadge.textContent = 'High Priority';
                } else if(priority == '2') {
                    priorityBadge.className = 'badge bg-warning text-dark me-2';
                    priorityBadge.textContent = 'Need Attention';
                } else {
                    priorityBadge.className = 'badge bg-secondary me-2';
                    priorityBadge.textContent = 'Normal';
                }
                
                // Set category badge
                const categoryBadge = document.getElementById('modalMessageCategory');
                const category = this.getAttribute('data-message-category');
                categoryBadge.className = 'badge bg-info';
                categoryBadge.textContent = category.charAt(0).toUpperCase() + category.slice(1);
            });
        });

        // Reply button handler
        // Replace the existing reply button handler with this:
        document.getElementById('replyButton').addEventListener('click', function() {
        if (currentMessageId) {
        // Use the named route format
        window.location.href = `/inbox/${currentMessageId}/reply`;
        }
        });

        // Trash button handler
        document.getElementById('trashButton').addEventListener('click', function() {
            if (currentMessageId) {
                if (confirm('Are you sure you want to move this message to trash?')) {
                    fetch(`/inbox/${currentMessageId}/trash`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Find and close the modal using CoreUI's dismiss method
                            const viewMessageModal = document.getElementById('viewMessageModal');
                            const coreUIModal = coreui.Modal.getInstance(viewMessageModal);
                            if (coreUIModal) {
                                coreUIModal.hide();
                            }
                            
                            // Short delay before redirecting to ensure modal closes
                            setTimeout(function() {
                                window.location.href = "{{ route('inbox.index') }}?trash=1";
                            }, 300);
                        } else {
                            window.toast.show('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        window.toast.show('Error moving to trash', 'error');
                        console.error('Error:', error);
                    });
                }
            }
        });

        // Thread button handler
        document.getElementById('threadButton').addEventListener('click', function() {
            if (currentMessageId) {
                window.location.href = `/inbox/${currentMessageId}/thread`;
            }
        });

        // Helper function to create message elements
        function createMessageElement(message, isRoot) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-item ${isRoot ? 'root-message' : 'reply-message'} mb-4`;
            
            // Create priority badge HTML
            let priorityBadgeHTML = '';
            if (message.priority_status == '3') {
                priorityBadgeHTML = '<span class="badge bg-danger me-2">High Priority</span>';
            } else if (message.priority_status == '2') {
                priorityBadgeHTML = '<span class="badge bg-warning text-dark me-2">Need Attention</span>';
            } else {
                priorityBadgeHTML = '<span class="badge bg-secondary me-2">Normal</span>';
            }
            
            // Create category badge HTML
            const categoryName = message.message_category.charAt(0).toUpperCase() + message.message_category.slice(1);
            const categoryBadgeHTML = `<span class="badge bg-info">${categoryName}</span>`;
            
            // Format the message content with line breaks and auto-links
            const formattedContent = message.message
                .replace(/\n/g, '<br>')
                .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
            
            // Create message HTML
            messageDiv.innerHTML = `
                <div class="card ${isRoot ? 'border-primary' : ''}">
                    <div class="card-header ${isRoot ? 'bg-light' : 'bg-light-subtle'}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <strong>From:</strong> ${message.sender_name}
                                ${!isRoot ? `<small class="text-muted ms-2">(Reply)</small>` : ''}
                            </span>
                            <small class="text-muted">${message.created_at}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p>${formattedContent}</p>
                        </div>
                        <div class="d-flex">
                            ${priorityBadgeHTML}
                            ${categoryBadgeHTML}
                        </div>
                    </div>
                </div>
            `;
            
            return messageDiv;
        }
    </script>
@endpush

        

@push('scripts')
    <script>
        // Add the mark as read function
        function markMessageAsRead(messageId) {
            // Only make the request if the message belongs to the current user (not sent messages)
            if(!{{ request()->routeIs('inbox.sent') ? 'true' : 'false' }}) {
                fetch(`/inbox/${messageId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the icon to show the message as read
                        const iconElement = document.getElementById(`message-icon-${messageId}`);
                        if (iconElement) {
                            const useElement = iconElement.querySelector('use');
                            if (useElement) {
                                useElement.setAttribute('xlink:href', 
                                    '{{ asset('assets/icons/free/free.svg') }}#cil-envelope-open');
                            }
                        }
                        
                        // Remove the bold formatting
                        const messageRow = document.getElementById(`message-item-${messageId}`);
                        if (messageRow) {
                            messageRow.classList.remove('fw-bold');
                        }
                        
                        // Update unread count in the sidebar if needed
                        const unreadBadge = document.querySelector('.list-group-item .badge.bg-primary');
                        if (unreadBadge) {
                            const currentCount = parseInt(unreadBadge.textContent);
                            if (currentCount > 0) {
                                unreadBadge.textContent = currentCount - 1;
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking message as read:', error);
                });
            }
        }
        
        // Rest of your existing scripts...
    </script>
@endpush

</x-app-layout>
