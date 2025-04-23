@props([
    'id' => null,
    'name' => null,
    'class' => '',
    'placeholder' => 'Select an option',
    'options' => [],
    'selected' => null
])

<div>
    <select 
        {{ $attributes->merge([
            'class' => 'select2 form-select select2-dark ' . $class,
            'id' => $id,
            'name' => $name,
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

@once
    @push('headerscripts')
        @vite(['resources/js/select2.js'])
    @endpush
    @push('scripts')
    <style>
        .select2-dark + .select2-container--default .select2-selection--single,
        .select2-dark + .select2-container--default .select2-selection--multiple {
            background-color: var(--cui-input-bg);
            border-color: var(--cui-input-border-color);
            color: var(--cui-input-color);
        }
    
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--cui-dropdown-link-active-bg);
            color: var(--cui-dropdown-link-active-color);
        }
    
        .select2-dropdown {
            background-color: var(--cui-body-bg);
            border-color: var(--cui-dropdown-border-color);
            box-shadow: var(--cui-dropdown-box-shadow);
        }
    
        .select2-container--default .select2-results__option {
            color: var(--cui-body-color);
            padding: 0.5rem 1rem;
        }
    
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--cui-primary);
            color: var(--cui-primary-color);
        }
    
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--cui-primary-lighter);
            color: var(--cui-primary-color);
        }
    
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--cui-primary);
            color: var(--cui-light);
        }
    
        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: var(--cui-input-bg);
            border-color: var(--cui-input-border-color);
            color: var(--cui-input-color);
        }
    
        .select2-container--default .select2-results__option {
            color: var(--cui-dropdown-link-color);
        }
    
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--cui-input-color);
        }
    
        .select2-container--default .select2-selection--single {
            background-color: var(--cui-input-bg);
            color: var(--cui-input-color);
        }
        </style>
    @endpush
    
 
@endonce