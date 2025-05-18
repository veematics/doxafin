<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Media Viewer</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="preview-container border rounded p-3 mb-3" style="min-height: 400px;">
                            @php
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'svg']);
                                $isPdf = strtolower($fileExtension) === 'pdf';
                            @endphp

                            @if($isImage)
                                <img src="{{ route('media.show', $filePath) }}" alt="File Preview" class="img-fluid">
                            @elseif($isPdf)
                                <iframe src="{{ route('media.show', $filePath) }}" width="100%" height="600px" class="border-0"></iframe>
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <i class="fas fa-file fa-4x text-secondary mb-3"></i>
                                        <p>Preview not available for this file type</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="file-info border rounded p-3">
                            <h5 class="mb-3">File Information</h5>
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Filename:</dt>
                                <dd class="col-sm-8">{{ basename($filePath) }}</dd>

                                <dt class="col-sm-4">Type:</dt>
                                <dd class="col-sm-8">{{ strtoupper($fileExtension) }}</dd>

                                @if(isset($fileSize))
                                    <dt class="col-sm-4">Size:</dt>
                                    <dd class="col-sm-8">{{ $fileSize }}</dd>
                                @endif

                                @if(isset($uploadDate))
                                    <dt class="col-sm-4">Uploaded:</dt>
                                    <dd class="col-sm-8">{{ $uploadDate }}</dd>
                                @endif
                            </dl>

                            <div class="mt-4">
                                <a href="{{ route('media.download', $filePath) }}" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                                @if(isset($backUrl))
                                    <a href="{{ $backUrl }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>