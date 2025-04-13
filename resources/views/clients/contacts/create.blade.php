<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Add New Contact') }}</h6>
            <a href="{{ route('clients.show', $client) }}" class="btn btn-primary btn-sm">
                <i class="cil-arrow-left"></i> {{ __('Back to Client') }}
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ __('Contact Information') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('clients.contacts.store', $client) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="salutation">{{ __('Salutation') }}</label>
                        <select class="form-select @error('salutation') is-invalid @enderror" id="salutation" name="salutation">
                            <option value="">{{ __('Select Salutation') }}</option>
                            <option value="Mr" {{ old('salutation') == 'Mr' ? 'selected' : '' }}>Mr</option>
                            <option value="Mrs" {{ old('salutation') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                            <option value="Ms" {{ old('salutation') == 'Ms' ? 'selected' : '' }}>Ms</option>
                            <option value="Dr" {{ old('salutation') == 'Dr' ? 'selected' : '' }}>Dr</option>
                        </select>
                        @error('salutation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name">{{ __('Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="phone_number">{{ __('Phone') }}</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                            id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="role">{{ __('Position') }}</label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror" 
                            id="role" name="role" value="{{ old('role') }}">
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="is_primary" name="is_primary" value="1">
                            <label class="form-check-label" for="is_primary">{{ __('Set as primary contact') }}</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('clients.show', $client) }}" class="btn btn-light">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Save Contact') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>