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
        $validated = $request->validate([
            'featureName' => 'required|string|max:255',
            'featureIcon' => 'required|string|max:255',
            'featurePath' => 'required|string|max:255',
        ]);

        $validated['featureActive'] = $request->has('featureActive') ? 1 : 0;

        AppFeature::create($validated);

        return redirect()->route('appsetting.appfeature.index')
            ->with('success', 'Feature created successfully.');
    }

    public function update(Request $request, AppFeature $appfeature)
    {
        $validated = $request->validate([
            'featureName' => 'required|string|max:255',
            'featureIcon' => 'required|string|max:255',
            'featurePath' => 'required|string|max:255',
        ]);

        $validated['featureActive'] = $request->has('featureActive') ? 1 : 0;

        $appfeature->update($validated);

        return redirect()->route('appsetting.appfeature.index')
            ->with('success', 'Feature updated successfully.');
    }

    public function destroy(AppFeature $appfeature)
    {
        $appfeature->delete();

        return redirect()->route('appsetting.appfeature.index')
            ->with('success', 'Feature deleted successfully.');
    }
}