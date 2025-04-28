<x-app-layout>
    @php
        $featureId = 5;
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
                    <div class="fs-2 fw-semibold">Purchase Orders</div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Purchase Orders</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-auto">
                    <a href="{{ route('purchase-orders.add') }}" class="btn btn-primary">
                        <i class="cil-plus"></i> New Purchase Order
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search PO Number or Client..." id="searchInput">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="cil-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 ms-auto text-end">
                            <select class="form-select" id="perPageSelect" style="width: auto; display: inline-block;">
                                <option value="20">20 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                                <option value="500">500 per page</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Client</th>
                                    <th>Value</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Detail Info Snapshot</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseOrders as $po)
                                    <tr class="border-t">
                                        <td class="py-3">{{ $po->poNo }}</td>
                                        <td>{{ $po->client->company_name }}</td>
                                        <td>{{ $po->poCurrency }} {{ number_format($po->poValue, 0) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($po->poStartDate)->format('d M Y') }}</td>
                                        <td>{{ $po->poEndDate ? \Carbon\Carbon::parse($po->poEndDate)->format('d M Y') : '-' }}</td>
                                        <td>Services:
                                            <span class="badge bg-primary cursor-pointer" 
                                                  onclick="showServices({{ $po->poID }})">
                                                {{ $po->serviceItems->count() }}
                                            </span>
                                            <br/>
                                            Invoices:
                                            <span class="badge bg-primary cursor-pointer"
                                                  onclick="showInvoices({{ $po->poID }})">0
                                                {{-- {{ $po->invoiceItems->count() }} --}}
                                            </span>
                                            <br/>
                                            Payment:
                                            
                                            <span class="badge bg-primary cursor-pointer"
                                                  onclick="showInvoices({{ $po->poID }})">0
                                                {{-- {{ $po->invoiceItems->count() }} --}}
                                            </span>
                                            <br/>
                                            Outstanding:
                                            
                                            <span class="badge bg-primary cursor-pointer"
                                                  onclick="showInvoices({{ $po->poID }})">0
                                                {{-- {{ $po->invoiceItems->count() }} --}}
                                            </span>
                                        </td>
                                        <td>{{ $po->poStatus }}</td>
                                        <td class="text-right">
                                            <div class="d-flex flex-column">
                                            @if (($po->poStatus == "Draft" && $can_edit=='1')||$can_approve=='1')
                                            <a href="{{ route('purchase-orders.edit', $po->poID) }}" class="text-decoration-none mb-1" style="font-size: 0.9rem; line-height: 0.9rem"><i class="cil-pencil me-1"></i>Edit</a>
                                                 
                                            @endif
                                                @if ($po->poStatus == "Draft")
                                                     
                                                    <a href="#" class="text-decoration-none mb-1" style="font-size: 0.9rem; line-height: 0.9rem" onclick="confirmApproval(event, {{ $po->poID }}, '{{ route('purchase-orders.approval-request', $po->poID) }}')"><i class="cil-check-circle me-1"></i>Submit For Approval</a>  
                                                    @if ( $can_approve!=1)
                                                    @endif
                                                @endif
                                                                                               
                                                <a href="{{ route('purchase-orders.show', $po) }}" class="text-decoration-none mb-1" style="font-size: 0.9rem; line-height: 0.9rem"><i class="cil-description me-1"></i>View Details</a>
                                                @if ($po->poStatus != "Draft")
                                                 <a href="" class="text-decoration-none mb-1" style="font-size: 0.9rem; line-height: 0.9rem"><i class="cil-sync me-1"></i>Request Changes</a>
                                                @endif
                                                @if ( $can_delete==1)
                                                <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" class="mb-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-danger" style="font-size: 0.9rem; line-height: 0.9rem" onclick="return confirm('Are you sure?')"><i class="cil-trash me-1"></i>Delete</button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No purchase orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($purchaseOrders, 'links'))
                        <div class="mt-3">
                            {{ $purchaseOrders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

   

    @push('scripts')
    <script>
      
        function showServices(poId) {
            // AJAX call to fetch services
            fetch(`/po/${poId}/services`)
                .then(response => response.json())
                .then(data => {
                    // Create modal content
                    let html = `<div class="modal-header">
                                    <h5 class="modal-title">Services for PO ${data.poNo}</h5>
                                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Service Name</th>
                                                <th>Value</th>
                                                <th>Recurring</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                    
                    data.services.forEach(service => {
                        html += `<tr>
                                    <td>${service.serviceName}</td>
                                    <td>${data.poCurrency} ${Math.floor(service.value).toLocaleString()}</td>
                                    <td>${service.is_recurring ? 'Yes' : 'No'}</td>
                                </tr>`;
                    });
                    
                    html += `</tbody></table></div>`;
                    
                    // Show modal
                    const modal = new coreui.Modal(document.getElementById('servicesModal'));
                    document.getElementById('servicesModal').querySelector('.modal-content').innerHTML = html;
                    modal.show();
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check for success notification
            const urlParams = new URLSearchParams(window.location.search);
            const poId = urlParams.get('po');
            const status = urlParams.get('status');
            const valid = urlParams.get('valid');
            
            if (valid === '1' && poId) {
                window.toast.show(`PO#${poId} registered successfully. <br/>Status: ${status} `, 'success');
            }

            // ... existing search functionality code ...
        });
    </script>
    @endpush


    @component('components.approval-modal')
    @endcomponent

    <div class="modal fade" id="servicesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</x-app-layout>