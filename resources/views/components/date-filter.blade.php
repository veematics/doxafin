<div class="date-filter">
    <button type="button" class="btn btn-outline-secondary" data-coreui-toggle="modal" data-coreui-target="#dateFilterModal">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
        </svg>
        <span id="selectedDateRange">{{ now()->startOfMonth()->format('M d, Y') }} - {{ now()->endOfMonth()->format('M d, Y') }}</span>
    </button>

    <div class="modal fade" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateFilterModalLabel">Date Filter</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" id="dateRangePreset">
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_quarter">This Quarter</option>
                                <option value="last_quarter">Last Quarter</option>
                                <option value="this_year">This Year</option>
                                <option value="last_year">Last Year</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="row" id="customDateInputs">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">From</label>
                                <input type="text" class="form-control" id="dateFrom">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">To</label>
                                <input type="text" class="form-control" id="dateTo">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="applyDateFilter">Apply</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update modal initialization to use CoreUI
    const modalEl = document.getElementById('dateFilterModal');
    const modal = new coreui.Modal(modalEl);
    
    const dateFromPicker = flatpickr("#dateFrom", {
        dateFormat: "d-m-Y",
        defaultDate: new Date()
    });
    const dateToPicker = flatpickr("#dateTo", {
        dateFormat: "d-m-Y",
        defaultDate: new Date()
    });

    const dateRangePreset = document.getElementById('dateRangePreset');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const selectedDateRange = document.getElementById('selectedDateRange');
    
    dateRangePreset.addEventListener('change', function() {
        const isCustom = this.value === 'custom';
        dateFromPicker.set('disabled', !isCustom);
        dateToPicker.set('disabled', !isCustom);
        
        if (!isCustom) {
            updatePresetDates(this.value);
        }
    });

    document.getElementById('applyDateFilter').addEventListener('click', function() {
        const fromDate = dateFromPicker.selectedDates[0];
        const toDate = dateToPicker.selectedDates[0];
        selectedDateRange.textContent = `${fromDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} - ${toDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
        modal.hide();
        // Trigger your date filter event here
    });

    function updatePresetDates(preset) {
        const now = new Date();
        let fromDate, toDate;

        switch(preset) {
            case 'this_month':
                fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
                toDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                break;
            case 'last_month':
                fromDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                toDate = new Date(now.getFullYear(), now.getMonth(), 0);
                break;
            case 'this_quarter':
                const quarter = Math.floor(now.getMonth() / 3);
                fromDate = new Date(now.getFullYear(), quarter * 3, 1);
                toDate = new Date(now.getFullYear(), (quarter + 1) * 3, 0);
                break;
            case 'last_quarter':
                const lastQuarter = Math.floor(now.getMonth() / 3) - 1;
                const lastQuarterYear = lastQuarter < 0 ? now.getFullYear() - 1 : now.getFullYear();
                const adjustedQuarter = lastQuarter < 0 ? 3 : lastQuarter;
                fromDate = new Date(lastQuarterYear, adjustedQuarter * 3, 1);
                toDate = new Date(lastQuarterYear, (adjustedQuarter + 1) * 3, 0);
                break;
            case 'this_year':
                fromDate = new Date(now.getFullYear(), 0, 1);
                toDate = new Date(now.getFullYear(), 11, 31);
                break;
            case 'last_year':
                fromDate = new Date(now.getFullYear() - 1, 0, 1);
                toDate = new Date(now.getFullYear() - 1, 11, 31);
                break;
        }

        if (fromDate && toDate) {
            dateFromPicker.setDate(fromDate);
            dateToPicker.setDate(toDate);
        }
    }

    // Initialize with This Month
    updatePresetDates('this_month');
});
</script>
@endpush