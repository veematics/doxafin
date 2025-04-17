<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Purchase Order Details</h1>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <label class="font-bold">PO Number:</label>
                <span>{{ $purchaseOrder->po_no }}</span>
            </div>

            <div class="mb-4">
                <label class="font-bold">Client:</label>
                <span>{{ $purchaseOrder->client->name }}</span>
            </div>

            <div class="mb-4">
                <label class="font-bold">Status:</label>
                <span>{{ $purchaseOrder->status }}</span>
            </div>

            <div class="mb-4">
                <label class="font-bold">Payment Terms:</label>
                <span>{{ $purchaseOrder->payment_terms }}</span>
            </div>

            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</x-app-layout>