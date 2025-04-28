<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fs-2 fw-semibold mb-0">{{ __('Contact Search') }}</h2>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <form method="GET" action="{{ route('clients.contacts.search') }}" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="s" 
                            value="{{ $searchTerm }}" placeholder="{{ __('Search contacts, companies, or email...') }}">
                        <button class="btn btn-primary" type="submit">
                            <svg class="icon">
                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-search"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Client') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>
                                    @if($searchTerm)
                                        {!! preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<strong>$1</strong>', e($contact->name)) !!}
                                    @else
                                        {{ $contact->name }}
                                    @endif
                                </td>
                                <td>
                                    @if($searchTerm)
                                        {!! preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<strong>$1</strong>', e($contact->company_name)) !!}
                                    @else
                                        {{ $contact->company_name }}
                                    @endif
                                </td>
                                <td>{{ $contact->phone_number }}</td>
                                <td>
                                    @if($searchTerm)
                                        {!! preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<strong>$1</strong>', e($contact->email)) !!}
                                    @else
                                        {{ $contact->email }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('clients.show', $contact->client_id) }}" 
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-building me-1"></i>
                                        {{ __('See Company') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    {{ __('No contacts found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $contacts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>