<?php

namespace App\Http\Controllers;

use App\Models\AppFeature;
use Illuminate\Http\Request;

class AppFeatureController extends Controller
{
    public function index()
    {
        $features = AppFeature::all();
        return view('appsetting.appfeature.index', compact('features'));
    }

    public function create()
    {
        return view('appsetting.appfeature.create');
    }

    // Add this edit method
    public function edit(AppFeature $appfeature)
    {
        return view('appsetting.appfeature.edit', compact('appfeature'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateFeature($request);
        
        $validated['featureActive'] = $request->has('featureActive') ? 1 : 0;

        AppFeature::create($validated);

        return redirect()->route('appsetting.appfeature.index')
            ->with('success', 'Feature created successfully.');
    }

    public function update(Request $request, AppFeature $appfeature)
    {
        $validated = $this->validateFeature($request);
        
        $validated['featureActive'] = $request->has('featureActive') ? 1 : 0;

        $appfeature->update($validated);

        return redirect()->route('appsetting.appfeature.index')
            ->with('success', 'Feature updated successfully.');
    }

    protected function validateFeature(Request $request)
    {
        return $request->validate([
            'featureName' => 'required|string|max:255',
            'featureIcon' => 'required|string|max:255',
            'featurePath' => 'required|string|max:255',
            'custom_permission' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        return; // Empty value is allowed
                    }

                    $lines = explode("\n", $value);
                    foreach ($lines as $lineNumber => $line) {
                        $line = trim($line);
                        if (empty($line)) {
                            continue; // Skip empty lines
                        }

                        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9\s_]+:[a-zA-Z0-9\s,]+$/', $line)) {
                            $fail("Invalid format at line " . ($lineNumber + 1) . ". Format should be 'Permission Name:Option1, Option2'");
                        }
                    }
                }
            ]
        ]);
    }

    public function destroy(AppFeature $appfeature)
    {
        $appfeature->delete();

        return redirect()->route('appsetting.appfeature.index')
            ->with('success', 'Feature deleted successfully.');
    }
}