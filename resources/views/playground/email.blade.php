<x-app-layout>
    @php
       if (session('role_name') !== 'SA') {
             abort(403, 'Playground access is forbidden for you. Your action logged for security assestment');
         }
    @endphp
<div class="container-lg">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <strong>Email Testing Playground</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('playground.email.send') }}" method="POST">
                @csrf


                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="to_name">To Name</label>
                            <input type="text" class="form-control @error('to_name') is-invalid @enderror" 
                                id="to_name" name="to_name" value="{{ old('to_name', 'Viktor iwan') }}">
                            @error('to_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="to_email">To Email</label>
                            <input type="email" class="form-control @error('to_email') is-invalid @enderror" 
                                id="to_email" name="to_email" value="{{ old('to_email', 'viktor.iwan@doxadigital.com') }}">
                            @error('to_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="subject">Subject</label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                        id="subject" name="subject" value="{{ old('subject', 'Findoxa test : ' . now()->format('Y-m-d H:i:s')) }}">
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="message">Message</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" 
                        id="message" name="message" rows="5">{{ old('message', now()->format('Y-m-d H:i:s')) }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Send Test Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>