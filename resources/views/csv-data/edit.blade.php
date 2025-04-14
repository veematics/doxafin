<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">{{ __('Edit CSV Data') }}</h2>
        <a href="{{ route('csv-data.index') }}" class="btn btn-primary">
            <i class="cil-arrow-left"></i> {{ __('Back to List') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('csv-data.update', $csvData) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label" for="data_name">{{ __('Data Name') }}</label>
                    <input type="text" class="form-control @error('data_name') is-invalid @enderror" 
                           id="data_name" name="data_name" value="{{ old('data_name', $csvData->data_name) }}" required>
                    @error('data_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="data_value">{{ __('CSV Data') }}</label>
                    <textarea class="form-control @error('data_value') is-invalid @enderror" 
                              id="data_value" name="data_value" rows="10" required>{{ old('data_value', $csvData->data_value) }}</textarea>
                    <div class="form-text">{{ __('Enter CSV data, one row per line') }}</div>
                    @error('data_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Update CSV Data') }}</button>
            </form>
        </div>
    </div>
</x-app-layout>