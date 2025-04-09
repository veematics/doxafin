<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\FeatureRole;
use App\Models\AppFeature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\FeatureAccess;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $features = AppFeature::all();
        return view('roles.create', compact('features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);

        // Create the role
        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description']
        ]);

        // Process permissions
        if (isset($validated['permissions'])) {
            foreach ($validated['permissions'] as $featureId => $permissions) {
                $featureRole = [
                    'role_id' => $role->id,
                    'feature_id' => $featureId,
                    'can_view' => isset($permissions['can_view']) ? (int)$permissions['can_view'] : null,
                    'can_create' => isset($permissions['can_create']) ? 1 : 0,
                    'can_edit' => isset($permissions['can_edit']) ? 1 : 0,
                    'can_delete' => isset($permissions['can_delete']) ? 1 : 0,
                    'can_approve' => isset($permissions['can_approve']) ? 1 : 0,
                ];

                // Handle special permissions if they exist
                if (isset($permissions['special'])) {
                    $featureRole['additional_permissions'] = json_encode($permissions['special']);
                }

                // Insert into feature_role table
                FeatureRole::create($featureRole);
            }
        }

        return redirect()->route('appsetting.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $features = AppFeature::all();
        $role->load(['features' => function($query) {
            $query->withPivot('can_view', 'can_create', 'can_edit', 'can_delete', 'additional_permissions');
        }]);
        
        return view('roles.edit', compact('role', 'features'));
    }

    public function update(Request $request, Role $role)
    {
        // Your role update logic here
        
        // Rebuild cache for all users with this role
        $userIds = $role->users()->pluck('users.id');
        foreach ($userIds as $userId) {
            FeatureAccess::rebuildCache($userId);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        $role->update($validated);
    
        // Clear existing permissions
        $role->features()->detach();
    
        // Process permissions
        foreach ($request->permissions ?? [] as $featureId => $permissions) {
            $permissionData = [
                'can_create' => isset($permissions['can_create']),
                'can_edit' => isset($permissions['can_edit']),
                'can_delete' => isset($permissions['can_delete']),
                'can_approve' => isset($permissions['can_approve']),
                'can_view' => isset($permissions['can_view']) ? (int)$permissions['can_view'] : null,
            ];
    
            // Handle additional permissions if they exist
            if (isset($permissions['special'])) {
                $permissionData['additional_permissions'] = json_encode($permissions['special']);
            }
    
            $role->features()->attach($featureId, $permissionData);
        }
    
        return redirect()->route('appsetting.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        try {
            // Check if role exists and can be deleted
            if (!$role || $role->users()->count() > 0) {
                return redirect()->route('appsetting.roles.index')
                    ->with('error', 'Role cannot be deleted because it has associated users.');
            }

            // Delete role and its permissions
            $role->features()->detach();
            $role->delete();

            return redirect()->route('appsetting.roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('appsetting.roles.index')
                ->with('error', 'An error occurred while deleting the role.');
        }
    }

    public function members(Role $role)
    {
        $users = $role->users()->paginate(10);
        $allUsers = User::whereNotIn('users.id', $role->users()->pluck('users.id'))->get();
        
        return view('roles.members', compact('role', 'users', 'allUsers'));
    }

    public function addMembers(Request $request, Role $role)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $role->users()->syncWithoutDetaching($request->users);

        return response()->json([
            'success' => true,
            'message' => 'Members added successfully'
        ]);
    }
    public function permissions(Role $role)
        {
            $features = AppFeature::all();
            $role->load('features');
            return view('roles.permissions', compact('role', 'features'));
        }

    public function removeMember(Role $role, User $user)
    {
        $role->users()->detach($user->id);
        
        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully'
        ]);
    }

    public function duplicate(Role $role)
        {
            try {
                // Create new role with copied attributes
                $newRole = Role::create([
                    'name' => $role->name . '_copy_' . time(),
                    'display_name' => 'Copy of ' . $role->display_name,
                    'description' => $role->description
                ]);
    
                // Get all permissions from original role
                $permissions = $role->features()->get();
    
                // Copy permissions to new role
                foreach ($permissions as $feature) {
                    $pivotData = $feature->pivot;
                    $newRole->features()->attach($feature->featureID, [
                        'can_view' => $pivotData->can_view,
                        'can_create' => $pivotData->can_create,
                        'can_edit' => $pivotData->can_edit,
                        'can_delete' => $pivotData->can_delete,
                        'can_approve' => $pivotData->can_approve,
                        'additional_permissions' => $pivotData->additional_permissions
                    ]);
                }
    
                return redirect()->route('appsetting.roles.index')
                    ->with('success', 'Role duplicated successfully');
            } catch (\Exception $e) {
                return redirect()->route('appsetting.roles.index')
                    ->with('error', 'Failed to duplicate role');
            }
        }
}