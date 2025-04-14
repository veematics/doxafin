<div class="form-group">
    @if($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>

<!--
<x-csv-select 
    label="Select Data"
    :options="$options"
    :selected="$defaultValue"
    name="csv_field"
    class="custom-class"
    required
/>

!-->
