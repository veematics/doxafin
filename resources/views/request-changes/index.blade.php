<x-app-layout>
@push('scripts')
    <script src="{{ asset('js/request-changes.js') }}"></script>
@endpush
@php
        $featureId = 11;
        $userId = auth()->id();
        $cacheKey = 'user_permissions_' . $userId;
        $permissions = Cache::get($cacheKey);
        $can_view = $permissions[$featureId][0]->can_view;
        $can_create = $permissions[$featureId][0]->can_create;
        $can_approve = $permissions[$featureId][0]->can_approve;
        $can_edit = $permissions[$featureId][0]->can_edit;
        $can_delete = $permissions[$featureId][0]->can_delete;
@endphp
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <div class="fs-2 fw-semibold">Request Changes</div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Request Changes</li>
                        </ol>
                    </nav>
                </div>
                @if($can_create)
                <div class="col-auto">
                    <a href="{{ route('request-changes.create') }}" class="btn btn-primary">
                        <i class="cil-plus"></i> New Request
                    </a>
                </div>
                @endif
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-coreui-toggle="tab" href="#module-view" role="tab">
                                <i class="cil-grid"></i> By Module
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-coreui-toggle="tab" href="#status-view" role="tab">
                                <i class="cil-list"></i> By Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-coreui-toggle="tab" href="#archived-view" role="tab">
                                <i class="cil-archive"></i> Archived
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- Module View Tab -->
                        <div class="tab-pane active" id="module-view" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <select class="form-select global-status-filter">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="request-revision">Request Revision</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select global-client-filter">
                                        <option value="">All Clients</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                @php
                                    $groupedChanges = $activeRC->groupBy('category');
                                    $changes=$groupedChanges[null];
                                    $currentCategoryRC = null;
                            
                                        
                                @endphp

                                @foreach($changes as $items => $item)
                                    @php
                                 
                      
                                if($currentCategoryRC!=$item[0]->category ){
                                    if($currentCategoryRC!=null){
                                        echo '</tbody>
                                        </table>
                                    </div></div>
                                        </div>
                                        </div>';
                                    };
                                    $currentCategoryRC=$item[0]->category;
                                    $isChangeCategory=true;
                                }else{
                                    $isChangeCategory=false;
                                }

                                    @endphp
                                    @if ( $isChangeCategory=true)
                                    <div class="col-md-6 mb-4">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h4 class="card-title mb-0">{{ $currentCategoryRC }}</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table border mb-0">
                                                        <thead class="table-light fw-semibold">
                                                            <tr class="align-middle">
                                                                <th>Title</th>
                                                                <th>Status</th>
                                                                <th>Created At</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                    @endif
                                
                                                @foreach($item as $data)
                                               
                                                    <tr class="align-middle">
                                                        <td class="small">{{ $data->title }}<br/>C: <a href="{{ route('clients.show', $data->client_id) }}" target="_blank">{{ $data->client_name }}</a></td>
                                                        <td>
                                                            <span class="badge rounded-pill bg-{{ 
                                                            $data->status === 'approved' ? 'success' : 
                                                            ($data->status === 'pending' ? 'warning' : 
                                                            ($data->status === 'rejected' ? 'danger' : 'orange')) 
                                                        }}">
                                                            {{ ucfirst($data->status) }}
                                                        </span>
                                                        </td>
                                                        <td class="small">{{ \Carbon\Carbon::parse($data->created_at)->format('d F Y H:i') }}</td>
                                                        <td>
                                                            <div class="d-flex flex-column gap-1">
    <a href="#" class="btn btn-sm btn-info" style="font-size: 0.8rem;">View History</a>
    @if($can_edit)
        <a href="#" class="btn btn-sm btn-primary" style="font-size: 0.8rem;">Respond</a>
        <form action="#" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-secondary" style="font-size: 0.8rem;">Archive RC</button>
        </form>
    @endif
</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                                        </div>
                                     </div>
                                    </div>
                            </div>
                        </div>

                        <!-- Status View Tab -->
                        <div class="tab-pane" id="status-view" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <select id="statusFilter" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="request-revision">Request Revision</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select id="clientFilter" class="form-select">
                                        <option value="">All Clients</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table border mb-0">
                                    <thead class="table-light fw-semibold">
                                        <tr class="align-middle">
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Client Name</th>
                                            <th>Created Date</th>
                                            <th>Created By</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                
                            </div>
                        </div>

                        <!-- Archived View Tab -->
                        <div class="tab-pane" id="archived-view" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <select id="archivedClientFilter" class="form-select">
                                        <option value="">All Clients</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table border mb-0">
                                    <thead class="table-light fw-semibold">
                                        <tr class="align-middle">
                                            <th>Category</th>
                                            <th>Client Name</th>
                                            <th>Notes</th>
                                            <th>Archived Date</th>
                                            <th>Original Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($archivedChanges ?? [] as $change)
                                            <tr class="align-middle">
                                                <td>{{ $change->category }}</td>
                                                <td>{{ $change->client->name ?? 'N/A' }}</td>
                                                <td>{{ Str::limit($change->notes, 50) }}</td>
                                                <td>{{ $change->archived_at->format('d F Y H:i') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $change->original_status }}">
                                                        {{ ucfirst($change->original_status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('request-changes.show', $change) }}" 
                                                           class="btn btn-sm btn-info">View</a>
                                                        @if($can_edit)
                                                            <form action="{{ route('request-changes.unarchive', $change) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning">Unarchive</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    No archived changes found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(isset($archivedChanges))
                                <div class="mt-4">
                                    {{ $archivedChanges->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
@push('scripts')
    <script src="{{ asset('js/request-changes/module-filters.js') }}"></script>
@endpush
</x-app-layout>