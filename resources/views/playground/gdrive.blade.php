<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Google Drive Integration
        </h2>
    </x-slot>

    {{-- Display Success/Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>Upload File</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('playground.gdrive.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose File</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="folder" class="form-label">Target Subfolder</label>
                            <input type="hidden" name="parent_folder_id" value="{{ $currentPathId ?? '' }}">
                            <input type="text" class="form-control" 
                                   value="{{ $currentPath ?? 'Root Folder' }}" 
                                   readonly>
                            @error('folder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg class="icon me-2" width="16" height="16"> <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-cloud-upload"></use>
                    </svg>
                    Upload to Drive
                </button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Files in Drive (Current Path: {{ $currentPath ?? '/' }})</h4>
                {{-- Basic Folder Navigation --}}
                @if(isset($parentPathId) && $currentPathId !== $rootFolderId)
                    <a href="{{ route('playground.gdrive.index', [
                        'path_id' => $parentPathId,
                        'path_label' => $parentPathLabel ?? '/',
                        'parent_id' => $parentParentId ?? ''
                    ]) }}" class="btn btn-sm btn-outline-secondary">
                        <svg class="icon me-1" width="16" height="16"><use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-arrow-circle-top"></use></svg> Up
                    </a>
                @endif
                {{-- Search functionality is not implemented in this version --}}
                {{-- <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Search files..." id="searchFiles">
                    <button class="btn btn-outline-secondary" type="button">
                        <svg class="icon" width="16" height="16">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-search"></use>
                        </svg>
                    </button>
                </div> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="filesList">
                        @forelse($files ?? [] as $item)
                            <tr>
                                <td>
                                    @if($item->type === 'dir')
                                        <svg class="icon me-2" width="16" height="16"><use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-folder"></use></svg>
                                        <a href="{{ route('playground.gdrive.index', [
                                            'path_id' => $item->path_id, 
                                            'path_label' => ($currentPath ?? '/') . ($currentPath !== '/' ? '/' : '') . $item->name,
                                            'parent_id' => $currentPathId ?? ''
                                        ]) }}">{{ $item->name }}</a>
                                    @else
                                        <svg class="icon me-2" width="16" height="16"><use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-file"></use></svg>
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td></td>
                                <td>{{ $item->size }}</td>
                                <td>
                                    @if($item->modifiedTime !== 'N/A')
                                        {{ \Carbon\Carbon::parse($item->modifiedTime)->format('M d, Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($item->type === 'file')
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('playground.gdrive.download', ['fileId' => $item->path_id]) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                            <svg class="icon" width="16" height="16">
                                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-cloud-download"></use>
                                            </svg>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteFile('{{ $item->path_id }}', '{{ $item->name }}')">
                                            <svg class="icon" width="16" height="16">
                                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-trash"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    @elseif($item->type === 'dir')
                                     <button type="button" class="btn btn-sm btn-outline-danger" title="Delete Folder (Caution!)" onclick="deleteFile('{{ $item->path_id }}', '{{ $item->name }}', true)">
                                        <svg class="icon" width="16" height="16">
                                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-trash"></use>
                                        </svg>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No files or folders found in this directory.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Hidden form for delete requests --}}
    <form id="deleteFileForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deleteFile(encodedPath, fileName, isDir) {
            if (confirm(`Are you sure you want to delete ${isDir ? 'the folder' : 'the file'} "${fileName}"?`)) {
                const form = document.getElementById('deleteFileForm');
                form.action = "{{ url('playground/gdrive/delete') }}/" + encodedPath;
                form.submit();
            }
        }
    </script>
</x-app-layout>