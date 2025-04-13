<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <div class="fs-2 fw-semibold">Clients</div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">Clients</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-md-6">
                            <form action="{{ route('clients.index') }}" method="GET" id="searchForm">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                        placeholder="Search by company or contact name..." 
                                        value="{{ request('search') }}">
                                    <select class="form-select" style="max-width: 120px;" name="per_page" onchange="document.getElementById('searchForm').submit()">
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 items</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 items</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 items</option>
                                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All items</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="cil-search"></i>
                                    </button>
                                    @if(request('search') || request('per_page'))
                                        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
                                            <i class="cil-x"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                                <i class="cil-plus"></i> {{ __('Add New Client') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Code</th>
                                    <th>Assigned To</th>
                                    <th>Contacts</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                    <tr>
                                        <td>{{ $client->company_name }}</td>
                                        <td>{{ $client->company_code }}</td>
                                        <td>{{ $client->assignedUser?->name ?? 'Unassigned' }}</td>
                                        <td>{{ $client->contacts_count ?? $client->contacts->count() }}</td>
                                        <td>
                                            <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-info">
                                                <i class="cil-people"></i>
                                            </a>
                                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-primary">
                                                <i class="cil-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No clients found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($clients->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $clients->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>