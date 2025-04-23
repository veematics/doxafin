@props(['id' => null, 'name' => null, 'required' => false, 'placeholder' => 'Select date'])

<div class="singledate-filter" data-wrap="true">
    <div class="input-group">
        <input type="text" 
               id="{{ $id ?? uniqid('date_') }}" 
               name="{{ $name ?? $id }}" 
               class="form-control singledate-picker" 
               placeholder="{{ $placeholder }}"
               {{ $required ? 'required' : '' }}
               data-input
               autocomplete="off">
        <span class="input-group-text" data-toggle>
            <svg class="icon">
                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
            </svg>
        </span>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('.singledate-picker');
    dateInputs.forEach(input => {
        flatpickr(input.closest('.singledate-filter'), {
            dateFormat: "d-m-Y",
            allowInput: true,
            wrap: true
        });
    });
});
</script>
@endpush