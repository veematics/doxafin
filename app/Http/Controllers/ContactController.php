<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Helpers\FeatureAccess;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public function create(Client $client)
    {
        return view('clients.contacts.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean'
        ]);

        // Handle is_primary checkbox
        $validated['is_primary'] = $request->has('is_primary');

        // Create contact with all validated data
        $contact = Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
            'is_primary' => $validated['is_primary'],
            'client_id' => $client->id
        ]);

        if ($validated['is_primary']) {
            $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'Contact created successfully');
    }

    public function createIndependent()
    {
        $featureId = \App\Models\AppFeature::where('featureName', 'Clients')->value('featureID');
        $canView = FeatureAccess::getViewLevelById(auth()->id(), $featureId);
        
        $query = Client::query();

        // Apply permission filters
        switch ($canView) {
            case 1: // All clients
                $clients = $query->get();
                break;
            case 2: // Clients from same roles
                $userRoleIds = auth()->user()
                    ->roles()
                    ->select('roles.id')
                    ->pluck('roles.id');
                    
                $clients = $query->whereExists(function ($subquery) use ($userRoleIds) {
                    $subquery->from('role_user')
                        ->select('role_user.user_id')
                        ->whereColumn('role_user.user_id', 'clients.assign_to')
                        ->whereIn('role_user.role_id', $userRoleIds);
                })->get();
                break;
            case 3: // Only assigned clients
                $clients = $query->where('clients.assign_to', auth()->id())->get();
                break;
            default:
                $clients = collect();
        }

        return view('clients.contacts.create-independent', compact('clients'));
    }

    public function storeIndependent(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'salutation' => 'required|string|in:Mr.,Mrs.,Ms.,Dr.',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean'
        ]);

        // Handle is_primary checkbox
        $validated['is_primary'] = $request->has('is_primary');
        
        // Add created_by
        $validated['created_by'] = auth()->id();

        // Create contact with all validated data
        $contact = Contact::create([
            'client_id' => $validated['client_id'],
            'salutation' => $validated['salutation'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
            'is_primary' => $validated['is_primary'],
            'created_by' => $validated['created_by']
        ]);

        if ($validated['is_primary']) {
            Contact::where('client_id', $validated['client_id'])
                  ->where('id', '!=', $contact->id)
                  ->update(['is_primary' => false]);
        }

        return redirect()->route('clients.show', $validated['client_id'])
            ->with('success', 'Contact created successfully');
    }

    public function edit(Client $client, Contact $contact)
    {
        return view('clients.contacts.edit', compact('client', 'contact'));
    }

    public function update(Request $request, Client $client, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'nullable|boolean'  // Change to nullable
        ]);

        // Handle is_primary checkbox
        $validated['is_primary'] = $request->has('is_primary');

        $contact->update($validated);

        if ($validated['is_primary']) {
            $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'Contact updated successfully');
    }

    public function destroy(Client $client, Contact $contact)
    {
        $contact->delete();
        return redirect()->route('clients.show', $client)
            ->with('success', 'Contact deleted successfully');
    }

    public function makePrimary(Client $client, Contact $contact)
    {
        $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        $contact->update(['is_primary' => true]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Primary contact updated successfully');
    }
}