<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col">
            <div class="fs-2 fw-semibold">{{ __('Edit Client') }}</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ __('Client Information') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="company_name">Company Legal Name</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                            id="company_name" name="company_name" value="{{ old('company_name', $client->company_name) }}" 
                            required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="company_alias">Company Alias (shortname)</label>
                        <input type="text" class="form-control @error('company_alias') is-invalid @enderror" 
                            id="company_alias" name="company_alias" value="{{ old('company_alias', $client->company_alias) }}">
                        @error('company_alias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="company_code">{{ __('Company Code') }}</label>
                        <input type="text" class="form-control @error('company_code') is-invalid @enderror" 
                            id="company_code" name="company_code" value="{{ old('company_code', $client->company_code) }}" 
                            maxlength="4" style="text-transform: uppercase;" readonly>
                        @error('company_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="company_address">{{ __('Address') }}</label>
                        <textarea class="form-control @error('company_address') is-invalid @enderror" 
                            id="company_address" name="company_address" rows="3">{{ old('company_address', $client->company_address) }}</textarea>
                        @error('company_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="npwp">{{ __('NPWP') }}</label>
                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" 
                            id="npwp" name="npwp" value="{{ old('npwp', $client->npwp) }}">
                        @error('npwp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="website">{{ __('Website') }}</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                            id="website" name="website" value="{{ old('website', $client->website) }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="assign_to">{{ __('Assign To') }}</label>
                        <select class="form-select @error('assign_to') is-invalid @enderror" 
                            id="assign_to" name="assign_to">
                            <option value="">{{ __('Select User') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ (old('assign_to', $client->assign_to) == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assign_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="notes">{{ __('Notes') }}</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                            id="notes" name="notes" rows="3">{{ old('notes', $client->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('clients.show', $client) }}" class="btn btn-light">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Update Client') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>