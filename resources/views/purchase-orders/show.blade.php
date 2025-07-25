<x-app-layout>
@php
        $featureId = 5;
         $userId = auth()->id();
        $cacheKey = 'user_permissions_' . $userId;
        $permissions = Cache::get($cacheKey);
        $can_view = $permissions[$featureId][0]->can_view;
        $can_create = $permissions[$featureId][0]->can_create;
        $can_approve = $permissions[$featureId][0]->can_approve;
        $can_edit = $permissions[$featureId][0]->can_edit;
        $can_delete = $permissions[$featureId][0]->can_delete;
    @endphp
  
<x-slot name="header">
        <h1 class="text-2xl font-bold mb-6">Purchase Order Monitoring</h1>
    </x-slot>
    
    <div class="container mx-auto px-4 py-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">PO Overview</div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Client:</dt><dd class="col-sm-9">{{ $purchaseOrder->client->company_name }}</dd>
                                    <dt class="col-sm-3">PO Number:</dt><dd class="col-sm-9">{{ $purchaseOrder->poNo }}</dd>
                                    <dt class="col-sm-3">PO Value:</dt><dd class="col-sm-9">{{ $purchaseOrder->poCurrency }} {{ number_format($purchaseOrder->poValue, 0) }}</dd>
                                    <dt class="col-sm-3">Start Date:</dt><dd class="col-sm-9">{{ $purchaseOrder->poStartDate ? \Carbon\Carbon::parse($purchaseOrder->poStartDate)->format('d-m-Y') : 'N/A' }}</dd>
                                    <dt class="col-sm-3">End Date:</dt><dd class="col-sm-9">{{ $purchaseOrder->poEndDate ? \Carbon\Carbon::parse($purchaseOrder->poEndDate)->format('d-m-Y') : 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Payment Terms</div>
                            <div class="card-body payment-terms-display" style="max-height: 200px; overflow-y: auto;">
                                {!! $purchaseOrder->poTerm !!}
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Services</div>
                            <div class="card-body">
                                @if($purchaseOrder->serviceItems && $purchaseOrder->serviceItems->count() > 0)
                                    <ul>
                                        @foreach($purchaseOrder->serviceItems as $service)
                                            <li>
                                                <strong>{{ $service->serviceName }}</strong><br>
                                                Value: {{ $purchaseOrder->poCurrency }} {{ number_format($service->serviceValue, 0) }}<br>
                                                Is Recurring: {{ $service->is_recurring ? 'Yes' : 'No' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No services found for this purchase order</p>
                                @endif
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Files</div>
                            <div class="card-body">
                                @if($purchaseOrder->poFiles && count($purchaseOrder->poFiles) > 0)
                                    <ul>
                                        @foreach($purchaseOrder->poFiles as $file)
                                   
                                            <li>
                                                <a href="{{ route('media.view', base64_encode($file['file'])) }}" target="_blank" rel="noopener noreferrer">
                                                    <strong>{{ basename($file['original_name']) }}</strong>
                                                </a><br>
                                                <small><em>Notes:</em> {{ $file['notes'] ?? 'No notes' }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No files attached to this purchase order</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
            <div class="col-md-6">
                @if($requestChanges && $requestChanges->count() > 0)
                 <div class="card mb-3">
                    <div class="card-header">Request Changes</div>
                    <div class="card-body">
                       <!-- //list of request changes column: date request, title, changes -->
                       <table class="table">
                        <thead>
                            <tr>
                                <th width="20%">Date Request</th>
                                <th width="50%">Request Title</th>
                               
                                <th width="10%">Status</th>
                                <th width="20%">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requestChanges as $requestChange)
                                <tr>
                                    <td>{{ $requestChange->created_at }}</td>
                                    <td>{{ $requestChange->title }}</td>
                                   
                                    <td>
                                         @php
                                             $statusClass = $rcStatusColors[$requestChange->status] ?? 'secondary';
                                         @endphp
                                         <span class="badge rounded-pill bg-{{ $statusClass }}">
                                         {{ ucfirst($requestChange->status) }}
                                                        </span>
                                        
                                     </td>
                                    <td>
                                        <div class="d-grid gap-2 mb-2">
                                            <button class="btn btn-sm btn-info detail-btn" data-changes="{{ json_encode($requestChange->changes) }}" data-category="{{ $requestChange->category }}" data-id="{{ $requestChange->id }}">Action</button>
                                         </div>
                                   
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                @endif
                 <div class="card mb-3">
                    <div class="card-header">Notes</div>
                    <div class="card-body">
                      
                       
                       @if($purchaseOrder->notes && $purchaseOrder->notes->count() > 0)
                           <div class="table-responsive">
                               <table class="table table-sm table-striped">
                                   <thead>
                                       <tr>
                                           <th style="width: 20%;">Date</th>
                                           <th style="width: 70%;">Title & Summary</th>
                                           <th style="width: 10%;">Created By</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       @foreach($purchaseOrder->notes->where('notesHide', 0) as $note)
                                           <tr>
                                               <td>
                                                   {{ $note->created_at ? $note->created_at->format('d-m-Y H:i') : 'N/A' }}
                                                   <br><a href="#" class="text-primary small edit-note-btn" data-note-id="{{ $note->notesID }}" data-note-title="{{ $note->notesTitle }}" data-note-summary="{{ $note->notesSummary }}" data-note-detail="{{ $note->notesDetail }}">[edit]</a>
                                                   <a href="#" class="text-danger small delete-note-btn" data-note-id="{{ $note->notesID }}">[delete]</a>
                                               </td>
                                               <td>
                                                   <strong>{{ $note->notesTitle ?? 'N/A' }}</strong>
                                                   @if($note->notesSummary)
                                                       <br>{{ $note->notesSummary }}
                                                   @endif
                                                   @if($note->notesDetail)
                                                       <br><a href="#" class="text-info small" data-coreui-toggle="modal" data-coreui-target="#noteDetailModal" data-note-id="{{ $note->notesID }}" data-note-title="{{ $note->notesTitle }}" data-note-detail="{{ $note->notesDetail }}">View Detail</a>
                                                   @endif
                                               </td>
                                               <td>{{ $note->notesBy ?? 'N/A' }}</td>
                                           </tr>
                                       @endforeach
                                   </tbody>
                               </table>
                           </div>
                       @else
                           <p class="text-muted">No notes available for this Purchase Order.</p>
                       @endif
                        <button class="btn btn-sm btn-info mb-3" data-coreui-toggle="modal" data-coreui-target="#addNoteModal">Add Notes</button>
                    </div>
                </div>
                
           
                
                <div class="card mb-3">
                    <div class="card-header">Operational & Status</div>
                    <div class="card-body">
                        <p class="text-muted">Status: <strong>{{ $purchaseOrder->poStatus ?? 'Not Set' }}</strong></p>
                        Operation:<br/>
                        <ul>
                        @if (($purchaseOrder->poStatus == "Draft" && $can_edit=='1')||$can_approve=='1')
                          
                            <li><a href="{{ route('purchase-orders.edit', $purchaseOrder) }}">Edit</a></li>
                            @endif

                            @if ($purchaseOrder->poStatus == "Draft" && $can_edit=='1')
                            <li><a href="#" onclick="confirmApproval(event, {{ $purchaseOrder->id }})">Submit for Approval</a></li>
                            @endif
                        </ul>
                    </div>
                </div>

               

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">Invoice Status</div>
                            <div class="card-body">
                                <p class="text-muted">Invoice Created:0
                                    <br/>
                                        Invoice Completed:0<br/>
                                        Active Invoice Due:0<br/>
                                        Total Invoice Value:Rp. 0<br/>
                                        Total Remaining Balance: Rp. 0<br/>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">Payment Status</div>
                            <div class="card-body">
                                <p class="text-muted">
                                       No of Payment: 0<br/>
                                       Total Payment Value:Rp. 0<br/>
                                       Remaining Payment Value:Rp. 0<br/>
                                        
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
     <!-- Activity Logs Section -->
                @if(str_contains(session('role_name'), 'SA'))
                <div class="card mb-3">
                    <div class="card-header">
                        <button class="btn btn-link text-decoration-none p-0 w-100 text-start" type="button" data-coreui-toggle="collapse" data-coreui-target="#activityLogsCollapse" aria-expanded="false" aria-controls="activityLogsCollapse">
                            <span><i class="fas fa-clipboard-list me-2"></i>Activity Logs</span>
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </button>
                    </div>
                    <div class="collapse" id="activityLogsCollapse">
                        <div class="card-body">
                        @if($purchaseOrder->logs && $purchaseOrder->logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%;">Date & Time</th>
                                            <th style="width: 15%;">Action</th>
                                            <th style="width: 15%;">User</th>
                                            <th style="width: 50%;">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchaseOrder->logs->take(5)->sortByDesc('created_at') as $log)
                                            <tr>
                                                <td>
                                                    <small class="text-muted">{{ $log->formatted_created_at }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $log->action_name }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $log->logBy }}</strong>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $log->logNotes }}">
                                                        {{ $log->logNotes }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($purchaseOrder->logs->count() > 5)
                                <div class="text-center mt-2">
                                    <small class="text-muted">Showing latest 5 entries. 
                                        <a href="#" onclick="loadLogHistory(1, {{ $purchaseOrder->poID }})" class="text-primary">View all {{ $purchaseOrder->logs->count() }} logs</a>
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No activity logs found for this Purchase Order.</p>
                                <small class="text-muted">Click "Add Log" to create the first entry.</small>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
                @endif
               
            </div>
        </div>
    </div>
    @component('components.approval-modal')
    @endcomponent

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Request Change Details</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Before</th>
                                <th>After</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="detailModalBody">
                            <!-- Details will be loaded here by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


            </div>
        </div>
    </div>

    <!-- Note Detail Modal -->
    <div class="modal fade" id="noteDetailModal" tabindex="-1" aria-labelledby="noteDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteDetailModalLabel">Note Details</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="noteDetailContent">
                        <!-- Note details will be loaded here by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addNoteForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="notesCategory" value="PurchaseOrder">
                        <input type="hidden" name="notesObjID" value="{{ $purchaseOrder->poID }}">
                        
                        <div class="mb-3">
                            <label for="notesTitle" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="notesTitle" name="notesTitle" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notesSummary" class="form-label">Summary</label>
                            <textarea class="form-control" id="notesSummary" name="notesSummary" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <x-ckeditor 
                                id="notesDetail"
                                name="notesDetail"
                                height="200px"
                                label="Detail"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNoteModalLabel">Edit Note</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editNoteForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="editNoteId" name="noteId">
                        
                        <div class="mb-3">
                            <label for="editNotesTitle" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editNotesTitle" name="notesTitle" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editNotesSummary" class="form-label">Summary</label>
                            <textarea class="form-control" id="editNotesSummary" name="notesSummary" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <x-ckeditor 
                                id="editNotesDetail"
                                name="notesDetail"
                                height="200px"
                                label="Detail"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.detail-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const changesData = JSON.parse(this.dataset.changes);
                    const category = this.dataset.category;
                    const requestId = this.dataset.id; // Assuming you have a data-id attribute on the button
                    const modalBody = document.getElementById('detailModalBody');
                    modalBody.innerHTML = ''; // Clear previous content

                    if (Array.isArray(changesData)) {
                        changesData.forEach(change => {
                            let fieldDisplay = change.field;
                            if (category === 'Purchase Order') {
                                fieldDisplay = fieldDisplay.replace('po', '');
                            }
                            const row = `
                                <tr>
                                    <td>${fieldDisplay}</td>
                                    <td>${change.before ?? 'N/A'}</td>
                                    <td>${change.after ?? 'N/A'}</td>
                                    <td><button class="btn btn-success btn-sm approve-btn" data-id="${change.id}" data-change="${JSON.stringify(change).replace(/"/g, '&quot;')}">Approve</button></td>
                                </tr>
                            `;
                            modalBody.innerHTML += row;
                        });
                    } else {
                        // Handle case where changesData is not an array (e.g., a simple string)
                        const row = `
                            <tr>
                                <td colspan="3">${changesData}</td>
                            </tr>
                        `;
                        modalBody.innerHTML += row;
                    }

                    var detailModal = new coreui.Modal(document.getElementById('detailModal'));
                    detailModal.show();
                });
            });

            // Handle Approve button click
            document.getElementById('detailModalBody').addEventListener('click', function(event) {
                if (event.target.classList.contains('approve-btn')) {
                    const requestId = event.target.dataset.id;
                    const changeData = JSON.parse(event.target.dataset.change);
                    console.log('Approve button clicked for request ID:', requestId, 'with change data:', changeData);

                    fetch('/request-changes/' + requestId + '/approve', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ change: changeData })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Change approved successfully!');
                            // Optionally, refresh the modal or the page
                            location.reload();
                        } else {
                            alert('Error approving change: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while approving the change.');
                    });
                }
            });

            // Handle Note Detail Modal
            document.querySelectorAll('[data-coreui-target="#noteDetailModal"]').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const noteTitle = this.dataset.noteTitle;
                    const noteDetail = this.dataset.noteDetail;
                    
                    document.getElementById('noteDetailModalLabel').textContent = noteTitle || 'Note Details';
                    document.getElementById('noteDetailContent').innerHTML = noteDetail || 'No details available.';
                });
            });

            // Handle Delete Note
            document.querySelectorAll('.delete-note-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const noteId = this.dataset.noteId;
                    
                    if (confirm('Are you sure you want to delete this note?')) {
                        fetch('/notes/' + noteId, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                notesHide: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload page to refresh the table
                                location.reload();
                            } else {
                                alert('Error deleting note: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the note.');
                        });
                    }
                });
            });

            // Handle Edit Note
            document.querySelectorAll('.edit-note-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const noteId = this.dataset.noteId;
                    const noteTitle = this.dataset.noteTitle;
                    const noteSummary = this.dataset.noteSummary;
                    const noteDetail = this.dataset.noteDetail;
                    
                    // Populate the edit form
                    document.getElementById('editNoteId').value = noteId;
                    document.getElementById('editNotesTitle').value = noteTitle || '';
                    document.getElementById('editNotesSummary').value = noteSummary || '';
                    
                    // Set CKEditor content
                    const editEditorElement = document.getElementById('editNotesDetail');
                    const editEditor = window.ckeditors ? window.ckeditors.get(editEditorElement) : null;
                    if (editEditor) {
                        editEditor.setData(noteDetail || '');
                    } else {
                        editEditorElement.value = noteDetail || '';
                    }
                    
                    // Show the edit modal
                    const editModal = new coreui.Modal(document.getElementById('editNoteModal'));
                    editModal.show();
                });
            });

            // Handle Edit Note Form Submission
            document.getElementById('editNoteForm').addEventListener('submit', function(event) {
                event.preventDefault();
                
                const noteId = document.getElementById('editNoteId').value;
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('notesTitle', document.getElementById('editNotesTitle').value);
                formData.append('notesSummary', document.getElementById('editNotesSummary').value);
                
                // Get CKEditor content
                const editEditorElement = document.getElementById('editNotesDetail');
                const editEditor = window.ckeditors ? window.ckeditors.get(editEditorElement) : null;
                if (editEditor) {
                    formData.append('notesDetail', editEditor.getData());
                } else {
                    formData.append('notesDetail', editEditorElement.value);
                }
                
                fetch('/notes/' + noteId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        notesTitle: document.getElementById('editNotesTitle').value,
                        notesSummary: document.getElementById('editNotesSummary').value,
                        notesDetail: editEditor ? editEditor.getData() : editEditorElement.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = coreui.Modal.getInstance(document.getElementById('editNoteModal'));
                        modal.hide();
                        
                        // Reload page to show updated note
                        location.reload();
                    } else {
                        alert('Error updating note: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the note.');
                });
            });

            // Handle Add Note Form Submission
            document.getElementById('addNoteForm').addEventListener('submit', function(event) {
                event.preventDefault();
                
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('notesCategory', 'PurchaseOrder');
                formData.append('notesObjID', '{{ $purchaseOrder->poID }}');
                formData.append('notesTitle', document.getElementById('notesTitle').value);
                formData.append('notesSummary', document.getElementById('notesSummary').value);
                formData.append('notesBy', '{{ auth()->user()->name }}');

                
                // Get CKEditor content
                const editorElement = document.getElementById('notesDetail');
                const editor = window.ckeditors ? window.ckeditors.get(editorElement) : null;
                if (editor) {
                    formData.append('notesDetail', editor.getData());
                } else {
                    formData.append('notesDetail', editorElement.value);
                }
                
                fetch('/notes', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = coreui.Modal.getInstance(document.getElementById('addNoteModal'));
                        modal.hide();
                        
                        // Reset form
                        document.getElementById('addNoteForm').reset();
                        if (editor) {
                            editor.setData('');
                        }
                        
                        // Reload page to show new note
                        location.reload();
                    } else {
                        alert('Error saving note: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the note.');
                });
            });



        });
    </script>
</x-app-layout>