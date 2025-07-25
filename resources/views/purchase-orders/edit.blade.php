<x-app-layout>
    <style>
        .ck.ck-editor__editable_inline {
            min-height: 300px !important;
            max-height: 300px !important;
            overflow-y: auto;
        }
    </style>
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
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Edit Purchase Order</strong>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')



                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="poNo" class="form-label">PO Number</label>
                                    <input type="text" class="form-control" id="poNo" name="poNo" value="{{ $purchaseOrder->poNo }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="poClient" class="form-label">Client</label>
                                    <input type="text" class="form-control" id="poClientDisplay" value="{{ $purchaseOrder->client->company_name ?? 'N/A' }}" readonly>
                                    <input type="hidden" id="poClient" name="poClient" value="{{ $purchaseOrder->poClient }}">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                     <label for="poValue" class="form-label">Value</label>
                                     <input type="number" step="1" min="0" class="form-control" id="poValue" name="poValue" value="{{ (int)$purchaseOrder->poValue }}" required>
                                     <div class="form-text" id="formattedValue"></div>
                                 </div>

                                <div class="mb-3">
                                    <label for="poCurrency" class="form-label">Currency</label>
                                     <select class="form-control" id="poCurrency" name="poCurrency" required>
                                         @foreach(['IDR', 'USD'] as $currency)
                                             <option value="{{ $currency }}" {{ $purchaseOrder->poCurrency == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                                         @endforeach
                                     </select>
                                </div>

                                <div class="mb-3">
                                    <label for="poStatus" class="form-label">Status</label>
                                     @if($can_approve == 0 && $purchaseOrder->poStatus == 'Draft')
                                         <input type="text" class="form-control" id="poStatusDisplay" value="{{ $purchaseOrder->poStatus }}" readonly>
                                         <input type="hidden" id="poStatus" name="poStatus" value="{{ $purchaseOrder->poStatus }}">
                                     @else
                                         <select class="form-control" id="poStatus" name="poStatus" required>
                                             @foreach($statuses as $status)
                                                 <option value="{{ $status }}" {{ $purchaseOrder->poStatus == $status ? 'selected' : '' }}>{{ $status }}</option>
                                             @endforeach
                                         </select>
                                     @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="poStartDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="poStartDate" name="poStartDate" value="{{ \Carbon\Carbon::parse($purchaseOrder->poStartDate)->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="poEndDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="poEndDate" name="poEndDate" value="{{ \Carbon\Carbon::parse($purchaseOrder->poEndDate)->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="poTerm">Payment Term</label>
                                    <textarea class="form-control" id="poTerm" name="poTerm">{{ old('poTerm', $purchaseOrder->poTerm) }}</textarea>
                                </div>
                            </div>
                        </div>

                   

                        <div class="mb-3">
                            <label class="form-label"><h2>Services</h2></label>
                            
                        <div class="mb-3">
                            <label class="form-label">Remaining Budget To Allocate: <span id="remaining-budget"></span></label>
                        </div>
                            <div id="services-container">
                                @foreach($purchaseOrder->serviceItems as $index => $service)
                                    <div class="row mb-2 service-item">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="services[{{ $index }}][name]" value="{{ $service->serviceName }}" placeholder="Service Name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control service-value" name="services[{{ $index }}][value]" value="{{ (int)$service->serviceValue }}" placeholder="Value" step="1" min="0" required>
                                            <div class="form-text service-formatted-value"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-service">
                                                <svg class="icon">
                                                    <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-trash') }}"></use>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="add-service">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-plus') }}"></use>
                                </svg>Add Service
                            </button>
                        </div>


                        <div class="mb-3">
                            <label class="form-label"><h2>File/Attachments</h2></label>
                            @php
                                $filesArray = json_decode($purchaseOrder->poFiles, true) ?? [];
                            @endphp
                            @if(count($filesArray) > 0)
                                <div class="mb-3">
                                    <label class="form-label">Current Files (uncheck to remove)</label>
                                    <div class="list-group mb-3">
                                        @foreach($filesArray as $index => $file)
                                            <div class="list-group-item">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="checkbox" name="keepFiles[]" value="{{ $file['file'] }}" id="keepFile_{{ $index }}" checked>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label" for="keepFile_{{ $index }}">
                                                            <a href="{{ route('media.view', base64_encode($file['file'])) }}" target="_blank" rel="noopener noreferrer">
                                                                <strong>{{ $file['original_name'] }}</strong>
                                                            </a>
                                                            @if(isset($file['notes']))
                                                                <br>
                                                                <small class="text-muted">Notes: {{ $file['notes'] }}</small>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No files currently attached</p>
                            @endif

                            <label class="form-label">Add New Attachments</label>
                            <div id="file-inputs-container">
                                <div class="mb-2 file-input-group">
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="poFiles[]">
                                        <input type="text" class="form-control" name="fileNotes[]" placeholder="File notes (optional)">
                                        <button type="button" class="btn btn-danger remove-file">
                                            <svg class="icon">
                                                <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-trash') }}"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="add-file">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-plus') }}"></use>
                                </svg>Add Another File
                            </button>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Purchase Order</button>
                            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>

        // Initialize CKEditor for Payment Term
        ClassicEditor
            .create(document.querySelector('#poTerm'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ]
            })
             .catch(error => {
                  console.error(error);
              });



        // Function to calculate and display remaining budget
        function updateRemainingBudget() {
            var poValue = parseFloat(document.getElementById('poValue').value) || 0;
            var totalServiceValue = 0;
            document.querySelectorAll('.service-value').forEach(function(input) {
                totalServiceValue += parseFloat(input.value) || 0;
            });
            var remainingBudget = poValue - totalServiceValue;
            let formattedRemainingBudget = formatCurrency(remainingBudget, poCurrency.value);
            if (remainingBudget === 0) {
                formattedRemainingBudget += " - <strong>Great! PO Value Distributed Correctly</strong>";
            }
            document.getElementById('remaining-budget').innerHTML = formattedRemainingBudget;
        }

        // Add Service Item
        document.getElementById('add-service').addEventListener('click', function() {
            const container = document.getElementById('services-container');
            const index = container.children.length;
            
            const serviceRow = document.createElement('div');
            serviceRow.className = 'row mb-2 service-item';
            serviceRow.innerHTML = `
                <div class="col-md-6">
                    <input type="text" class="form-control" name="services[${index}][name]" placeholder="Service Name" required>
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control service-value" name="services[${index}][value]" placeholder="Value" step="1" min="0" required>
                    <div class="form-text service-formatted-value"></div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-service">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-trash') }}"></use>
                        </svg>
                    </button>
                </div>
            `;
            
            container.appendChild(serviceRow);
            
            // Add event listener to new service value input
            const newValueInput = serviceRow.querySelector('.service-value');
            newValueInput.addEventListener('input', () => {
                updateServiceValue(newValueInput);
                updateRemainingBudget();
            });
            updateServiceValue(newValueInput);
            updateRemainingBudget();
        });

        // Remove Service Item
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-service')) {
                e.target.closest('.service-item').remove();
                updateRemainingBudget(); // Update budget after removing service
            }
        });

        // Currency formatting function
        function formatCurrency(value, currency) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }

        // Update formatted value display
        function updateFormattedValue(value, currency, targetElement) {

            if (value && !isNaN(value)) {
                targetElement.textContent = formatCurrency(value, currency);
            } else {
                targetElement.textContent = '';
            }
        }

        // Main value handling
        const poValue = document.getElementById('poValue');
        const poCurrency = document.getElementById('poCurrency');
        const formattedValue = document.getElementById('formattedValue');

        function updateMainValue() {
            updateFormattedValue(poValue.value, poCurrency.value, formattedValue);
            updateRemainingBudget();
        }

        poValue.addEventListener('input', updateMainValue);
        poCurrency.addEventListener('change', updateMainValue);

        // Initial format
        updateMainValue();
        updateRemainingBudget();

        // Service handling
        function updateServiceValue(input) {
            const formattedDiv = input.nextElementSibling;

            updateFormattedValue(input.value, poCurrency.value, formattedDiv);
        }

        // Update all service values when currency changes
        function updateAllServiceValues() {
            document.querySelectorAll('.service-value').forEach(input => {
                updateServiceValue(input);
            });
        }

        poCurrency.addEventListener('change', updateAllServiceValues);

        // Initialize existing service values
        document.querySelectorAll('.service-value').forEach(input => {
            input.addEventListener('input', () => {
                updateServiceValue(input);
                updateRemainingBudget();
            });

            updateServiceValue(input);
        });







        // File handling
        document.getElementById('add-file').addEventListener('click', function() {
            const container = document.getElementById('file-inputs-container');
            const fileInputGroup = document.createElement('div');
            fileInputGroup.className = 'mb-2 file-input-group';
            fileInputGroup.innerHTML = `
                <div class="input-group">
                    <input type="file" class="form-control" name="poFiles[]">
                    <input type="text" class="form-control" name="fileNotes[]" placeholder="File notes (optional)">
                    <button type="button" class="btn btn-danger remove-file">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg#cil-trash') }}"></use>
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(fileInputGroup);
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-file')) {
                const fileInputGroup = e.target.closest('.file-input-group');
                if (document.querySelectorAll('.file-input-group').length > 1) {
                    fileInputGroup.remove();
                }
            }
        });
    </script>
    @endpush
</x-app-layout>