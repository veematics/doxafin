<x-app-layout>
    <div class="container-lg">
        <div class="row mb-4">
            <div class="col">
                <h2>User Management</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">User Management</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Users List</span>
                <a href="{{ route('appsetting.users.create') }}" class="btn btn-primary btn-sm">Add User</a>
            </div>
            <div class="card-body">
                <table class="table" id="usersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('appsetting.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                                <button type="button" 
                                        class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                                        onclick="toggleUserStatus('{{ $user->id }}', '{{ $user->is_active ? 0 : 1 }}')">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <form action="{{ route('appsetting.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
    
        <script>
              
            async function toggleUserStatus(userId, status) {
                try {
                    const response = await fetch(`/appsetting/users/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ is_active: status })
                    });
    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        window.toast.show(data.error || 'Failed to update user status', 'error');
                    }
                } catch (error) {
                    window.toast.show('Failed to update user status', 'error');
                }
            }
        </script>
        @endpush
</x-app-layout>