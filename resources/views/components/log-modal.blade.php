<!-- Log Activity Modal -->
<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Log Activity
                </h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="logForm">
                    @csrf
                    <input type="hidden" id="logID" name="logID">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="logCategory" name="logCategory" required>
                                    <option value="">Select Category</option>
                                    <option value="Purchase Order">Purchase Order</option>
                                    <option value="Invoice">Invoice</option>
                                    <option value="Payment">Payment</option>
                                    <option value="User Management">User Management</option>
                                    <option value="System">System</option>
                                    <option value="Notes">Notes</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logAction" class="form-label">Action <span class="text-danger">*</span></label>
                                <select class="form-select" id="logAction" name="logAction" required>
                                    <option value="">Select Action</option>
                                    <option value="Create">Create</option>
                                    <option value="Update">Update</option>
                                    <option value="Delete">Delete</option>
                                    <option value="View">View</option>
                                    <option value="Approve">Approve</option>
                                    <option value="Reject">Reject</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logObjID" class="form-label">Object ID <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="logObjID" name="logObjID" min="0" required>
                                <div class="form-text">ID of the object being logged (e.g., Purchase Order ID, User ID)</div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logBy" class="form-label">Logged By</label>
                                <input type="text" class="form-control" id="logBy" name="logBy" maxlength="50" readonly>
                                <div class="form-text">This will be automatically filled with current user</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="logNotes" class="form-label">Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="logNotes" name="logNotes" rows="4" required placeholder="Enter detailed notes about this activity..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveLogBtn">
                    <i class="fas fa-save me-1"></i>Save Log
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Log History Modal -->
<div class="modal fade" id="logHistoryModal" tabindex="-1" role="dialog" aria-labelledby="logHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logHistoryModalLabel">
                    <i class="fas fa-history me-2"></i>Activity History
                </h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Category</th>
                                <th>Action</th>
                                <th>User</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="logHistoryTableBody">
                            <!-- Dynamic content will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div id="logHistoryEmpty" class="text-center py-4" style="display: none;">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No activity logs found for this item.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize log modal
    $('#logModal').on('show.coreui.modal', function(event) {
        var button = $(event.relatedTarget);
        var category = button.data('category') || '';
        var action = button.data('action') || '';
        var objId = button.data('object-id') || '';
        
        // Reset form
        $('#logForm')[0].reset();
        $('#logForm .is-invalid').removeClass('is-invalid');
        
        // Pre-fill form if data provided
        if (category) $('#logCategory').val(category);
        if (action) $('#logAction').val(action);
        if (objId) $('#logObjID').val(objId);
        
        // Set current user
        @auth
        $('#logBy').val('{{ Auth::user()->name }}');
        @else
        $('#logBy').val('System');
        @endauth
    });
    
    // Save log
    $('#saveLogBtn').click(function() {
        var formData = {
            logCategory: $('#logCategory').val(),
            logAction: $('#logAction').val(),
            logObjID: $('#logObjID').val(),
            logNotes: $('#logNotes').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        // Clear previous validation errors
        $('#logForm .is-invalid').removeClass('is-invalid');
        $('#logForm .invalid-feedback').text('');
        
        $.ajax({
            url: '{{ route("logs.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#logModal').modal('hide');
                    
                    // Show success message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert(response.message);
                    }
                    
                    // Refresh page or update UI as needed
                    if (typeof refreshLogHistory === 'function') {
                        refreshLogHistory();
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = $('#' + field);
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(messages[0]);
                    });
                } else {
                    alert('An error occurred while saving the log.');
                }
            }
        });
    });
    
    // Load log history
    window.loadLogHistory = function(category, objectId) {
        $.ajax({
            url: '{{ route("logs.object-logs") }}',
            method: 'GET',
            data: {
                category: category,
                object_id: objectId
            },
            success: function(response) {
                if (response.success) {
                    var tbody = $('#logHistoryTableBody');
                    tbody.empty();
                    
                    if (response.data.length > 0) {
                        $('#logHistoryEmpty').hide();
                        
                        $.each(response.data, function(index, log) {
                            var row = '<tr>' +
                                '<td>' + formatDate(log.created_at) + '</td>' +
                                '<td><span class="badge bg-info">' + log.logCategory + '</span></td>' +
                                '<td><span class="badge bg-primary">' + log.logAction + '</span></td>' +
                                '<td>' + log.logBy + '</td>' +
                                '<td>' + log.logNotes + '</td>' +
                            '</tr>';
                            tbody.append(row);
                        });
                    } else {
                        $('#logHistoryEmpty').show();
                    }
                    
                    $('#logHistoryModal').modal('show');
                } else {
                    alert('Error loading log history: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while loading log history.');
            }
        });
    };
    
    // Helper functions
    function formatDate(dateString) {
        var date = new Date(dateString);
        return date.toLocaleString('id-ID', {
            timeZone: 'Asia/Jakarta',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    // Helper functions for category and action names are no longer needed
    // since we're now storing string values directly
});
</script>