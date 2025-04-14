<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">{{ __('CSV Data Management') }}</h2>
        <a href="{{ route('csv-data.create') }}" class="btn btn-primary">
            <i class="cil-plus"></i> {{ __('Add New CSV Data') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Data Name') }}</th>
                            <th>{{ __('Last Updated') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($csvData as $data)
                            <tr>
                                <td>{{ $data->data_name }}</td>
                                <td>{{ $data->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-coreui-toggle="modal" 
                                            data-coreui-target="#viewModal{{ $data->id }}">
                                        <i class="cil-list"></i>
                                    </button>
                                    <a href="{{ route('csv-data.edit', $data) }}" class="btn btn-sm btn-primary">
                                        <i class="cil-pencil"></i>
                                    </a>
                                    <form action="{{ route('csv-data.destroy', $data) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('{{ __('Are you sure?') }}')">
                                            <i class="cil-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            
                            <!-- View Modal for each CSV data -->
                            <div class="modal fade" id="viewModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $data->data_name }}</h5>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- Replace the modal body with this simpler version -->
                                        <!-- Replace the modal body content -->
                                        <div class="modal-body">
                                            <div class="table-responsive" id="csvContent{{ $data->id }}">
                                                <div class="text-center">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Update the script -->
                                        @push('scripts')
                                        <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const modals = document.querySelectorAll('.modal');
                                            modals.forEach(modal => {
                                                modal.addEventListener('show.coreui.modal', function(event) {
                                                    const modalId = this.getAttribute('id');
                                                    const dataId = modalId.replace('viewModal', '');
                                                    const contentDiv = document.querySelector(`#csvContent${dataId}`);
                                                    
                                                    fetch(`/csv-data/${dataId}/view`)
                                                        .then(response => response.text())
                                                        .then(html => {
                                                            contentDiv.innerHTML = html;
                                                        })
                                                        .catch(error => {
                                                            contentDiv.innerHTML = '<div class="alert alert-danger">Error loading data</div>';
                                                            console.error('Error:', error);
                                                        });
                                                });
                                            });
                                        });
                                        </script>
                                        @endpush
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">{{ __('Close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('No CSV data found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>