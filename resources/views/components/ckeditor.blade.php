@props(['id', 'name', 'value' => '', 'label' => null, 'required' => false, 'height' => '200px'])

@if($label)
<label for="{{ $id }}" class="form-label">
    {{ $label }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
@endif

<div class="ckeditor-container" style="height: {{ $height }};">
    <textarea id="{{ $id }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">{!! $value !!}</textarea>
</div>

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

@once
    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ClassicEditor
            .create(document.getElementById('{{ $id }}'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('height', '{{ $height }}', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush