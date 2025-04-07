<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Management') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
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
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ ucfirst($menu->type) }}</td>
                                        <td>{{ $menu->description }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('appsetting.menu.edit', $menu) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="cil-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('appsetting.menu.destroy', $menu) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm"
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