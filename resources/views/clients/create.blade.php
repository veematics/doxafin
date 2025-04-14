<x-app-layout>
    
    <div class="row align-items-center mb-4">
        <div class="col">
            <div class="fs-2 fw-semibold">{{ __('Create Client') }}</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ __('Client Information') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="company_name">Company Legal Name</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                            id="company_name" name="company_name" value="{{ old('company_name') }}" 
                             required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="company_alias">Company Alias (Short Name)</label>
                        <input type="text" class="form-control @error('company_alias') is-invalid @enderror" 
                            id="company_alias" name="company_alias" value="{{ old('company_alias') }}">
                        @error('company_alias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="company_code">Company Code</label>
                        <input type="text" class="form-control @error('company_code') is-invalid @enderror" 
                            id="company_code" name="company_code" value="{{ old('company_code') }}" 
                            maxlength="4" style="text-transform: uppercase;">
                        @error('company_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="company_address">{{ __('Address') }}</label>
                        <textarea class="form-control @error('company_address') is-invalid @enderror" 
                            id="company_address" name="company_address" rows="3">{{ old('company_address') }}</textarea>
                        @error('company_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="npwp">{{ __('NPWP') }}</label>
                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" 
                            id="npwp" name="npwp" value="{{ old('npwp') }}">
                        @error('npwp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="website">{{ __('Website') }}</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                            id="website" name="website" value="{{ old('website') }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="assign_to">{{ __('Assign To') }}</label>
                        <select class="form-select @error('assign_to') is-invalid @enderror" 
                            id="assign_to" name="assign_to">
                            <option value="">{{ __('Select User') }}</option>
                            @php
                                $featureId = App\Models\AppFeature::where('featureName', 'Clients')->value('featureID');
                                $permissions = Cache::get('user_permissions_' . auth()->id());
                                $canView = $permissions[$featureId]->first()->can_view;
                                
                                $userQuery = App\Models\User::query();
                                
                                if ($canView == 3) {
                                    $userQuery->where('users.id', auth()->id());
                                } elseif ($canView == 2) {
                                    $userRoleIds = auth()->user()
                                        ->roles()
                                        ->select('roles.id as role_id')
                                        ->pluck('role_id');
                                    $userQuery->whereHas('roles', function($query) use ($userRoleIds) {
                                        $query->whereIn('roles.id', $userRoleIds);
                                    });
                                }
                                
                                $users = $userQuery->get();
                            @endphp
                            
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ (old('assign_to', auth()->id()) == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assign_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-5">
                        <x-ckeditor 
                            id="payment_terms"
                            name="payment_terms"
                            height="300px"
                            label="{{ __('Payment Terms') }}"
                            :value="old('payment_terms')"
                        />
                    </div>

                    <div class="col-12 mb-5 ">
                        <x-ckeditor 
                            id="notes"
                            name="notes"
                            height="200px"
                            label="{{ __('Notes') }}"
                            :value="old('notes')"
                        />
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('clients.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Create Client') }}</button>
                </div>
            </form>
        </div>
    </div>
    
</x-app-layout>