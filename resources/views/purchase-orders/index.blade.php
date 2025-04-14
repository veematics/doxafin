<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Purchase Orders</h1>
            <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">Create New PO</a>
        </div>

        <div class="bg-white shadow-md rounded-lg">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Payment Terms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->poNo }}</td>
                            <td>{{ $po->client->name }}</td>
                            <td>{{ $po->poStatus }}</td>
                            <td>{{ $po->poTerm }}</td>
                            <td>
                                <a href="{{ route('purchase-orders.edit', $po->poID) }}" class="btn btn-sm btn-primary">Edit</a>
                                <a href="{{ route('purchase-orders.show', $po->poID) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>