<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Features Management') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Features List</strong>
                    <a href="{{ route('appsetting.appfeature.create') }}" class="btn btn-primary float-end">
                        <i class="cil-plus"></i> Add New Feature
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover border">
                            <thead>
                                <tr>
                                    <th>Feature Name</th>
                                    <th>Icon</th>
                                    <th>Path</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($features as $feature)
                                    <tr>
                                        <td>{{ $feature->featureName }}</td>
                                        <td><i class="{{ $feature->featureIcon }}"></i> {{ $feature->featureIcon }}</td>
                                        <td>{{ $feature->featurePath }}</td>
                                        <td>
                                            @if($feature->featureActive)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Not Active</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('appsetting.appfeature.edit', $feature) }}" 
                                                   class="btn btn-primary btn-sm me-2" 
                                                   data-coreui-toggle="tooltip" 
                                                   data-coreui-placement="top" 
                                                   title="Edit">
                                                    <i class="cil-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('appsetting.appfeature.destroy', $feature) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-secondary btn-sm" 
                                                            data-coreui-toggle="tooltip" 
                                                            data-coreui-placement="top" 
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this feature?')">
                                                        <i class="cil-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No features found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
     
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-coreui-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new coreui.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    @endpush
</x-app-layout>