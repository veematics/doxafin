<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\FeatureRole;
use App\Models\AppFeature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                    'can_view' => isset($permissions['can_view']) ? 1 : 0,
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role)],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['array']
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
        ]);

        // Clear existing permissions
        $role->features()->detach();

        // Process permissions
        if (isset($validated['permissions'])) {
            foreach ($validated['permissions'] as $featureId => $permissions) {
                $featureRole = [
                    'can_view' => isset($permissions['can_view']) ? 1 : 0,
                    'can_create' => isset($permissions['can_create']) ? 1 : 0,
                    'can_edit' => isset($permissions['can_edit']) ? 1 : 0,
                    'can_delete' => isset($permissions['can_delete']) ? 1 : 0,
                    'can_approve' => isset($permissions['can_approve']) ? 1 : 0,
                ];

                // Handle special permissions if they exist
                if (isset($permissions['special'])) {
                    $featureRole['additional_permissions'] = json_encode($permissions['special']);
                }

                // Attach feature with permissions
                $role->features()->attach($featureId, $featureRole);
            }
        }

        return redirect()->route('appsetting.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
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
}