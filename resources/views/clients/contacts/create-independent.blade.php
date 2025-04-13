<x-app-layout>

        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Add New Contact') }}</h6>
            <a href="{{ route('clients.index') }}" class="btn btn-primary btn-sm">
                <i class="cil-arrow-left"></i> {{ __('Back to Clients') }}
            </a>
        </div>


    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ __('Contact Information') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('clients.contacts.store-independent') }}" method="POST" id="contactForm">
                @csrf
                <input type="hidden" name="redirect_to_client" value="1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="client_id">{{ __('Client') }}</label>
                        
                      
                            <style>
                                .select2-container .select2-selection--single {
                                    border: 1px solid var(--cui-input-border-color, #DBDFE6);
                                    border-radius: 0.25rem;
                                    height: 37px !important;
                                    background-color: var(--cui-input-bg, #fff);
                                    color: var(--cui-input-color, #4f5d73);
                                }
                                
                                .select2-container--default .select2-selection--single .select2-selection__rendered {
                                    padding-top: 3px;
                                    color: var(--cui-input-color, #4f5d73);
                                }
                                
                                .select2-dropdown {
                                    background-color: var(--cui-input-bg, #fff);
                                    border-color: var(--cui-input-border-color, #DBDFE6);
                                    color: var(--cui-input-color, #4f5d73);
                                }
                            </style>
                   

                        <!-- Modify the select element to add select2-client class -->
                        <select class="select2-client form-select @error('client_id') is-invalid @enderror" 
                            id="client_id" name="client_id" required>
                            <option value="">{{ __('Select Client') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->company_name }}
                                </option>
                            @endforeach
                        </select>
                        
                       
                    </div>

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
                    <a href="{{ route('clients.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Save Contact') }}</button>
                </div>
            </form>
        </div>
    </div>
    
@push('scripts')
<script>
    function initSelect2() {
        $('.select2-client').select2({
            placeholder: 'Select client...',
            width: '100%',
            minimumResultsForSearch: 0
        });
    }

    function loadScript() {
        const jqueryScript = document.createElement('script');
        jqueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        jqueryScript.onload = function() {
            const select2Script = document.createElement('script');
            select2Script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
            select2Script.onload = function() {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
                document.head.appendChild(link);
                setTimeout(initSelect2, 300);
            };
            document.head.appendChild(select2Script);
        };
        document.head.appendChild(jqueryScript);
    }

    document.addEventListener('DOMContentLoaded', loadScript);
</script>
@endpush
</x-app-layout>
