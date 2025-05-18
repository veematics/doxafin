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
      
    <x-slot name="header">
            <h1 class="text-2xl font-bold mb-6">Purchase Order Request Changes</h1>
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
                                    @php
                                        $files = json_decode($purchaseOrder->poFiles, true);
                                        
                                    @endphp
                                    @if($purchaseOrder->poFiles && count($files) > 0)
                                        <ul>
                                            @foreach($files as $file)
                                       
                                                <li>
                                                    <a href="{{ route('media.view', base64_encode($file['file'])) }}" target="_blank" rel="noopener noreferrer">
                                                        <strong>{{ basename($file['original_name']) }}</strong>
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
    
                   
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Operational & Status
                            
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Status: <strong>{{ $purchaseOrder->poStatus ?? 'Not Set' }}</strong></p>
                           
                        </div>
                    </div>
                    @if($requestChange)
                 
                    <div class="card  mb-3">
                        <div class="card-header">Request Changes</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Requested By:</dt>
                                <dd class="col-sm-8">{{ $requestChange->creator->name ?? 'N/A' }}</dd>
                                <dt class="col-sm-4">Request Date:</dt>
                                <dd class="col-sm-8">{{ $requestChange->created_at->format('d-m-Y H:i') }}</dd>
                                <dt class="col-sm-4">Current Request Status:</dt>
                                <dd class="col-sm-8">{{ $requestChange->status }}</dd>
                                
                                
                            </dl>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">History</div>
                        <div class="card-body">
                            <dd class="col-sm-8">
                                @if(is_array($requestChange->changes))
                                    <ul class="list-unstyled">
                                        @foreach($requestChange->changes as $field => $change)
                                            <li>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                                {{ $change['before'] }} → {{ $change['after'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $requestChange->changes }}
                                @endif
                            </dd>
                        </div>
                    </div>
                @endif
    
                   
    
                   
    
                   
                </div>
            </div>
        </div>
        @component('components.approval-modal')
        @endcomponent
    
       
    </x-app-layout>