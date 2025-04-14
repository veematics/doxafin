<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Create Purchase Order</h1>

        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="poNo" class="form-label">PO Number</label>
                <input type="text" name="poNo" id="poNo" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="poClient" class="form-label">Client</label>
                <select name="poClient" id="poClient" class="form-control" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" data-terms="{{ $client->payment_terms }}">
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="poStatus" class="form-label">Status</label>
                <select name="poStatus" id="poStatus" class="form-control" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="poTerm" class="form-label">Payment Terms</label>
                <input type="text" name="poTerm" id="poTerm" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Purchase Order</button>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('poClient').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('poTerm').value = selectedOption.dataset.terms;
        });
    </script>
    @endpush
</x-app-layout>