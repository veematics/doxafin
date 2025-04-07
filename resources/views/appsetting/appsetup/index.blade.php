<x-app-layout>
    <div class="card mb-4">
        <div class="card-header">
            <strong>Application Setup</strong>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('appsetting.appsetup.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Application Name</label>
                            <input type="text" class="form-control @error('AppsName') is-invalid @enderror" 
                                name="AppsName" value="{{ old('AppsName', $appSetup->AppsName) }}" required>
                            @error('AppsName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Application Title</label>
                            <input type="text" class="form-control @error('AppsTitle') is-invalid @enderror" 
                                name="AppsTitle" value="{{ old('AppsTitle', $appSetup->AppsTitle) }}" required>
                            @error('AppsTitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Application Subtitle</label>
                            <input type="text" class="form-control @error('AppsSubTitle') is-invalid @enderror" 
                                name="AppsSubTitle" value="{{ old('AppsSubTitle', $appSetup->AppsSubTitle) }}">
                            @error('AppsSubTitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Application Logo</label>
                            @if($appSetup->AppsLogo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/images/app/' . $appSetup->AppsLogo) }}" alt="App Logo" class="img-thumbnail" style="max-height: 100px">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('AppsLogo') is-invalid @enderror" 
                                name="AppsLogo" accept="image/*">
                            @error('AppsLogo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Logo</label>
                            @if($appSetup->AppsShortLogo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/images/app/' . $appSetup->AppsShortLogo) }}" alt="Short Logo" class="img-thumbnail" style="max-height: 50px">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('AppsShortLogo') is-invalid @enderror" 
                                name="AppsShortLogo" accept="image/*">
                            @error('AppsShortLogo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <svg class="icon me-1">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-save"></use>
                        </svg> 
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>