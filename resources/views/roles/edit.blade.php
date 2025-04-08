<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Edit Role</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('appsetting.roles.update', $role) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="name">Role Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="display_name">Display Name</label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                       id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" required>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
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
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="{{ $feature->featureIcon ?? 'cil-settings' }} me-2"></i>
                                                {{ $feature->featureName }}
                                            </h5>
                                            <input type="hidden" name="permissions[{{ $feature->featureID }}][feature_id]" 
                                                   value="{{ $feature->featureID }}">
                                            
                                            @php
                                                $featurePermissions = $role->features->where('featureID', $feature->featureID)->first();
                                                $pivotData = $featurePermissions ? $featurePermissions->pivot : null;
                                            @endphp

                                            <div class="row g-3 mb-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">View Permission</label>
                                                    <div class="ms-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                   id="view_all_{{ $feature->featureID }}"
                                                                   value="1"
                                                                   {{ $pivotData && $pivotData->can_view == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_all_{{ $feature->featureID }}">
                                                                Global
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                   id="view_group_{{ $feature->featureID }}"
                                                                   value="2"
                                                                   {{ $pivotData && $pivotData->can_view == 2 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_group_{{ $feature->featureID }}">
                                                                Group
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                   id="view_own_{{ $feature->featureID }}"
                                                                   value="3"
                                                                   {{ $pivotData && $pivotData->can_view == 3 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_own_{{ $feature->featureID }}">
                                                                Own
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="permissions[{{ $feature->featureID }}][can_view]" 
                                                                   id="view_none_{{ $feature->featureID }}"
                                                                   value="4"
                                                                   {{ $pivotData && $pivotData->can_view == 4 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="view_none_{{ $feature->featureID }}">
                                                                No View
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                              
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[{{ $feature->featureID }}][can_create]" 
                                                               id="create_{{ $feature->featureID }}"
                                                               {{ $pivotData && $pivotData->can_create ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="create_{{ $feature->featureID }}">
                                                            Can Create
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[{{ $feature->featureID }}][can_edit]" 
                                                               id="edit_{{ $feature->featureID }}"
                                                               {{ $pivotData && $pivotData->can_edit ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="edit_{{ $feature->featureID }}">
                                                            Can Edit
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[{{ $feature->featureID }}][can_approve]" 
                                                               id="approve_{{ $feature->featureID }}"
                                                               {{ $pivotData && $pivotData->can_approve ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="approve_{{ $feature->featureID }}">
                                                            Can Approve
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="permissions[{{ $feature->featureID }}][can_delete]" 
                                                               id="delete_{{ $feature->featureID }}"
                                                               {{ $pivotData && $pivotData->can_delete ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="delete_{{ $feature->featureID }}">
                                                            Can Delete
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($feature->custom_permission)
                                                <div class="mt-3">
                                                    <h6 class="mb-3 text-primary">Additional Permissions</h6>
                                                    @foreach(explode("\n", $feature->custom_permission) as $permission)
                                                        @php
                                                            $parts = explode(':', $permission);
                                                            $permissionName = trim($parts[0]);
                                                            $options = isset($parts[1]) ? array_map('trim', explode(',', $parts[1])) : [];
                                                            $currentValue = $featurePermissions && isset($featurePermissions->pivot->additional_permissions) 
                                                                ? json_decode($featurePermissions->pivot->additional_permissions, true) 
                                                                : [];
                                                        @endphp
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">{{ $permissionName }}</label>
                                                            <div class="ms-3 d-flex gap-4">
                                                                @foreach($options as $option)
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                               name="permissions[{{ $feature->featureID }}][special][{{ Str::slug($permissionName) }}]"
                                                                               id="{{ Str::slug($feature->featureID . '_' . $permissionName . '_' . $option) }}"
                                                                               value="{{ $option }}"
                                                                               {{ isset($currentValue[Str::slug($permissionName)]) && $currentValue[Str::slug($permissionName)] == $option ? 'checked' : '' }}>
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
                                    <i class="cil-save me-1"></i> Update Role
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>