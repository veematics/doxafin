@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Activity Logs
                        </h5>
                        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#logModal">
                            <i class="fas fa-plus me-1"></i>Add Log Entry
                        </button>
                    </div>
                </div>
                
                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('logs.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>Purchase Order</option>
                                <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>Invoice</option>
                                <option value="3" {{ request('category') == '3' ? 'selected' : '' }}>Payment</option>
                                <option value="4" {{ request('category') == '4' ? 'selected' : '' }}>User Management</option>
                                <option value="5" {{ request('category') == '5' ? 'selected' : '' }}>System</option>
                                <option value="6" {{ request('category') == '6' ? 'selected' : '' }}>Notes</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="action" class="form-label">Action</label>
                            <select name="action" id="action" class="form-select">
                                <option value="">All Actions</option>
                                <option value="1" {{ request('action') == '1' ? 'selected' : '' }}>Create</option>
                                <option value="2" {{ request('action') == '2' ? 'selected' : '' }}>Update</option>
                                <option value="3" {{ request('action') == '3' ? 'selected' : '' }}>Delete</option>
                                <option value="4" {{ request('action') == '4' ? 'selected' : '' }}>View</option>
                                <option value="5" {{ request('action') == '5' ? 'selected' : '' }}>Approve</option>
                                <option value="6" {{ request('action') == '6' ? 'selected' : '' }}>Reject</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="user" class="form-label">User</label>
                            <input type="text" name="user" id="user" class="form-control" value="{{ request('user') }}" placeholder="Username">
                        </div>
                        <div class="col-md-2">
                            <label for="object_id" class="form-label">Object ID</label>
                            <input type="number" name="object_id" id="object_id" class="form-control" value="{{ request('object_id') }}" placeholder="ID">
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary me-2">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                        <th>Object ID</th>
                                        <th>User</th>
                                        <th>Notes</th>
                                        <th width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $log->formatted_created_at }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $log->category_name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $log->action_name }}</span>
                                        </td>
                                        <td>
                                            <code>{{ $log->logObjID }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $log->logBy }}</strong>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $log->logNotes }}">
                                                {{ $log->logNotes }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        data-coreui-toggle="modal" 
                                                        data-coreui-target="#logDetailModal"
                                                        data-log-id="{{ $log->logID }}"
                                                        data-log-category="{{ $log->category_name }}"
                                                        data-log-action="{{ $log->action_name }}"
                                                        data-log-object="{{ $log->logObjID }}"
                                                        data-log-user="{{ $log->logBy }}"
                                                        data-log-notes="{{ $log->logNotes }}"
                                                        data-log-date="{{ $log->formatted_created_at }}"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-log-btn" 
                                                        data-log-id="{{ $log->logID }}"
                                                        title="Delete Log">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $logs->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No activity logs found</h5>
                            <p class="text-muted">No logs match your current filters or no logs have been created yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Detail Modal -->
<div class="modal fade" id="logDetailModal" tabindex="-1" role="dialog" aria-labelledby="logDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Log Details
                </h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Date & Time:</strong>
                        <p id="detailDate" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>User:</strong>
                        <p id="detailUser" class="text-muted"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Category:</strong>
                        <p><span id="detailCategory" class="badge bg-info"></span></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Action:</strong>
                        <p><span id="detailAction" class="badge bg-primary"></span></p>
                    </div>
                    <div class="col-md-12">
                        <strong>Object ID:</strong>
                        <p><code id="detailObject"></code></p>
                    </div>
                    <div class="col-md-12">
                        <strong>Notes:</strong>
                        <div id="detailNotes" class="border rounded p-3 bg-light"></div>
                    </div>
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

<!-- Include Log Modal Component -->
@include('components.log-modal')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle log detail modal
    $('#logDetailModal').on('show.coreui.modal', function(event) {
        var button = $(event.relatedTarget);
        
        $('#detailDate').text(button.data('log-date'));
        $('#detailUser').text(button.data('log-user'));
        $('#detailCategory').text(button.data('log-category'));
        $('#detailAction').text(button.data('log-action'));
        $('#detailObject').text(button.data('log-object'));
        $('#detailNotes').text(button.data('log-notes'));
    });
    
    // Handle delete log
    $('.delete-log-btn').click(function() {
        var logId = $(this).data('log-id');
        var row = $(this).closest('tr');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This log entry will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteLog(logId, row);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this log entry?')) {
                deleteLog(logId, row);
            }
        }
    });
    
    function deleteLog(logId, row) {
        $.ajax({
            url: '/logs/' + logId,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                    });
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting the log.');
            }
        });
    }
    
    // Refresh log history function for the modal component
    window.refreshLogHistory = function() {
        location.reload();
    };
});
</script>
@endpush