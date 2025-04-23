@props(['id', 'name', 'value' => '', 'label' => null, 'required' => false, 'height' => '200px'])

<style>
    #{{ $id }}-container .ck.ck-editor__editable_inline,
    #{{ $id }}-container .ck.ck-editor__editable {
        min-height: {{ $height }} !important;
        overflow-y: auto !important;
    }
    
    #{{ $id }}-container .ck.ck-content {
        min-height: {{ $height }} !important;
    }
</style>

@if($label)
<label for="{{ $id }}" class="form-label">
    {{ $label }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
@endif

<div id="{{ $id }}-container" class="ckeditor-container">
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


