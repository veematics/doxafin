<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Client;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('client')->get();
        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $clients = Client::all();
        $statuses = ['Draft', 'Pending', 'Approved', 'Completed'];
        return view('purchase-orders.create', compact('clients', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'poNo' => 'required|unique:purchase_orders',
            'poClient' => 'required|exists:clients,id',
            'poStatus' => 'required',
            'poTerm' => 'required'
        ]);

        PurchaseOrder::create($validated);
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order created successfully.');
    }

    // Add other resource methods as needed
}
