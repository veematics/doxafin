<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="page-title-box">
                        <h4 class="mb-0">Create New Role</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('appsetting.roles.index') }}">Roles</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>Role Details</strong>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('appsetting.roles.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="name">Role Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="display_name">Display Name</label>
                                        <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                               id="display_name" name="display_name" required>
                                        @error('display_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header">
                                        <strong>Feature Permissions</strong>
                                    </div>
                                    <div class="card-body">
                                        @foreach($features as $feature)
                                            <div class="row mb-4 pb-4 border-bottom">
                                                <div class="col-12 mb-3">
                                                    <h5>
                                                        <i class="{{ $feature->featureIcon ?? 'cil-settings' }} me-2"></i>
                                                        {{ $feature->featureName }}
                                                    </h5>
                                                </div>
                                                
                                                <input type="hidden" name="permissions[{{ $feature->featureID }}][feature_id]" 
                                                       value="{{ $feature->featureID }}">
                                                
                                                <div class="col-md-4">
                                                    <div class="card h-100">
                                                        <div class="card-header">
                                                            <strong>View Permission</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                       id="view_all_{{ $feature->featureID }}"
                                                                       value="1" checked>
                                                                <label class="form-check-label" for="view_all_{{ $feature->featureID }}">
                                                                     Global
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                       id="view_group_{{ $feature->featureID }}"
                                                                       value="2">
                                                                <label class="form-check-label" for="view_group_{{ $feature->featureID }}">
                                                                     Group
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                       id="view_own_{{ $feature->featureID }}"
                                                                       value="3">
                                                                <label class="form-check-label" for="view_own_{{ $feature->featureID }}">
                                                                     Own
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" 
                                                                       name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                       id="view_none_{{ $feature->featureID }}"
                                                                       value="0">
                                                                <label class="form-check-label" for="view_none_{{ $feature->featureID }}">
                                                                     No View
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card h-100">
                                                        <div class="card-header">
                                                            <strong>Other Permissions</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="permissions[{{ $feature->featureID }}][can_create]" 
                                                                       id="create_{{ $feature->featureID }}">
                                                                <label class="form-check-label" for="create_{{ $feature->featureID }}">
                                                                    Can Create
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="permissions[{{ $feature->featureID }}][can_edit]" 
                                                                       id="edit_{{ $feature->featureID }}">
                                                                <label class="form-check-label" for="edit_{{ $feature->featureID }}">
                                                                    Can Edit
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="permissions[{{ $feature->featureID }}][can_delete]" 
                                                                       id="delete_{{ $feature->featureID }}">
                                                                <label class="form-check-label" for="delete_{{ $feature->featureID }}">
                                                                    Can Delete
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="permissions[{{ $feature->featureID }}][can_approve]" 
                                                                       id="approve_{{ $feature->featureID }}">
                                                                <label class="form-check-label" for="approve_{{ $feature->featureID }}">
                                                                    Can Approve
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    @if($feature->custom_permission)
                                                        <div class="card h-100">
                                                            <div class="card-header">
                                                                <strong>Additional Permissions</strong>
                                                            </div>
                                                            <div class="card-body">
                                                                @foreach(explode("\n", $feature->custom_permission) as $permission)
                                                                    @php
                                                                        $parts = explode(':', $permission);
                                                                        $permissionName = trim($parts[0]);
                                                                        $options = isset($parts[1]) ? array_map('trim', explode(',', $parts[1])) : [];
                                                                    @endphp
                                                                    
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">{{ $permissionName }}</label>
                                                                        <div class="ms-3">
                                                                            @foreach($options as $option)
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio"
                                                                                           name="permissions[{{ $feature->featureID }}][special][{{ Str::slug($permissionName) }}]"
                                                                                           id="{{ Str::slug($feature->featureID . '_' . $permissionName . '_' . $option) }}"
                                                                                           value="{{ $option }}">
                                                                                    <label class="form-check-label" 
                                                                                           for="{{ Str::slug($feature->featureID . '_' . $permissionName . '_' . $option) }}">
                                                                                        {{ $option }}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <a href="{{ route('appsetting.roles.index') }}" class="btn btn-light me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="cil-save me-1"></i> Create Role
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>