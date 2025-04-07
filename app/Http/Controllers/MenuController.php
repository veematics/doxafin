<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\AppFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('appsetting.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('appsetting.menu.create');
    }

    public function edit(Menu $menu)
    {
        $menuItems = $menu->topLevelItems()->with('children')->get();
        $features = AppFeature::where('featureActive', true)
            ->orderBy('featureName')
            ->get();

        return view('appsetting.menu.edit', compact('menu', 'menuItems', 'features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sidebar,personal',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $menu = Menu::create($validated);

        return redirect()
            ->route('menu.edit', $menu)
            ->with('success', 'Menu created successfully.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:sidebar,personal',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $menu->update($validated);

        return redirect()
            ->route('menu.edit', $menu)
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete(); // Cascade deletion handled by foreign key

        return redirect()
            ->route('menu.index')
            ->with('success', 'Menu deleted successfully.');
    }

    public function saveStructure(Request $request, Menu $menu)
    {
        $request->validate([
            'structure' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $this->updateMenuStructure($request->structure);

            DB::commit();

            return response()->json(['message' => 'Menu structure updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update menu structure'], 500);
        }
    }

    private function updateMenuStructure(array $items, $parentId = null, $order = 0)
    {
        foreach ($items as $item) {
            MenuItem::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'order' => $order,
            ]);

            if (!empty($item['children'])) {
                $this->updateMenuStructure($item['children'], $item['id'], 0);
            }

            $order++;
        }
    }
}