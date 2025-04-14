<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use App\Helpers\FeatureAccess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CsvDataController extends Controller
{
    // Remove the constructor with middleware
    
    public function index()
    {
        if (!FeatureAccess::check(auth()->id(), 'csv_data', 'can_view')) {
            abort(403, 'Unauthorized access');
        }
        
        $csvData = CsvData::all();
        return view('csv-data.index', compact('csvData'));
    }

    public function create()
    {
        if (!FeatureAccess::check(auth()->id(), 'csv_data', 'can_create')) {
            abort(403, 'Unauthorized access');
        }
        
        return view('csv-data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_name' => 'required|string|unique:csv_data',
            'data_value' => 'required|string',
        ]);

        CsvData::create($validated);

        return redirect()
            ->route('csv-data.index')
            ->with('success', 'CSV Data created successfully');
    }

    public function edit(CsvData $csvData)
    {
        return view('csv-data.edit', compact('csvData'));
    }

    public function update(Request $request, CsvData $csvData)
    {
        $validated = $request->validate([
            'data_name' => 'required|string|unique:csv_data,data_name,' . $csvData->id,
            'data_value' => 'required|string',
        ]);

        $csvData->update($validated);

        return redirect()
            ->route('csv-data.index')
            ->with('success', 'CSV Data updated successfully');
    }

    public function destroy(CsvData $csvData)
    {
        $csvData->delete();
        return redirect()
            ->route('csv-data.index')
            ->with('success', 'CSV Data deleted successfully');
    }

    public function view(CsvData $csvData)
    {
        $csvContent = trim($csvData->data_value);
        $rows = array_filter(array_map('str_getcsv', explode("\n", $csvContent)));
        $headers = !empty($rows) ? array_shift($rows) : [];
        
        return view('csv-data.partials.table', [
            'headers' => $headers,
            'rows' => $rows
        ]);
    }
}