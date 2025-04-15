@props(['id', 'name', 'value' => '', 'label' => null, 'required' => false, 'height' => '200px'])

@if($label)
<label for="{{ $id }}" class="form-label">
    {{ $label }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
@endif

<div class="ckeditor-container" style="height: {{ $height }}; max-height: 600px; overflow-y: auto;">
    <textarea id="{{ $id }}" name="{{ $name }}" class="ckeditor form-control @error($name) is-invalid @enderror">{!! $value !!}</textarea>
</div>

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

@once
    @push('headerscripts')
        @vite(['resources/js/ckeditor-init.js'])
    @endpush
@endonce
