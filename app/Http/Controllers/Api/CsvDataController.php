<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CsvData;
use Illuminate\Http\Request;

class CsvDataController extends Controller
{
    public function show($dataName)
    {
        $csvData = CsvData::where('data_name', $dataName)->first();
        
        if (!$csvData) {
            return response()->json(['error' => 'CSV data not found'], 404);
        }

        // Parse CSV content - Changed csv_content to data_value to match the model
        $rows = array_map('str_getcsv', explode("\n", $csvData->data_value));
        
        // Get headers from first row
        $headers = array_shift($rows);
        
        // Convert rows to associative array
        $data = array_map(function($row) use ($headers) {
            return array_combine($headers, $row);
        }, array_filter($rows)); // array_filter removes empty rows
        
        return response()->json($data);
    }
}