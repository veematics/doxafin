<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Helpers\FeatureAccess; // Add this import
use Illuminate\Support\Facades\Cache;
use App\Models\AppFeature;
use App\Models\User;
use App\Models\Contact;  // Add this with other use statements at the top

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
                $userRoleIds = auth()->user()
                    ->roles()
                    ->select('roles.id')  // Explicitly select roles.id
                    ->pluck('roles.id');  // Use explicit table.column reference
                    
                $query->whereExists(function ($subquery) use ($userRoleIds) {
                    $subquery->from('role_user')
                        ->select('role_user.user_id')  // Add explicit column selection
                        ->whereColumn('role_user.user_id', 'clients.assign_to')
                        ->whereIn('role_user.role_id', $userRoleIds);
                });
                break;
            case 3: // Only assigned clients
                $query->where('clients.assign_to', auth()->id());
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
            1 => User::all(),
            2 => User::whereHas('roles', function($query) {
                    $userRoleIds = auth()->user()
                        ->roles()
                        ->select('roles.id as role_id')
                        ->pluck('role_id');
                    $query->whereIn('roles.id', $userRoleIds);
                })->get(),
            3 => User::where('id', auth()->id())->get(),
            default => collect([auth()->user()]),
        };
    
        return view('clients.create', [
            'users' => $users,
            'created_by' => auth()->id()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_alias' => 'nullable|string|max:255',
            'company_code' => 'nullable|string|max:4',
            'company_address' => 'nullable|string',
            'npwp' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'assign_to' => 'nullable|exists:users,id',
            'payment_terms' => 'nullable|string', // Added this line
            'notes' => 'nullable|string',
        ]);

        // Set created_by field to current user id
        $validated['created_by'] = auth()->id();
        
        $client = Client::create($validated);

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
            'payment_terms' => 'nullable|string', // Add this line
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

    public function searchContacts(Request $request)
    {
        $featureId = AppFeature::where('featureName', 'Clients')->value('featureID');
        $userId = auth()->id();
        // Update to accept both 'q' and 's' parameters
        $searchTerm = $request->input('q', $request->input('s', ''));
        
        if ($request->ajax()) {
            // For AJAX search in header
            $searchQuery = Client::where('company_name', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('contacts', function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                })
                ->with('contacts');
        } else {
            // For full contact search page
            $searchQuery = Contact::query()
                ->join('clients', 'contacts.client_id', '=', 'clients.id')
                ->select('contacts.*', 'clients.company_name', 'clients.id as client_id')
                ->where(function($q) use ($searchTerm) {
                    $q->where('contacts.name', 'like', "%{$searchTerm}%")
                      ->orWhere('clients.company_name', 'like', "%{$searchTerm}%")
                      ->orWhere('contacts.email', 'like', "%{$searchTerm}%");
                });
        }

        // Apply permission filters based on user's access level
        $canView = FeatureAccess::check($userId, 'Clients', 'can_view');
        
        if ($canView == 3) {
            $searchQuery->where('clients.assign_to', $userId);
        } elseif ($canView == 2) {
            $userRoleIds = auth()->user()->roles()->pluck('roles.id');
            $searchQuery->whereIn('clients.assign_to', function($subquery) use ($userRoleIds) {
                $subquery->select('users.id')
                    ->from('users')
                    ->join('role_user', 'users.id', '=', 'role_user.user_id')
                    ->whereIn('role_user.role_id', $userRoleIds);
            });
        }

        if ($request->ajax()) {
            $results = $searchQuery->get();
            return view('clients.search-results', compact('results', 'searchTerm'));
        } else {
            $contacts = $searchQuery->paginate(10);
            return view('clients.contacts.search', compact('contacts', 'searchTerm'));
        }
    }
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully');
    }
    public function getClientDetails(Client $client)
    {
        return response()->json([
            'id' => $client->id,
            'company_name' => $client->company_name,
            'company_alias' => $client->company_alias,
            'company_code' => $client->company_code,
            'company_address' => $client->company_address,
            'npwp' => $client->npwp,
            'website' => $client->website,
            'payment_terms' => $client->payment_terms,
            'notes' => $client->notes,
            'assigned_user' => $client->assignedUser ? [
                'id' => $client->assignedUser->id,
                'name' => $client->assignedUser->name
            ] : null
        ]);
    }   
}

