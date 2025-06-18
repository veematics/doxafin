<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="page-title-box">
                        <h4 class="mb-0">Roles Management</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Roles</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('appsetting.roles.create') }}" class="btn btn-primary">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-plus') }}"></use>
                        </svg>Create New Role
                    </a>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped border mb-0 align-middle">
                            <thead class="bg-light fw-semibold">
                                <tr>
                                    <th>Role Name</th>
                                    <th class="text-center">No. of Members</th>
                                    <th class="text-end" width="280">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $role->display_name }}</div>
                                            <div class="small text-medium-emphasis">{{ $role->name }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-gradient">{{ $role->users_count }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group gap-2">
                                                <a href="{{ route('appsetting.roles.members', $role) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-coreui-toggle="tooltip" 
                                                   title="Manage Members">
                                                    <svg class="icon">
                                                        <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-people') }}"></use>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('appsetting.roles.edit', $role) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   data-coreui-toggle="tooltip" 
                                                   title="Edit Role">
                                                    <svg class="icon">
                                                        <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-pencil') }}"></use>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('appsetting.roles.duplicate', $role) }}" 
                                                      method="POST" 
                                                      class="d-inline" >
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-success" 
                                                            data-coreui-toggle="tooltip" 
                                                            title="Duplicate Role">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-copy') }}"></use>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('appsetting.roles.destroy', $role) }}" 
                                                      method="POST" 
                                                      class="d-inline" style="position:relative;left:-10px">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger ms-2" 
                                                            data-coreui-toggle="tooltip" 
                                                            title="Delete Role"
                                                            onclick="return confirm('Are you sure you want to delete this role?');">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-trash') }}"></use>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="text-medium-emphasis">No roles found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>