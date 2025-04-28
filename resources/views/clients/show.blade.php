<x-app-layout>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">{{ __('Client Details') }}</h2>
            <a href="{{ route('clients.index') }}" class="btn btn-primary btn-sm">
                <i class="cil-arrow-left"></i> {{ __('Back to List') }}
            </a>
        </div>


    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">{{ $client->company_name }}</h6>
              
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>{{ __('Company Information') }}</h6>
                    <table class="table">
                        <tr>
                            <th width="30%">{{ __('Company Code') }}</th>
                            <td>{{ $client->company_code }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Address') }}</th>
                            <td>{{ $client->company_address }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('NPWP') }}</th>
                            <td>{{ $client->npwp }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Website') }}</th>
                            <td>
                                @if($client->website)
                                    <a href="{{ $client->website }}" target="_blank">{{ $client->website }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>{{ __('Additional Information') }}</h6>
                    <table class="table">
                        <tr>
                            <th width="30%">{{ __('Assigned To') }}</th>
                            <td>{{ $client->assignedUser?->name ?? 'Unassigned' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Created By') }}</th>
                            <td>{{ $client->createdBy?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ $client->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Last Updated') }}</th>
                            <td>{{ $client->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($client->notes || $client->payment_terms)
                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __('Notes') }}</h6>
                            <div class="p-3 rounded" style="background: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                {!! $client->notes !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('Default Payment Terms') }}</h6>
                            <div class="p-3 rounded" style="background: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                {!! $client->payment_terms !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ __('Contacts') }} ({{ $client->contacts->count() }})</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-coreui-toggle="modal" data-coreui-target="#addContactModal">
                        <i class="cil-plus"></i> {{ __('Add Contact') }}
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="30%">{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Position') }}</th>
                                <th width="15%">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->contacts as $contact)
                                <tr>
                                    <td class="position-relative">
                                        {{ $contact->name }}
                                        @if($contact->is_primary)
                                            <i class="cil-star text-warning ms-2" title="{{ __('Primary Contact') }}"></i>
                                        @else
                                            <a href="#" class="make-primary-contact ms-2 text-muted" style="display:none"
                                               onclick="event.preventDefault(); document.getElementById('make-primary-{{ $contact->id }}').submit();">
                                              <span style="font-size: 0.7em">  [{{ __('make default') }}]</span>
                                            </a>
                                            <form id="make-primary-{{ $contact->id }}" 
                                                  action="{{ route('clients.contacts.make-primary', [$client, $contact]) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        @endif
                                    </td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->phone_number }}</td>
                                    <td>{{ $contact->role }}</td>
                                    <td>
                                        <a href="{{ route('clients.contacts.edit', [$client, $contact]) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="cil-pencil"></i>
                                        </a>
                                        <form action="{{ route('clients.contacts.destroy', [$client, $contact]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('{{ __('Are you sure?') }}')">
                                                <i class="cil-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('No contacts found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Contact Modal -->
            <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addContactModalLabel">{{ __('Add Contact') }}</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('clients.contacts.store', $client) }}" method="POST" id="addContactForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label" for="salutation">{{ __('Salutation') }}</label>
                                    <select class="form-select @error('salutation') is-invalid @enderror" id="salutation" name="salutation">
                                        <option value="">{{ __('Select Salutation') }}</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                    @error('salutation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="name">{{ __('Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        id="name" name="name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">{{ __('Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="phone_number">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                        id="phone_number" name="phone_number">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="role">{{ __('Position') }}</label>
                                    <input type="text" class="form-control @error('role') is-invalid @enderror" 
                                        id="role" name="role">
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('Save Contact') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('td:first-child').forEach(td => {
            td.addEventListener('mouseenter', function() {
                const link = this.querySelector('.make-primary-contact');
                if (link) link.style.display = 'inline';
            });
            td.addEventListener('mouseleave', function() {
                const link = this.querySelector('.make-primary-contact');
                if (link) link.style.display = 'none';
            });
        });
    </script>
    @endpush
</x-app-layout>