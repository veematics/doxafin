<x-app-layout>
    <div class="c-wrapper">
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="page-title-box">
                                    <h4 class="mb-0">Role Members: {{ $role->display_name }}</h4>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('appsetting.roles.index') }}">Roles</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Members</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#addMemberModal">
                                    <i class="cil-plus"></i> Add Member
                                </button>
                                <button type="button" class="btn btn-success ms-2" id="saveMembersBtn" style="display:none;">
                                    <i class="cil-save"></i> Save Members
                                </button>
                            </div>
                        </div>

                        <!-- Add Member Modal -->
                        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addMemberModalLabel">Add Member to Role</h5>
                                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="userFilter" placeholder="Filter by name or email...">
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="usersTable">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($allUsers as $user)
                                                        <tr>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>
                                                                <button class="btn btn-sm btn-success add-member-btn" data-user-id="{{ $user->id }}">
                                                                    <i class="cil-plus"></i> Add
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                  

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($users->count() > 0)
                                                @foreach($users as $user)
                                                    <tr data-user-id="{{ $user->id }}">
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger remove-member-btn" data-user-id="{{ $user->id }}">
                                                                <i class="cil-trash"></i> Remove
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center py-4">
                                                        <div class="text-muted">No members found for this role</div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script>
            let selectedMembers = [];
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ === 'undefined') {
                    console.error('jQuery is not loaded');
                    return;
                }
                
                $(document).ready(function() {
                    // Filter users table
                    $('#userFilter').on('keyup', function() {
                        const value = $(this).val().toLowerCase();
                        $('#usersTable tbody tr').each(function() {
                            const text = $(this).text().toLowerCase();
                            $(this).toggle(text.indexOf(value) > -1);
                        });
                    });
                    
                    // Add member handler
                    $(document).on('click', '.add-member-btn', function() {
                        const userId = $(this).data('user-id');
                        const $row = $(this).closest('tr');
                        const userName = $row.find('td:first').text();
                        const userEmail = $row.find('td:nth-child(2)').text();
                        
                        $row.remove();
                        
                        if(!selectedMembers.some(m => m.id === userId)) {
                            selectedMembers.push({
                                id: userId,
                                name: userName,
                                email: userEmail
                            });
                            
                            // Update main table
                            const newRow = `
                                <tr data-user-id="${userId}">
                                    <td>${userName}</td>
                                    <td>${userEmail}</td>
                                    <td>
                                        Pending
                                    </td>
                                </tr>
                            `;
                            $('table.table-striped tbody').prepend(newRow);
                            $('#saveMembersBtn').show();
                        }
                    });

                    // Add remove member handler
            $(document).on('click', '.remove-member-btn', function() {
                const userId = $(this).data('user-id');
                const $row = $(this).closest('tr');
                
                if(confirm('Are you sure you want to remove this member?')) {
                    fetch(`/appsetting/roles/{{ $role->id }}/remove-member/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            $row.remove();
                        }
                    });
                }
            });
                
                    // Save members to database
                    $('#saveMembersBtn').click(function() {
                        if(selectedMembers.length > 0) {
                            fetch("{{ route('appsetting.roles.add-members', $role->id) }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    users: selectedMembers.map(m => m.id)
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if(data.success) {
                                    location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error saving members. Please try again.');
                            });
                        }
                    });
                });
            }); // <-- This was the missing closing brace for DOMContentLoaded

            function addMember(userId) {
                // AJAX call to add member to role
                fetch(`/appsetting/roles/{{ $role->id }}/add-member/${userId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    }
                });
            }
            
            
        </script>
    @endpush
</x-app-layout>