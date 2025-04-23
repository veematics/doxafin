<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Contact') }}
        </h2>
    </x-slot>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Create New Contact</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('clients.contacts.store-independent') }}">
                        @csrf
                        <!-- Row 1: Client List -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="client_id">Client</label>
                                <x-select2 
                                    id="client_id" 
                                    name="client_id" 
                                    :options="$clients->pluck('company_name', 'id')->toArray()" 
                                    placeholder="Select a client" 
                                    required 
                                />
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2: Salutation, Name, Position -->
                        <div class="row mb-3">
                            <div class="col-2">
                                <label class="form-label" for="salutation">Salutation</label>
                                <select class="form-select @error('salutation') is-invalid @enderror" id="salutation" name="salutation">
                                    <option value="">Select</option>
                                    <option value="Mr." {{ old('salutation') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Mrs." {{ old('salutation') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    <option value="Ms." {{ old('salutation') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                    <option value="Dr." {{ old('salutation') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                </select>
                                @error('salutation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-7">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" 
                                       type="text" id="name" name="name" 
                                       value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-3">
                                <label class="form-label" for="role">Position</label>
                                <input class="form-control @error('role') is-invalid @enderror" 
                                       type="text" id="role" name="role" 
                                       value="{{ old('role') }}">
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 3: Email and Phone -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" 
                                       type="email" id="email" name="email" 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="phone_number">Phone</label>
                                <input class="form-control @error('phone_number') is-invalid @enderror" 
                                       type="text" id="phone_number" name="phone_number" 
                                       value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 4: Primary Contact Checkbox -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_primary" name="is_primary" value="1" 
                                           {{ old('is_primary') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_primary">
                                        Set as primary contact
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Create Contact</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>