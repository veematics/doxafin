<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Helpers\FeatureAccess; // Add this import
use Illuminate\Support\Facades\Cache;
use App\Models\AppFeature;

class ClientController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $featureId = AppFeature::where('featureName', 'Clients')->value('featureID');
        $cacheKey = 'user_permissions_' . $userId;
        $permissions = Cache::get($cacheKey);

        if (!$permissions || !isset($permissions[$featureId])) {
            abort(403, 'No permissions found');
        }

        $canView = $permissions[$featureId]->first()->can_view;
     
        $query = Client::query();
    
        // Apply search filter
        if (request('search')) {
            $searchTerm = '%' . request('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('company_name', 'like', $searchTerm)
                  ->orWhere('company_code', 'like', $searchTerm)
                  ->orWhereHas('contacts', function($q) use ($searchTerm) {
                      $q->where('name', 'like', $searchTerm);
                  });
            });
        }

        // Apply permission filters
        switch ($canView) {
            case 1: // All clients
                break;
            case 2: // Clients from same roles
                $userRoleIds = auth()->user()->roles()->pluck('roles.id');
                $query->whereExists(function ($subquery) use ($userRoleIds) {
                    $subquery->from('role_user')
                        ->whereColumn('role_user.user_id', 'clients.assign_to')
                        ->whereIn('role_user.role_id', $userRoleIds);
                });
                break;
            case 3: // Only assigned clients
                $query->where('assign_to', auth()->id());
                break;
            default:
                abort(403, 'Unauthorized access');
        }

        // Handle per_page parameter
        $perPage = request('per_page', 20);
        $clients = $perPage === 'all' ? $query->get() : $query->paginate($perPage);

        return view('clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load(['assignedUser', 'contacts', 'createdBy']);
        
        return view('clients.show', compact('client'));
    }

    public function create()
    {
        $featureId = \App\Models\AppFeature::where('featureName', 'Client Management')->value('featureID');
        $canView = FeatureAccess::getViewLevelById(auth()->id(), $featureId);
        
        $users = match ($canView) {
            1 => User::all(), // All users
            2 => User::whereHas('roles', function($query) {
                    $query->whereIn('id', auth()->user()->roles->pluck('id'));
                })->get(), // Users in same roles
            3 => User::where('id', auth()->id())->get(), // Only current user
            default => collect([auth()->user()]), // Fallback to current user
        };

        return view('clients.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_alias' => 'nullable|string|max:50',
            'company_code' => 'required|string|size:4|unique:clients,company_code|alpha',
            'company_address' => 'nullable|string',
            'npwp' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'assign_to' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $validated['company_code'] = strtoupper($validated['company_code']);

        $client = Client::create([
            ...$validated,
            'created_by' => auth()->id()
        ]);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client created successfully');
    }

    public function edit(Client $client)
    {
        $featureId = \App\Models\AppFeature::where('featureName', 'Client Management')->value('featureID');
        $canView = FeatureAccess::getViewLevelById(auth()->id(), $featureId);
        
        $users = match ($canView) {
            1 => User::all(),
            2 => User::whereHas('roles', function($query) {
                    $query->whereIn('id', auth()->user()->roles->pluck('id'));
                })->get(),
            3 => User::where('id', auth()->id())->get(),
            default => collect([auth()->user()]),
        };

        return view('clients.edit', compact('client', 'users'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_alias' => 'nullable|string|max:50',
            'company_code' => 'required|string|size:4|unique:clients,company_code,'.$client->id.'|alpha',
            'company_address' => 'nullable|string',
            'npwp' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'assign_to' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $validated['company_code'] = strtoupper($validated['company_code']);
        
        $client->update($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully');
    }

    public function checkCompanyCode($code)
        {
            $exists = Client::where('company_code', $code)->exists();
            return response()->json(['exists' => $exists]);
        }

    public function getClientsForContact()
    {
        $user = auth()->user();
        $query = Client::query();
    
        switch ($user->can_view) {
            case 1: // All clients
                // No filtering needed
                break;
                
            case 2: // Same role clients
                $userRoleIds = $user->roles()->pluck('id');
                $userIdsInSameRoles = \DB::table('role_user')
                    ->whereIn('role_id', $userRoleIds)
                    ->pluck('user_id');
                
                $query->whereIn('assigned_to', $userIdsInSameRoles);
                break;
                
            case 3: // Only assigned clients
                $query->where('assigned_to', $user->id);
                break;
                
            default:
                $query->where('id', 0); // Return empty if no valid permission
        }
    
        return $query->orderBy('company_name')->get();
    }
}