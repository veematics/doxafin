<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Management') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
    <div class="row align-items-center mb-4">
    <div class="col">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard"> Menu</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item">Menu</li>
             
            </ol>
        </nav>
    </div>
    <div class="col-auto">
        
    </div>
</div>
        <div class="container-lg">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Menus</strong>
                        <a href="{{ route('appsetting.menu.create') }}" class="btn btn-primary btn-sm">
                            <i class="cil-plus"></i> Create New Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                              
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr>
                                        <td>{{ $menu->name }}</td>
                            
                                        <td>{{ $menu->description }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('appsetting.menu.edit', $menu) }}" 
                                                   class="btn btn-primary btn-sm me-2">
                                                    <i class="cil-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('appsetting.menu.destroy', $menu) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-secondary btn-sm"
                                                            onclick="return confirm('Are you sure?')">
                                                        <i class="cil-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No menus found.</td>
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