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
            'class' => 'select2 form-select ' . $class,
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
@endonce