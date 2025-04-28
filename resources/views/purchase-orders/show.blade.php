<x-app-layout>

  
<x-slot name="header">
        <h1 class="text-2xl font-bold mb-6">Purchase Order Monitoring</h1>
    </x-slot>
    <div class="container mx-auto px-4 py-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">PO Overview</div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Client:</dt><dd class="col-sm-9">{{ $purchaseOrder->client->company_name }}</dd>
                                    <dt class="col-sm-3">PO Number:</dt><dd class="col-sm-9">{{ $purchaseOrder->poNo }}</dd>
                                    <dt class="col-sm-3">PO Value:</dt><dd class="col-sm-9">{{ $purchaseOrder->poCurrency }} {{ number_format($purchaseOrder->poValue, 0) }}</dd>
                                    <dt class="col-sm-3">Start Date:</dt><dd class="col-sm-9">{{ $purchaseOrder->poStartDate ? \Carbon\Carbon::parse($purchaseOrder->poStartDate)->format('d-m-Y') : 'N/A' }}</dd>
                                    <dt class="col-sm-3">End Date:</dt><dd class="col-sm-9">{{ $purchaseOrder->poEndDate ? \Carbon\Carbon::parse($purchaseOrder->poEndDate)->format('d-m-Y') : 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Payment Terms</div>
                            <div class="card-body payment-terms-display" style="max-height: 200px; overflow-y: auto;">
                                {!! $purchaseOrder->poTerm !!}
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Services</div>
                            <div class="card-body">
                                @if($purchaseOrder->serviceItems && $purchaseOrder->serviceItems->count() > 0)
                                    <ul>
                                        @foreach($purchaseOrder->serviceItems as $service)
                                            <li>
                                                <strong>{{ $service->serviceName }}</strong><br>
                                                Value: {{ $purchaseOrder->poCurrency }} {{ number_format($service->serviceValue, 0) }}<br>
                                                Is Recurring: {{ $service->is_recurring ? 'Yes' : 'No' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No services found for this purchase order</p>
                                @endif
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">Files</div>
                            <div class="card-body">
                                @if($purchaseOrder->poFiles && count($purchaseOrder->poFiles) > 0)
                                    <ul>
                                        @foreach($purchaseOrder->poFiles as $file)
                                            <li>
                                                <a href="{{ route('media.view', base64_encode($file['file'])) }}" target="_blank" rel="noopener noreferrer">
                                                    <strong>{{ basename($file['file']) }}</strong>
                                                </a><br>
                                                <small><em>Notes:</em> {{ $file['notes'] ?? 'No notes' }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No files attached to this purchase order</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Operational & Status</div>
                    <div class="card-body">
                        <p class="text-muted">Status: <strong>{{ $purchaseOrder->poStatus ?? 'Not Set' }}</strong></p>
                        Operation:<br/>
                        <ul>
                            <li>Edit</li>
                            <li>Change Request</li>
                            <li>Add Activity Logs</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Invoice Status</div>
                    <div class="card-body">
                        <p class="text-muted">Invoice Created:0
                            <br/>
                                Invoice Completed:0<br/>
                                Active Invoice Due:0<br/>
                                Total Invoice Value:Rp. 0<br/>
                                Total Remaining Balance: Rp. 0<br/>
                        </p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Payment Status</div>
                    <div class="card-body">
                        <p class="text-muted"><p class="text-muted">
                               No of Payment: 0<br/>
                               Total Payment Value:Rp. 0<br/>
                               Remaining Payment Value:Rp. 0<br/>
                                
                        </p></p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Activity Log</div>
                    <div class="card-body">
                        <p class="text-muted">Recent activities and history will be displayed here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>