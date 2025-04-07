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
            'description' => 'nullable|string',
        ]);

        $menu = Menu::create($validated);

        return redirect()->route('appsetting.menu.index')
            ->with('success', 'Menu created successfully.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $menu->update($validated);

        return redirect()
            ->route('appsetting.menu.edit', $menu)
            ->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete(); // Cascade deletion handled by foreign key

        return redirect()
            ->route('appsetting.menu.index')
            ->with('success', 'Menu deleted successfully.');
    }

    // Change method name from saveStructure to structure
    public function structure(Request $request, Menu $menu)
    {
        $request->validate([
            'structure' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // Clear existing items for this menu
            MenuItem::where('menu_id', $menu->id)->delete();

            // Create new structure
            $this->createMenuStructure($request->structure, $menu->id);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Menu structure updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function createMenuStructure(array $items, $menuId, $parentId = null, $order = 0)
    {
        foreach ($items as $item) {
            // Create new menu item
            $menuItem = MenuItem::create([
                'menu_id' => $menuId,
                'parent_id' => $parentId,
                'title' => $item['title'],
                'icon' => $item['icon'],
                'item_type' => $item['item_type'],
                'app_feature_id' => $item['app_feature_id'],
                'path' => $item['path'],
                'order' => $order
            ]);

            if (!empty($item['children'])) {
                $this->createMenuStructure($item['children'], $menuId, $menuItem->id, 0);
            }

            $order++;
        }
    }
}