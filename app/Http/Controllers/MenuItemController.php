<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\AppFeature;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class MenuItemController extends Controller
{


    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'item_type' => ['required', 'in:feature,free_form'],
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'path' => [
                Rule::requiredIf(fn() => $request->item_type === 'free_form'),
                'nullable',
                'string',
                'max:2048'
            ],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'app_feature_id' => [
                Rule::requiredIf(fn() => $request->item_type === 'feature'),
                'nullable',
                'exists:appfeatures,featureID'
            ],
            'custom_data' => ['nullable', 'json'],
        ]);

        // Get the highest order number and add 1
        $maxOrder = $menu->menuItems()->whereNull('parent_id')->max('order') ?? -1;
        $validated['order'] = $maxOrder + 1;
        
        $menuItem = $menu->menuItems()->create($validated);

  

        return response()->json([
            'message' => 'Menu item created successfully',
            'item' => $menuItem->load(['appFeature'])
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'path' => [
                Rule::requiredIf(fn() => $menuItem->item_type === 'free_form'),
                'nullable',
                'string',
                'max:2048'
            ],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
            'custom_data' => ['nullable', 'json'],
        ]);

        $menuItem->update($validated);



        return response()->json([
            'message' => 'Menu item updated successfully',
            'item' => $menuItem->fresh(['appFeature'])
        ]);
    }

    public function destroy(MenuItem $menuItem)
    {
        try {
            $menuItem->delete();

    

            return response()->json([
                'message' => 'Menu item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete menu item'
            ], 500);
        }
    }
}