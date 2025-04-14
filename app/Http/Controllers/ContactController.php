<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create(Client $client)
    {
        return view('clients.contacts.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'salutation' => 'nullable|string|in:Mr,Mrs,Ms,Dr',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'boolean'
        ]);

        // Add created_by before creating the contact
        $validated['created_by'] = auth()->id();

        $client->contacts()->create($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Contact added successfully');
    }

    public function edit(Client $client, Contact $contact)
    {
        return view('clients.contacts.edit', compact('client', 'contact'));
    }

    public function update(Request $request, Client $client, Contact $contact)
    {
        $validated = $request->validate([
            'salutation' => 'nullable|string|in:Mr,Mrs,Ms,Dr',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'boolean'
        ]);

        if ($request->boolean('is_primary') && !$contact->is_primary) {
            $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }

        $contact->update($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Contact updated successfully');
    }

    public function destroy(Client $client, Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Contact deleted successfully');
    }

    public function makePrimary(Client $client, Contact $contact)
    {
        // Remove primary status from all other contacts
        $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        
        // Set this contact as primary
        $contact->update(['is_primary' => true]);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Primary contact updated successfully');
    }

    private function getFeatureID()
    {
        return \App\Models\AppFeature::where('featureName', 'Clients')
            ->value('featureID');
    }

    public function createIndependent()
    {
        $featureId = $this->getFeatureID();
        $canView = \App\Helpers\FeatureAccess::getViewLevelById(auth()->id(), $featureId);
        
        $query = Client::query();
        
        switch ($canView) {
            case 1: // View all
                break;
            case 2: // Same role
                $userRoleIds = auth()->user()->roles()->pluck('roles.id'); // Specify the table name
                $userIds = \DB::table('role_user')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('role_user.user_id') // Explicitly select the user_id column
                    ->whereIn('roles.id', $userRoleIds)
                    ->pluck('user_id');
                $query->whereIn('clients.assign_to', $userIds);
                break;
            case 3: // Only assigned
                $query->where('clients.assign_to', auth()->id());
                break;
            default:
                $query->where('clients.id', 0);
        }
        
        $clients = $query->orderBy('clients.company_name')->get();
        
        return view('clients.contacts.create-independent', compact('clients', 'featureId', 'canView'));
    }

    /**
     * Store a newly created contact from independent form.
     */
    public function storeIndependent(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'salutation' => 'nullable|string|max:10',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'is_primary' => 'boolean'
        ]);

        $client = \App\Models\Client::findOrFail($validated['client_id']);
        $contact = $client->contacts()->create($validated);

        if ($contact->is_primary) {
            $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', __('Contact created successfully'));
    }
}