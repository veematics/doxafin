<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Menu') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Create New Menu</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('menu.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label" for="name">Menu Name</label>
                            <input class="form-control @error('name') is-invalid @enderror" 
                                   id="name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="type">Menu Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type"
                                    name="type"
                                    required>
                                <option value="">Select Type</option>
                                <option value="sidebar" {{ old('type') === 'sidebar' ? 'selected' : '' }}>
                                    Sidebar Menu
                                </option>
                                <option value="personal" {{ old('type') === 'personal' ? 'selected' : '' }}>
                                    Personal Menu
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('menu.index') }}" class="btn btn-secondary">
                                <i class="cil-x"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="cil-save"></i> Create Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>