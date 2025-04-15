<x-app-layout>

    <a href="{{ route('playground.index') }}" class="btn btn-primary mb-4"><< Back to Index</a>
    
    <div class="container-lg">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">CKEditor Demo</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <x-ckeditor
                        id="editor"
                        name="content"
                        label="Test Editor"
                        value="<p>This is a demo content for CKEditor</p>"
                        height="250px"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="container-lg">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">CKEditor Demo</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <x-ckeditor
                        id="editor2"
                        name="content2"
                        label="Test Editor2"
                        value="<p>This is a demo content for CKEditor</p>"
                        height="500px"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>