<x-app-layout>
@php
      if (!str_contains(session('role_name'), 'SA')) {
         abort(403, 'Playground access is forbidden for you. Your action logged for security assestment');
      }
   @endphp
    <a href="{{ route('playground.index') }}" class="btn btn-primary mb-4"><< Back to Index</a>
    
    <div class="container-lg">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Select2 Playground</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Test Select2</label>
                    <x-select2 
                        id="test-select"
                        name="test_select"
                        :options="[
                            'data' => 'Data',
                            'alamat' => 'Alamat',
                            'nomor_telepon' => 'Nomor Telepon',
                            'status' => 'Status',
                            'dana' => 'Dana',
                            'doxadigital' => 'Doxadigital',
                            'pt_doxa360' => 'PT Doxa360'
                        ]"
                        placeholder="Choose an option"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Multiple Select</label>
                    <x-select2 
                        id="multiple-select"
                        name="multiple_select[]"
                        :options="[
                            'data' => 'Data',
                            'alamat' => 'Alamat',
                            'nomor_telepon' => 'Nomor Telepon'
                        ]"
                        multiple
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>