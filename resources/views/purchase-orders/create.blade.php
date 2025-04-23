<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Register Purchase Order</h1>
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="card">
            {{-- Card Header with Progress Steps --}}
            <div class="card-header" id="progressPO">
                <div class="progress-steps d-flex justify-content-between align-items-center position-relative mb-4">
                    {{-- Progress Bar Line --}}
                    <div class="progress position-absolute" style="height: 2px; width: 100%; z-index: 0;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    {{-- Individual Steps --}}
                    <div class="step active" data-step="1">
                        <div class="step-circle">1</div>
                        <div class="step-text">Choose Client</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">2</div>
                        <div class="step-text">PO Overview</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">3</div>
                        <div class="step-text">PO Services</div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="step-circle">4</div>
                        <div class="step-text">Files</div>
                    </div>
                    <div class="step" data-step="5">
                        <div class="step-circle">5</div>
                        <div class="step-text">Confirmation</div>
                    </div>
                </div>
            </div>

            {{-- Card Progress Header (Initially Hidden) --}}
            <div class="card-progress d-none">
                {{-- Content dynamically updated by JS --}}
            </div>

            <div class="card-body">
                <form id="poForm" action="{{ route('purchase-orders.store') }}" method="POST" enctype="multipart/form-data"> {{-- Added enctype for file uploads --}}
                    @csrf

                    {{-- Step 1: Client Selection --}}
                    <div class="form-step" id="step1">
                        <div class="mb-4">
                            <label for="poClient" class="form-label">Select Client</label>
                            <x-select2
                                id="poClient"
                                name="poClient"
                                :options="$clients->pluck('company_name', 'id')->toArray()"
                                placeholder="Select a client"
                                required
                            />
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>

                    {{-- Step 2: PO Details --}}
                    <div class="form-step d-none" id="step2">
                        <div class="mb-4 row">
                            <div class="col-md-4">
                                <label for="poNo" class="form-label">PO Number</label>
                                <input type="text" name="poNo" id="poNo" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="poCurrency" class="form-label">PO Currency</label>
                                <select name="poCurrency" id="poCurrency" class="form-control" required>
                                    <option value="IDR">IDR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="poValue" class="form-label">PO Value</label>
                                <input type="text" name="poValue" id="poValue" class="form-control" required>
                                <span id="formattedValue" class="text-muted small"></span>
                            </div>
                        </div>
                        <div class="mb-4 row">
                            <div class="col-md-3">
                                <label for="poStartDate" class="form-label">Start Date</label>
                                <x-singledate-filter
                                    id="poStartDate"
                                    name="poStartDate"
                                    placeholder="Select start date"
                                    required
                                />
                            </div>
                            <div class="col-md-3">
                                <label for="poEndDate" class="form-label">End Date</label>
                                <x-singledate-filter
                                    id="poEndDate"
                                    name="poEndDate"
                                    placeholder="Select end date"
                                    required {{-- Make End Date required as button is removed --}}
                                />
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                {{-- Removed the "Set No End Date" button --}}
                            </div>
                        </div>
                        <div class="mb-4">
                            <x-ckeditor
                                id="poTerm"
                                name="poTerm"
                                height="300px"
                                label="{{ __('Payment Terms') }}"
                            />
                            <small class="text-muted">Payment terms are automatically loaded from client settings</small>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>

                    {{-- Step 3: PO Services --}}
                    <div class="form-step d-none" id="step3">
                        <div class="row">
                            {{-- Column 1: Services List --}}
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Available Services</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="serviceSearch" placeholder="Search services...">
                                        </div>
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-hover" id="servicesTable">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Item</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Populated by JS --}}
                                                    <tr><td colspan="3" class="text-center">Loading services...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Column 2: Selected Services --}}
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                         <h5 class="mb-0">Item/Services in This PO#<span id="poNumberDisplay"></span>. Total Product/Services : <span id="selectedItemsCount">0</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <strong>Budget to Allocate:</strong> <span id="remainingBudget"></span>
                                            </div>
                                        </div>
                                        <div id="selectedServices" class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 45%">Item Name</th>
                                                        <th style="width: 25%">Item Value</th>
                                                        <th style="width: 15%">Is Recurring</th>
                                                        <th style="width: 15%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Populated by JS --}}
                                                    <tr class="no-data"><td colspan="4" class="text-center text-muted fst-italic">No services added yet.</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>

                    {{-- Step 4: Files --}}
                    <div class="form-step d-none" id="step4">
                        <h3>Files and Documentation</h3>
                        <div class="mb-4">
                            <button type="button" class="btn btn-primary" id="addFileBtn">Add File</button>
                        </div>
                        <div id="fileList" class="mb-4">
                            <p class="text-muted fst-italic no-files">No files added yet.</p> {{-- Initial message --}}
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>

                    {{-- Step 5: Confirmation --}}
                    <div class="form-step d-none" id="step5">
                        <h3>Confirmation</h3>
                        {{-- Add confirmation details display here --}}
                        <p class="text-muted">Review your Purchase Order details before submitting.</p>
                        <div id="confirmationDetails">
                            {{-- Dynamically populated summary --}}
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="submit" class="btn btn-success">Register Purchase Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .progress-steps {
            padding: 20px 0;
        }
        .step {
            z-index: 1;
            text-align: center;
            padding: 10px;
            min-width: 100px; /* Adjust as needed */
            background: var(--cui-body-bg);
            border-radius: 15px; /* Rounded background for text */
            position: relative; /* Needed for z-index */
            font-size: 0.85rem; /* Slightly smaller text */
        }
        .step-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--cui-gray-300);
            color: var(--cui-gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: bold;
            border: 3px solid var(--cui-body-bg); /* Match background to overlay progress line */
            position: relative; /* Needed for z-index */
            z-index: 2; /* Above progress line */
        }
        .step-text {
            color: var(--cui-gray-600);
        }
        .step.active .step-circle {
            background-color: var(--cui-primary);
            color: white;
        }
        .step.active .step-text {
            font-weight: bold;
            color: var(--cui-body-color);
        }
        .step.completed .step-circle {
            background-color: var(--cui-success);
            color: white;
        }
        .step.completed .step-text {
             color: var(--cui-gray-700);
        }
        .progress-bar {
            background-color: var(--cui-primary);
            transition: width 0.4s ease;
        }
        .card-progress {
            padding: 1rem;
            background-color: var(--cui-card-cap-bg, rgba(0, 0, 21, 0.03));
            border-bottom: 1px solid var(--cui-card-border-color, rgba(0, 0, 21, 0.125));
        }
        .card-progress .progress {
            height: 10px;
        }
        /* Ensure table headers are sticky if needed */
        #selectedServices table thead th {
            position: sticky;
            top: 0;
            background: var(--cui-body-bg);
            z-index: 1;
        }
         /* Style for formatted currency spans */
        .formatted-value, #formattedValue {
            display: block; /* Ensure it takes its own line */
            margin-top: 0.25rem;
            font-size: 0.8em;
        }
        /* Ensure textareas in table are reasonably sized */
        #selectedServices textarea.item-name {
            font-size: 0.9em;
            padding: 0.3rem;
            min-height: 40px; /* Adjust as needed */
        }
         /* Style for file list items */
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem; /* Add space between elements */
        }
        .file-item .file-info {
            flex-grow: 1; /* Allow file info to take up space */
        }
        .file-item .file-name-display {
             font-style: italic;
             color: var(--cui-gray-600);
             font-size: 0.9em;
             margin-top: 0.25rem;
             word-break: break-all; /* Prevent long file names from overflowing */
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- DOM Element References ---
          
            const form = document.getElementById('poForm');
            const steps = document.querySelectorAll('.form-step');
            const progressSteps = document.querySelectorAll('.step');
            const progressBar = document.querySelector('#progressPO .progress-bar'); // More specific selector
            const cardProgressDiv = document.querySelector('.card-progress');
            const poClientSelect = document.getElementById('poClient');
            const poNoInput = document.getElementById('poNo');
            const poCurrencySelect = document.getElementById('poCurrency');
            const poValueInput = document.getElementById('poValue');
            const formattedValueSpan = document.getElementById('formattedValue');
            const poStartDateInput = document.getElementById('poStartDate');
            const poEndDateInput = document.getElementById('poEndDate');
            // const resetEndDateButton = document.getElementById('resetEndDate'); // Button removed, reference removed
            const poTermEditorElement = document.getElementById('poTerm');
            const servicesTableBody = document.querySelector('#servicesTable tbody');
            const serviceSearchInput = document.getElementById('serviceSearch');
            const selectedServicesTableBody = document.querySelector('#selectedServices tbody');
            const selectedItemsCountSpan = document.getElementById('selectedItemsCount');
            const poNumberDisplaySpan = document.getElementById('poNumberDisplay');
            const remainingBudgetSpan = document.getElementById('remainingBudget');
            const confirmationDetailsDiv = document.getElementById('confirmationDetails');
            const nextButtons = document.querySelectorAll('.next-step');
            const prevButtons = document.querySelectorAll('.prev-step');
            // Step 4: Files
            const addFileBtn = document.getElementById('addFileBtn');
            const fileListDiv = document.getElementById('fileList');
            let fileCounter = 0; // Counter for unique file input IDs

            // --- State Variables ---
            let currentStep = 1;
            let clientData = null; // To store fetched client details
            let allServices = []; // To store fetched services
            let totalBudget = 0;

            // --- Helper Functions ---

            /**
             * Formats a numeric value as currency based on the selected currency.
             * @param {number|string} value - The numeric value to format.
             * @param {string} currency - The currency code (e.g., 'IDR', 'USD').
             * @returns {string} - The formatted currency string.
             */
            function formatCurrency(value, currency) {
                const numericValue = parseFloat(String(value).replace(/[^\d.-]/g, '')) || 0;
                const formatter = new Intl.NumberFormat(currency === 'IDR' ? 'id-ID' : 'en-US', {
                    style: 'currency',
                    currency: currency,
                    minimumFractionDigits: currency === 'IDR' ? 0 : 2,
                    maximumFractionDigits: 2,
                });
                return formatter.format(numericValue);
            }

            /**
             * Updates the main progress bar and step indicators in the header.
             */
            function updateMainProgress() {
                 if (!progressBar) {
                     console.error("Progress bar element not found!");
                     return;
                 }
                const progressPercentage = ((currentStep - 1) / (progressSteps.length - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;

                progressSteps.forEach((stepEl, index) => {
                    const stepNumber = index + 1;
                    stepEl.classList.remove('active', 'completed');
                    if (stepNumber < currentStep) {
                        stepEl.classList.add('completed');
                    } else if (stepNumber === currentStep) {
                        stepEl.classList.add('active');
                    }
                });
            }

            /**
             * Updates the content of the secondary progress/summary header section.
             */
             function updateCardProgressHeader() {
                if (currentStep <= 1) {
                    cardProgressDiv.classList.add('d-none'); // Hide for step 1
                    return;
                }
                 if (currentStep === 5) {
                     cardProgressDiv.classList.add('d-none'); // Hide for step 5 (confirmation)
                     document.getElementById('progressPO').classList.remove('d-none'); // Ensure main progress is visible
                     return;
                 }

                cardProgressDiv.classList.remove('d-none'); // Show for steps 2, 3, 4
                 document.getElementById('progressPO').classList.add('d-none'); // Hide main progress bar when summary is shown

                const currentStepText = document.querySelector(`.step[data-step="${currentStep}"] .step-text`).textContent;
                const progressPercentage = ((currentStep - 1) / (steps.length - 1)) * 100;

                let clientName = clientData ? clientData.company_name : '<span class="text-muted">N/A</span>';
                let poNumber = poNoInput.value || '<span class="text-muted">N/A</span>';
                let poValueFormatted = formatCurrency(poValueInput.value, poCurrencySelect.value);
                let startDate = poStartDateInput.value || '<span class="text-muted">N/A</span>';
                let endDate = poEndDateInput.value || '<span class="text-muted">N/A</span>'; // End date is now required

                // Gather selected services summary only if needed (e.g., for step 4 header)
                let servicesSummaryHtml = '';
                if (currentStep >= 4) { // Show services summary in header for step 4
                    const selectedRows = selectedServicesTableBody.querySelectorAll('tr[data-service-id]');
                    const totalServices = selectedRows.length;
                    let totalAllocated = calculateAllocatedBudget();

                    servicesSummaryHtml = `
                        <tr><td colspan="2"><strong>Services Overview (${totalServices} items)</strong></td></tr>
                        <tr>
                            <td>Total Allocated:</td>
                            <td class="text-end"><strong>${formatCurrency(totalAllocated, poCurrencySelect.value)}</strong></td>
                        </tr>
                    `;
                     // Optional: Add a few item examples if needed, but keep header concise
                }


                let headerHTML = `
                    <div class="row align-items-center mb-2">
                        <div class="col-sm-6 mb-2 mb-sm-0">
                            <h6 class="mb-0">Step ${currentStep}: <span id="currentStepTextHeader">${currentStepText}</span></h6>
                        </div>
                        <div class="col-sm-6">
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: ${progressPercentage}%" aria-valuenow="${progressPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered mb-0" style="font-size: 0.9em;">
                        <tbody>
                            <tr>
                                <td style="width: 50%;"><strong>Client:</strong> ${clientName}</td>
                                <td style="width: 50%;"><strong>PO Number:</strong> ${poNumber}</td>
                            </tr>
                             ${currentStep >= 3 ? `
                            <tr>
                                <td><strong>PO Value:</strong> ${poValueFormatted}</td>
                                <td><strong>Currency:</strong> ${poCurrencySelect.value}</td>
                             </tr>
                             <tr>
                                <td><strong>Start Date:</strong> ${startDate}</td>
                                <td><strong>End Date:</strong> ${endDate}</td>
                            </tr>
                            ` : ''}
                            ${servicesSummaryHtml} {{-- Inject services summary for step 4 --}}
                        </tbody>
                    </table>
                `;
                cardProgressDiv.innerHTML = headerHTML;
            }


             /**
             * Validates the inputs for the specified step before proceeding.
             * @param {number} stepToValidate - The step number to validate (1-based).
             * @returns {boolean} - True if valid, false otherwise.
             */
             async function validateStep(stepToValidate) {
                let isValid = true;
              
                // Simple function to add/remove Bootstrap validation classes
                const setValidationState = (input, valid) => {
                    if (valid) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid'); // Optional: show valid state
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                };

                switch(stepToValidate) {
                    case 1:
                        const clientSelected = !!poClientSelect.value;
                        setValidationState(poClientSelect, clientSelected); // Assumes x-select2 handles styling or needs custom handling
                        if (!clientSelected) {
                            alert('Please select a client before proceeding.');
                            isValid = false;
                        }
                        break;

                    case 2:
                        // Check if PO number is empty
                        const poNo = poNoInput.value.trim();
                        if (!poNo) {
                            alert('PO Number is required');
                            setValidationState(poNoInput, false);
                            poNoInput.focus();
                            return false;
                        }

                        // Check PO number uniqueness via AJAX
                        const response = await fetch('/api/po/check-unique', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ poNo: poNo })
                        });
                        
                        if (!response.ok) {
                            console.error('Error checking PO uniqueness:', response.statusText);
                            alert('Error checking PO number uniqueness. Please try again.');
                            return false;
                        }
                        
                        const data = await response.json();
                        if (data.exists) {
                            alert('This PO Number is already registered');
                            setValidationState(poNoInput, false);
                            poNoInput.focus();
                            return false;
                        }

                        const requiredInputsStep2 = steps[1].querySelectorAll('[required]');
                        let firstInvalidInputStep2 = null;
                        requiredInputsStep2.forEach(input => {
                            const inputValid = !!input.value.trim();
                            setValidationState(input, inputValid);
                            if (!inputValid && !firstInvalidInputStep2) {
                                firstInvalidInputStep2 = input;
                                isValid = false;
                            }
                        });

                        if (!isValid) {
                             alert('Please fill in all required fields for PO Overview.');
                             if (firstInvalidInputStep2) firstInvalidInputStep2.focus();
                             return false; // Stop validation here
                        }

                        // Check PO Value
                        const poNumericValue = parseFloat(poValueInput.value.replace(/[^\d.-]/g, ''));
                        const poValueValid = !isNaN(poNumericValue) && poNumericValue > 0;
                        setValidationState(poValueInput, poValueValid);
                        if (!poValueValid) {
                            alert('PO Value must be a valid positive number.');
                            poValueInput.focus();
                            isValid = false;
                        }

                        // Check Dates
                        const startDate = poStartDateInput.value;
                        const endDate = poEndDateInput.value;
                        const startDateValid = !!startDate; // Required field check done above
                        const endDateValid = !!endDate;   // Required field check done above
                        let datesLogicValid = true;
                        if (startDateValid && endDateValid) {
                            if (endDate <= startDate) {
                                alert('End Date must be later than Start Date.');
                                setValidationState(poEndDateInput, false);
                                poEndDateInput.focus();
                                datesLogicValid = false;
                                isValid = false;
                            } else {
                                // If logic is okay, rely on required validation state
                                setValidationState(poEndDateInput, true);
                                setValidationState(poStartDateInput, true);
                            }
                        }
                        break; // End of case 2 validation

                    case 3:
                        const allocated = calculateAllocatedBudget();
                        const currency = poCurrencySelect.value;
                        const tolerance = 0.001; // Tolerance for float comparison

                        // Check item values
                        const itemValueInputs = selectedServicesTableBody.querySelectorAll('tr[data-service-id] .item-value'); // Target only actual item rows
                        let firstInvalidItemInput = null;
                        let itemsValid = true;

                         if (itemValueInputs.length === 0) { // Check if any items were added
                             alert('Please add at least one service/item to the PO.');
                             isValid = false;
                             itemsValid = false;
                             // Maybe focus the search input or add button?
                         } else {
                             itemValueInputs.forEach(input => {
                                 const itemValue = parseFloat(input.value.replace(/[^\d.-]/g, ''));
                                 const itemValid = !isNaN(itemValue) && itemValue > 0;
                                 setValidationState(input, itemValid);
                                 if (!itemValid && !firstInvalidItemInput) {
                                     firstInvalidItemInput = input;
                                     itemsValid = false;
                                     isValid = false;
                                 }
                             });
                         }


                        if (!itemsValid) {
                            alert('Please enter a valid value greater than 0 for all selected services.');
                            if (firstInvalidItemInput) firstInvalidItemInput.focus();
                            return false; // Stop validation here
                        }

                        // Check budget allocation only if item values are valid
                        const budgetMatch = Math.abs(allocated - totalBudget) <= tolerance;
                        if (!budgetMatch) {
                             alert(`Budget Allocation Error:\nThe total allocated value (${formatCurrency(allocated, currency)}) must exactly match the PO Value (${formatCurrency(totalBudget, currency)}).\nPlease adjust item values.`);
                             // Optionally highlight the remaining budget display
                             if(remainingBudgetSpan) remainingBudgetSpan.classList.add('is-invalid'); // Need corresponding CSS
                             isValid = false;
                        } else {
                             if(remainingBudgetSpan) remainingBudgetSpan.classList.remove('is-invalid');
                        }
                        break; // End of case 3 validation

                    case 4:
                        // File validation: Check if at least one file is added (optional)
                        const fileInputs = fileListDiv.querySelectorAll('input[type="file"]');
                        if (fileInputs.length === 0) {
                             // It's okay to have no files, maybe? Depends on requirements.
                             // If required:
                             // alert('Please add at least one supporting file.');
                             // isValid = false;
                        } else {
                            // Check if any added file input is empty (more strict)
                            let allFilesSelected = true;
                            fileInputs.forEach(input => {
                                if (input.files.length === 0) {
                                    setValidationState(input, false);
                                    allFilesSelected = false;
                                    isValid = false;
                                } else {
                                    setValidationState(input, true);
                                }
                            });
                            if (!allFilesSelected) {
                                alert('Please select a file for each added file entry.');
                            }
                        }
                        console.log("Validating Step 4 (Files)");
                        break;

                    case 5:
                        // No inputs on this step, validation usually involves re-checking previous steps if needed.
                        console.log("Validating Step 5 (Confirmation)");
                        break;

                    default:
                        console.warn(`Validation requested for unknown step: ${stepToValidate}`);
                        break;
                }

                return isValid;
            }


            /**
             * Shows the specified step and hides others, performing validation first when moving forward.
             * @param {number} stepToShow - The step number to display (1-based).
             */
            function showStep(stepToShow) {
                // --- Logic to execute *before* potentially moving to the next step ---
                if (stepToShow > currentStep) {
                    // Validate the step we are *leaving* before proceeding
                    if (!validateStep(currentStep)) {
                        return; // Stop if current step validation fails
                    }

                    // --- Actions based on the step we are *moving to* ---
                    if (currentStep === 1 && stepToShow === 2) { // Moving 1 -> 2
                        // Show loading/spinner if needed
                        fetchClientDetails(poClientSelect.value).then(success => {
                            // Hide loading/spinner
                            if (success) proceedToStep(stepToShow);
                        });
                        return; // Wait for fetch
                    }
                    else if (stepToShow === 3 && allServices.length === 0) { // Moving to 3 (first time)
                         // Show loading/spinner if needed
                         fetchServices().then(() => {
                             // Hide loading/spinner
                             proceedToStep(stepToShow);
                         });
                         return; // Wait for fetch
                    }
                    else if (stepToShow === 5) { // Moving to 5 (Confirmation)
                        updateConfirmDetails(); // Update summary before showing
                        // No async here, proceed directly below
                    }
                } else if (stepToShow < currentStep) {
                     // Moving backward: Remove validation states from the step we are entering
                     const stepEntering = steps[stepToShow - 1];
                     stepEntering.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                         el.classList.remove('is-invalid', 'is-valid');
                     });
                      if (stepToShow === 3 && remainingBudgetSpan) { // Reset budget highlight when going back to step 3
                           remainingBudgetSpan.classList.remove('is-invalid');
                           remainingBudgetSpan.style.color = ''; // Reset color
                      }
                }

                // If moving backward or no special async action needed for the next step forward, proceed directly
                proceedToStep(stepToShow);
            }


            /**
             * Handles the actual DOM manipulation and state update for changing steps.
             * @param {number} stepToShow - The step number to display (1-based).
             */
            function proceedToStep(stepToShow) {
                if (stepToShow < 1 || stepToShow > steps.length) return; // Boundary check

                steps.forEach(s => s.classList.add('d-none'));
                steps[stepToShow - 1].classList.remove('d-none');
                currentStep = stepToShow;

                updateMainProgress(); // Update top progress bar
                updateCardProgressHeader(); // Update summary header (or hide it)

                 // Update budget display specifically when entering step 3
                if (currentStep === 3) {
                    updateTotalBudget(); // Ensure total budget variable is current
                    updateRemainingBudgetDisplay(); // Update the display based on current items
                }

                // Scroll to top of the specific step container for better UX
                steps[stepToShow - 1].scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            /**
             * Fetches client details from the API.
             * @param {string} clientId - The ID of the client to fetch.
             * @returns {Promise<boolean>} - Promise resolving to true on success, false on failure.
             */
             async function fetchClientDetails(clientId) {
                if (!clientId) return false;
                // Add Loading State indicator if desired (e.g., disable form controls)
                console.log(`Fetching details for client ID: ${clientId}`);
                try {
                    const response = await fetch(`/api/clients/${clientId}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } // Common headers
                    });
                    if (!response.ok) {
                        let errorMsg = `Error ${response.status}: ${response.statusText}`;
                        try { const errorData = await response.json(); errorMsg = errorData.message || errorMsg; } catch (e) {}
                        throw new Error(errorMsg);
                    }
                    clientData = await response.json();
                    console.log("Client data received:", clientData);

                    // Pre-fill payment terms in CKEditor
                    if (window.CKEDITOR && window.CKEDITOR.instances && window.CKEDITOR.instances.poTerm) {
                        window.CKEDITOR.instances.poTerm.setData(clientData.payment_terms || '<p>No payment terms specified.</p>');
                    } else if (window.ckeditors && poTermEditorElement) { // Fallback for potential different setup
                         const editorInstance = window.ckeditors.get(poTermEditorElement);
                         if (editorInstance) editorInstance.setData(clientData.payment_terms || '<p>No payment terms specified.</p>');
                         else console.warn('CKEditor instance not found via ckeditors map.');
                    } else {
                         console.warn('CKEditor instance or setup not found.');
                    }
                    return true; // Indicate success
                } catch (error) {
                    console.error('Error fetching client details:', error);
                    alert(`Failed to load client details: ${error.message}.\nPlease check the API endpoint and network connection.`);
                    clientData = null; // Reset client data on error
                    // Reset editor on error
                     if (window.CKEDITOR && window.CKEDITOR.instances && window.CKEDITOR.instances.poTerm) {
                         window.CKEDITOR.instances.poTerm.setData('');
                     } else if (window.ckeditors && poTermEditorElement) {
                         const editorInstance = window.ckeditors.get(poTermEditorElement);
                         if (editorInstance) editorInstance.setData('');
                     }
                    return false; // Indicate failure
                } finally {
                    // Remove Loading State indicator
                }
            }

            /**
             * Fetches available services from the API/CSV endpoint.
             * @returns {Promise<void>}
             */
            async function fetchServices() {
                 servicesTableBody.innerHTML = '<tr><td colspan="3" class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> Loading services...</td></tr>';
                 console.log("Fetching services...");
                try {
                    // Ensure the URL is correct for your Laravel setup
                    const response = await fetch('/api/csv-data/Product Doxa', {
                         headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) {
                         let errorMsg = `Error ${response.status}: ${response.statusText}`;
                         try { const errorData = await response.json(); errorMsg = errorData.message || errorMsg; } catch (e) {}
                        throw new Error(errorMsg);
                    }
                    const data = await response.json();
                    if (!Array.isArray(data)) {
                        throw new Error('Invalid data format: Expected an array.');
                    }
                    allServices = data;
                     console.log(`Fetched ${allServices.length} services.`);
                    populateServicesTable(allServices);
                } catch (error) {
                    console.error('Error loading services:', error);
                    servicesTableBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Error loading services: ${error.message}.</td></tr>`;
                    allServices = []; // Reset on error
                }
            }

            /**
             * Populates the available services table.
             * @param {Array} services - Array of service objects {Category, Item, id?}.
             */
            function populateServicesTable(services) {
                servicesTableBody.innerHTML = ''; // Clear loading/error message
                if (!services || services.length === 0) {
                    servicesTableBody.innerHTML = '<tr class="no-data"><td colspan="3" class="text-center text-muted fst-italic">No services available.</td></tr>';
                    return;
                }
                services.forEach((service, index) => {
                    if (!service || typeof service !== 'object' || !service.Category || !service.Item) {
                        console.warn(`Service data at index ${index} is incomplete or invalid:`, service);
                        return;
                    }
                    // Use a unique ID from the data if available, otherwise generate one
                    const serviceId = service.id || `generated-${service.Category.replace(/\s+/g, '-')}-${service.Item.replace(/\s+/g, '-')}-${index}`;
                    const row = servicesTableBody.insertRow();
                    row.dataset.serviceId = serviceId;
                    row.innerHTML = `
                        <td>${service.Category}</td>
                        <td>${service.Item}</td>
                        <td><button type="button" class="btn btn-sm btn-primary add-service" title="Add ${service.Item}">+</button></td>
                    `;
                });
            }

             /**
             * Filters the available services table based on search input.
             */
            function filterServices() {
                const searchTerm = serviceSearchInput.value.toLowerCase().trim();
                const rows = servicesTableBody.querySelectorAll('tr');
                let visibleCount = 0;

                const existingNoResultsRow = servicesTableBody.querySelector('.no-results');
                if (existingNoResultsRow) existingNoResultsRow.remove();

                rows.forEach(row => {
                    if (row.classList.contains('no-results') || row.classList.contains('no-data')) return; // Skip special rows

                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchTerm);
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                 if (visibleCount === 0 && allServices.length > 0 && searchTerm !== '') {
                     const noResultsRow = servicesTableBody.insertRow();
                     noResultsRow.classList.add('no-results');
                     noResultsRow.innerHTML = `<td colspan="3" class="text-center text-muted">No matching services found for "${serviceSearchInput.value}".</td>`;
                 } else if (servicesTableBody.children.length === 0 && !servicesTableBody.querySelector('.no-data')) {
                      // Handle case where table might become empty after filtering if it started empty
                       servicesTableBody.innerHTML = '<tr class="no-data"><td colspan="3" class="text-center text-muted fst-italic">No services available.</td></tr>';
                 }
            }

            /**
             * Adds a selected service to the right-hand table.
             * @param {HTMLElement} addButton - The button element that was clicked.
             */
            function addServiceToSelection(addButton) {
                 const row = addButton.closest('tr');
                 if (!row || !row.dataset.serviceId) return;
                 const category = row.cells[0].textContent;
                 const item = row.cells[1].textContent;
                 const serviceId = row.dataset.serviceId;

                 if (selectedServicesTableBody.querySelector(`tr[data-service-id="${serviceId}"]`)) {
                      alert(`"${item}" is already added.`);
                      return;
                 }

                 const emptyRow = selectedServicesTableBody.querySelector('tr.no-data');
                 if (emptyRow) emptyRow.remove();

                 const newRow = selectedServicesTableBody.insertRow();
                 newRow.dataset.serviceId = serviceId;

                 const index = selectedServicesTableBody.querySelectorAll('tr[data-service-id]').length - 1;

                 newRow.innerHTML = `
                    <td>
                        <input type="hidden" name="services[${index}][id]" value="${serviceId}">
                        <textarea name="services[${index}][name]" class="form-control item-name" rows="2" required>${category} - ${item}</textarea>
                    </td>
                    <td>
                        <input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]+" name="services[${index}][value]" class="form-control item-value" required placeholder="0.00">
                         <small class="text-muted formatted-value"></small>
                         <div class="invalid-feedback">Please enter a positive value.</div> {{-- Feedback for validation --}}
                    </td>
                    <td>
                        <div class="form-check d-flex justify-content-center align-items-center" style="min-height: 40px;">
                            <input type="hidden" name="services[${index}][is_recurring]" value="0">
                            <input type="checkbox" name="services[${index}][is_recurring]" class="form-check-input is-recurring" value="1" id="recurring-${index}">
                            <label class="form-check-label visually-hidden" for="recurring-${index}">Is Recurring</label>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-service" title="Remove ${item}">
                            <i class="cil-trash"></i>
                        </button>
                    </td>
                 `;

                 updateSelectedItemsCount();
                 updateRemainingBudgetDisplay();

                 const newValueInput = newRow.querySelector('.item-value');
                 if (newValueInput) newValueInput.focus();
            }

            /**
             * Removes a service from the selected services table and re-indexes subsequent rows.
             * @param {HTMLElement} removeButton - The button element that was clicked.
             */
            function removeServiceFromSelection(removeButton) {
                const rowToRemove = removeButton.closest('tr');
                if (!rowToRemove) return;

                rowToRemove.remove();
                reindexSelectedServices();
                updateSelectedItemsCount();
                updateRemainingBudgetDisplay();

                if (selectedServicesTableBody.querySelectorAll('tr[data-service-id]').length === 0) {
                     selectedServicesTableBody.innerHTML = '<tr class="no-data"><td colspan="4" class="text-center text-muted fst-italic">No services added yet.</td></tr>';
                }
            }

            /**
             * Re-indexes the name attributes of inputs in the selected services table.
             */
            function reindexSelectedServices() {
                const rows = selectedServicesTableBody.querySelectorAll('tr[data-service-id]');
                rows.forEach((row, index) => {
                    const inputs = row.querySelectorAll('input[name^="services["], textarea[name^="services["]');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            const newName = name.replace(/\[\d+\]/, `[${index}]`);
                            input.setAttribute('name', newName);
                        }
                        if (input.matches('.is-recurring')) {
                            const newId = `recurring-${index}`;
                            input.setAttribute('id', newId);
                            const label = row.querySelector(`label[for^="recurring-"]`);
                            if (label) label.setAttribute('for', newId);
                        }
                    });
                });
            }


             /**
             * Updates the displayed count of selected services.
             */
            function updateSelectedItemsCount() {
                 const count = selectedServicesTableBody.querySelectorAll('tr[data-service-id]').length;
                selectedItemsCountSpan.textContent = count;
            }

            /**
             * Updates the total budget state variable based on the PO Value input.
             */
            function updateTotalBudget() {
                totalBudget = parseFloat(poValueInput.value.replace(/[^\d.-]/g, '')) || 0;
            }

             /**
             * Calculates the total value of currently selected services.
             * @returns {number} - The total allocated budget.
             */
            function calculateAllocatedBudget() {
                let allocated = 0;
                const valueInputs = selectedServicesTableBody.querySelectorAll('tr[data-service-id] .item-value'); // Only count actual items
                valueInputs.forEach(input => {
                    allocated += parseFloat(input.value.replace(/[^\d.-]/g, '')) || 0;
                });
                 // Round to 2 decimal places to minimize floating point issues
                 return Math.round(allocated * 100) / 100;
            }


            /**
             * Updates the 'Remaining Budget' display in Step 3.
             */
            function updateRemainingBudgetDisplay() {
                 if (!remainingBudgetSpan) return; // Safety check
                const allocated = calculateAllocatedBudget();
                const remaining = Math.round((totalBudget - allocated) * 100) / 100; // Round result
                const currency = poCurrencySelect.value;
                const tolerance = 0.001;

                remainingBudgetSpan.textContent = formatCurrency(remaining, currency);
                remainingBudgetSpan.classList.remove('text-danger', 'text-warning', 'text-success', 'fw-bold'); // Reset classes

                if (remaining < -tolerance) { // Over budget
                     remainingBudgetSpan.classList.add('text-danger', 'fw-bold');
                } else if (Math.abs(remaining) > tolerance) { // Under budget
                     remainingBudgetSpan.classList.add('text-warning');
                } else { // Exactly zero (within tolerance)
                    remainingBudgetSpan.classList.add('text-success', 'fw-bold');
                }
            }

            /**
             * Updates the formatted currency display below an input field.
             * @param {HTMLInputElement} inputElement - The input field.
             * @param {HTMLElement} displayElement - The element to show the formatted value.
             */
             function updateFormattedValueDisplay(inputElement, displayElement) {
                 if (!displayElement) return; // Safety check
                 const value = inputElement.value;
                 const currency = poCurrencySelect.value;
                 const formatted = formatCurrency(value, currency);

                 let displayValue = '';
                 if (displayElement.id === 'formattedValue') { // Main PO Value display
                      displayValue = value ? `Preview: ${formatted}` : '';
                 } else { // Item value display
                     const numericValue = parseFloat(String(value).replace(/[^\d.-]/g, '')) || 0;
                     displayValue = numericValue !== 0 ? formatted : ''; // Show only if non-zero
                 }
                  displayElement.textContent = displayValue;
             }

             /**
              * Updates the confirmation details in Step 5.
              */
             function updateConfirmDetails() {
                 // Gather all data from previous steps
                 const clientName = clientData ? clientData.company_name : '<span class="text-danger">N/A</span>';
                 const poNumber = poNoInput.value || '<span class="text-danger">N/A</span>';
                 const poCurrency = poCurrencySelect.value;
                 const poValue = poValueInput.value;
                 const poValueFormatted = formatCurrency(poValue, poCurrency);
                 const startDate = poStartDateInput.value || '<span class="text-danger">N/A</span>';
                 const endDate = poEndDateInput.value || '<span class="text-danger">N/A</span>'; // Now required
                 let paymentTerms = '<span class="text-muted">N/A</span>';
                 // Get CKEditor content safely
                 if (window.CKEDITOR && window.CKEDITOR.instances && window.CKEDITOR.instances.poTerm) {
                     paymentTerms = window.CKEDITOR.instances.poTerm.getData() || '<span class="text-muted">N/A</span>';
                 } else if (window.ckeditors && poTermEditorElement) {
                      const editorInstance = window.ckeditors.get(poTermEditorElement);
                      if (editorInstance) paymentTerms = editorInstance.getData() || '<span class="text-muted">N/A</span>';
                 }

                 // Gather selected services
                 let servicesSummary = '<p class="text-muted fst-italic">No services selected.</p>';
                 const selectedRows = selectedServicesTableBody.querySelectorAll('tr[data-service-id]');
                 if (selectedRows.length > 0) {
                     servicesSummary = `
                         <h5>Selected Services (${selectedRows.length}):</h5>
                         <div class="table-responsive">
                             <table class="table table-sm table-bordered">
                                 <thead class="table-light">
                                     <tr><th>Item Name</th><th>Value</th><th>Recurring</th></tr>
                                 </thead>
                                 <tbody>`;
                     let totalAllocated = 0;
                     selectedRows.forEach(row => {
                         const name = row.querySelector('.item-name').value || '<span class="text-danger">N/A</span>';
                         const valueInput = row.querySelector('.item-value');
                         const valueRaw = parseFloat(valueInput.value.replace(/[^\d.-]/g, '')) || 0;
                         const valueFormatted = formatCurrency(valueRaw, poCurrency);
                         const isRecurring = row.querySelector('.is-recurring').checked ? 'Yes' : 'No';
                         servicesSummary += `<tr><td>${name}</td><td class="text-end">${valueFormatted}</td><td class="text-center">${isRecurring}</td></tr>`;
                         totalAllocated += valueRaw;
                     });
                     // Round total allocated for display consistency
                     totalAllocated = Math.round(totalAllocated * 100) / 100;
                     servicesSummary += `
                                 </tbody>
                                 <tfoot class="table-light">
                                     <tr>
                                        <td class="text-end"><strong>Total Allocated:</strong></td>
                                        <td class="text-end"><strong>${formatCurrency(totalAllocated, poCurrency)}</strong></td>
                                        <td></td>
                                    </tr>
                                 </tfoot>
                             </table>
                         </div>`;
                 }

                 // Gather file info
                 let filesSummaryHtml = '<p class="text-muted fst-italic">No files added.</p>';
                 const fileItems = fileListDiv.querySelectorAll('.file-item-container'); // Use the container class
                 if (fileItems.length > 0) {
                     filesSummaryHtml = '<ul>';
                     fileItems.forEach(fileDiv => {
                         const fileInput = fileDiv.querySelector('input[type="file"]');
                         const fileName = fileInput && fileInput.files.length > 0 ? fileInput.files[0].name : '<span class="text-danger">No file selected</span>';
                         const fileNotes = fileDiv.querySelector('textarea[name="fileNotes[]"]').value || '<span class="text-muted">No notes</span>';
                         filesSummaryHtml += `<li><strong>${fileName}</strong><br><small><em>Notes:</em> ${fileNotes}</small></li>`;
                     });
                     filesSummaryHtml += '</ul>';
                 }


                 // Construct HTML for confirmation display
                 confirmationDetailsDiv.innerHTML = `
                     <h4>Review Purchase Order</h4>
                     <div class="card mb-3">
                         <div class="card-header">PO Overview</div>
                         <div class="card-body">
                             <dl class="row mb-0">
                                 <dt class="col-sm-3">Client:</dt><dd class="col-sm-9">${clientName}</dd>
                                 <dt class="col-sm-3">PO Number:</dt><dd class="col-sm-9">${poNumber}</dd>
                                 <dt class="col-sm-3">PO Value:</dt><dd class="col-sm-9">${poValueFormatted}</dd>
                                 <dt class="col-sm-3">Currency:</dt><dd class="col-sm-9">${poCurrency}</dd>
                                 <dt class="col-sm-3">Start Date:</dt><dd class="col-sm-9">${startDate}</dd>
                                 <dt class="col-sm-3">End Date:</dt><dd class="col-sm-9">${endDate}</dd>
                             </dl>
                         </div>
                     </div>
                     <div class="card mb-3">
                         <div class="card-header">Payment Terms</div>
                         <div class="card-body payment-terms-display" style="max-height: 200px; overflow-y: auto;">${paymentTerms}</div>
                     </div>
                      <div class="card mb-3">
                         <div class="card-header">Services</div>
                         <div class="card-body">
                            ${servicesSummary}
                         </div>
                     </div>
                      <div class="card mb-3">
                         <div class="card-header">Files</div>
                         <div class="card-body">
                            ${filesSummaryHtml}
                         </div>
                     </div>
                     <p class="fw-bold text-danger">Please review all details carefully before submitting.</p>
                 `;
             }


            // --- Event Listeners ---

            // Navigation Buttons
            document.querySelectorAll('.next-step').forEach(button => {
    button.addEventListener('click', async function() {
        const isValid = await validateStep(currentStep);
        if (!isValid) {
            return; // Stop if validation fails
        }
        
        if (currentStep < steps.length) {
            showStep(currentStep + 1);
        }
    });
});
            prevButtons.forEach(button => {
                button.addEventListener('click', () => showStep(currentStep - 1));
            });

            // Step 2: Input Formatting and Updates
            poNoInput.addEventListener('input', () => {
                poNumberDisplaySpan.textContent = poNoInput.value ? ` ${poNoInput.value}` : '';
                if (currentStep > 1) updateCardProgressHeader();
            });

            poValueInput.addEventListener('input', () => {
                 poValueInput.value = poValueInput.value.replace(/[^\d.]/g, '').replace(/(\..*)\./g, '$1');
                 updateFormattedValueDisplay(poValueInput, formattedValueSpan);
                 updateTotalBudget();
                 if (currentStep > 1) updateCardProgressHeader();
                 if (currentStep === 3) updateRemainingBudgetDisplay();
            });

            poCurrencySelect.addEventListener('change', () => {
                updateFormattedValueDisplay(poValueInput, formattedValueSpan);
                 if (currentStep >= 3) {
                    const itemValueInputs = selectedServicesTableBody.querySelectorAll('.item-value');
                    itemValueInputs.forEach(input => {
                        const display = input.closest('td').querySelector('.formatted-value');
                        if(display) updateFormattedValueDisplay(input, display);
                    });
                    updateRemainingBudgetDisplay();
                 }
                 if (currentStep > 1) updateCardProgressHeader();
            });

            // Removed listener for resetEndDateButton as it's removed from HTML

             // Update header on date changes too
             poStartDateInput.addEventListener('change', () => { if (currentStep > 1) updateCardProgressHeader(); });
             poEndDateInput.addEventListener('change', () => { if (currentStep > 1) updateCardProgressHeader(); });


            // Step 3: Services - Search and Selection
            serviceSearchInput.addEventListener('input', filterServices);

            servicesTableBody.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('add-service')) {
                    addServiceToSelection(e.target);
                }
            });

            selectedServicesTableBody.addEventListener('click', function(e) {
                if (e.target && (e.target.classList.contains('remove-service') || e.target.closest('.remove-service'))) {
                    const button = e.target.closest('.remove-service');
                    const itemName = button.title.replace('Remove ',''); // Get item name for confirmation
                    if (confirm(`Are you sure you want to remove "${itemName}"?`)) {
                        removeServiceFromSelection(button);
                    }
                }
            });

             selectedServicesTableBody.addEventListener('input', function(e) {
                 if (e.target && e.target.classList.contains('item-value')) {
                     const input = e.target;
                     let value = input.value.replace(/,/g, '.');
                     value = value.replace(/[^\d.]/g, '');
                     value = value.replace(/(\..*)\./g, '$1');
                     input.value = value;

                     const display = input.closest('td').querySelector('.formatted-value');
                     if(display) updateFormattedValueDisplay(input, display);
                     updateRemainingBudgetDisplay();
                 }
             });


            // STEP 4: Files - Add and Remove
            addFileBtn.addEventListener('click', function() {
                fileCounter++;
                const fileInputId = `poFile_${fileCounter}`;
                const noteId = `fileNote_${fileCounter}`;
                const fileDiv = document.createElement('div');
                // Added 'file-item-container' class for easier selection in confirmation step
                fileDiv.className = 'mb-3 p-3 border rounded file-item-container';
                fileDiv.innerHTML = `
                    <div class="file-item">
                        <div class="file-info flex-grow-1">
                             <label for="${fileInputId}" class="form-label visually-hidden">File ${fileCounter}</label> {{-- Hidden label for accessibility --}}
                            <input type="file" class="form-control form-control-sm" id="${fileInputId}" name="poFiles[]" required> {{-- Make file input required? --}}
                            <div class="file-name-display text-muted small mt-1"></div> {{-- Placeholder for selected file name --}}
                            <div class="invalid-feedback">Please select a file.</div>
                        </div>
                        <div class="file-notes ms-3" style="min-width: 200px;"> {{-- Notes section --}}
                            <label for="${noteId}" class="form-label small">Notes:</label>
                            <textarea class="form-control form-control-sm" id="${noteId}" name="fileNotes[]" rows="2"></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-file align-self-start" title="Remove this file entry">X</button>
                    </div>
                `;

                 // Remove the initial "No files added yet" message
                 const noFilesMessage = fileListDiv.querySelector('.no-files');
                 if (noFilesMessage) noFilesMessage.remove();

                fileListDiv.appendChild(fileDiv);

                // Add event listener for file selection to display name
                const fileInput = fileDiv.querySelector(`#${fileInputId}`);
                const fileNameDisplay = fileDiv.querySelector('.file-name-display');
                fileInput.addEventListener('change', function(event) {
                    if (event.target.files.length > 0) {
                        fileNameDisplay.textContent = `Selected: ${event.target.files[0].name}`;
                         fileInput.classList.remove('is-invalid'); // Remove validation error if file is selected
                         fileInput.classList.add('is-valid');
                    } else {
                        fileNameDisplay.textContent = ''; // Clear if deselected (though browsers might not allow this easily)
                         fileInput.classList.remove('is-valid');
                    }
                });

                // Add event listener for remove button
                fileDiv.querySelector('.remove-file').addEventListener('click', function() {
                    if(confirm('Are you sure you want to remove this file entry?')) {
                        fileDiv.remove();
                         // Add back the "No files" message if the list becomes empty
                         if (fileListDiv.children.length === 0) {
                              const p = document.createElement('p');
                              p.className = 'text-muted fst-italic no-files';
                              p.textContent = 'No files added yet.';
                              fileListDiv.appendChild(p);
                         }
                    }
                });
            });

             // Form Submission
            form.addEventListener('submit', function(e) {
                 // Re-validate all steps before final submission for robustness
                 let formValid = true;
                 for (let i = 1; i <= steps.length; i++) {
                     // Only validate steps up to the current one if needed,
                     // but validating all ensures nothing was broken by going back/forth.
                     // Skip validation for step 5 itself as it has no inputs.
                     if (i < 5 && !validateStep(i)) {
                         formValid = false;
                         // Navigate back to the first invalid step found
                         showStep(i);
                         alert(`Please correct the errors in Step ${i} before submitting.`);
                         break; // Stop validation loop
                     }
                 }

                 if (!formValid) {
                     e.preventDefault(); // Prevent submission
                     return;
                 }

                 // Final confirmation dialog
                 if (!confirm('Are you sure you want to register this Purchase Order with the reviewed details?')) {
                     e.preventDefault();
                     return;
                 }

                 // Disable submit button to prevent multiple submissions
                 const submitButton = form.querySelector('button[type="submit"]');
                 if (submitButton) {
                     submitButton.disabled = true;
                     submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
                 }

                console.log('Form is valid and submitting...');
                // Allow actual form submission to proceed
            });


            // --- Initialisation ---
            proceedToStep(1); // Show first step without validation
            updateFormattedValueDisplay(poValueInput, formattedValueSpan); // Initial format for PO Value
            // Services and Client details are fetched when needed
              // Check if there are validation errors
              @if($errors->any())
                // Find which step has errors and show it
                const errorFields = @json(array_keys($errors->toArray()));
                
                // Determine which step to show based on error fields
                let errorStep = 1;
                if (errorFields.includes('poNo') || errorFields.includes('poCurrency') || 
                    errorFields.includes('poValue') || errorFields.includes('poStartDate') || 
                    errorFields.includes('poEndDate') || errorFields.includes('poTerm')) {
                    errorStep = 2;
                } else if (errorFields.includes('services')) {
                    errorStep = 3;
                } else if (errorFields.includes('poFiles')) {
                    errorStep = 4;
                }
                
                // Show the step with errors
                currentStep = errorStep-1;
                showStep(errorStep);
            @endif    
        });
    </script>
    @endpush
</x-app-layout>