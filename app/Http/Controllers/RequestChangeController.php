<?php

namespace App\Http\Controllers;

use App\Models\RequestChange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\FeatureAccess;

class RequestChangeController extends Controller
{
    protected $debug = 1; // Set to 1 to enable logging, 0 to disable

    protected function log($message, $context = [])
    {
        if ($this->debug) {
            \Log::info($message, $context);
        }
    }

    public function index()
    {
        $activeRC = DB::table('request_changes')
            ->leftJoin('users as creator', 'request_changes.created_by', '=', 'creator.id')
            ->leftJoin('users as approver', 'request_changes.approved_by', '=', 'approver.id')
            ->select('request_changes.*', 'creator.name as creator_name', 'approver.name as approver_name', 'request_changes.created_at')
            ->where('is_archived', false)
            ->orderBy('request_changes.updated_at', 'desc')
            ->get()
            ->groupBy('category');

        $archivedRC = DB::table('request_changes')
            ->leftJoin('users as creator', 'request_changes.created_by', '=', 'creator.id')
            ->leftJoin('users as approver', 'request_changes.approved_by', '=', 'approver.id')
            ->select('request_changes.*', 'creator.name as creator_name', 'approver.name as approver_name', 'request_changes.created_at')
            ->where('is_archived', true)
            ->orderBy('request_changes.archived_at', 'desc')
            ->paginate(10);

        $clients = \App\Models\Client::orderBy('company_name')->get();

        return view('request-changes.index', compact('activeRC', 'archivedRC', 'clients'));
    }

    // public function create()
    // {
    //     $purchaseOrders = \App\Models\PurchaseOrder::orderBy('created_at', 'desc')->get();
    //     $users = \App\Models\User::orderBy('name')->get();
    //     $clients = \App\Models\Client::orderBy('company_name')->get();
    //     $statuses = RequestChange::statuses();
        
    //     return view('request-changes.create', compact('purchaseOrders', 'users', 'clients', 'statuses'));
    // }

    public function storeDB(Request $request)
    {
       $userID=$request->user_id; 
       $featureId = $request->input('featureId');
        // Validate the request chang
        $validated = $request->validate([
           'changeable_type' => 'required|string|in:App\Models\PurchaseOrder,App\Models\User,App\Models\Client',
            'changeable_id' => 'required|integer',
            'notes' => 'required|string|max:500',
            'changes' => 'required|json',
            'client_id' => 'sometimes|nullable|integer|exists:clients,id'
        ], [
            'changeable_type.required' => 'Please select what you want to change',
            'changeable_id.required' => 'Invalid reference ID',
            'notes.required' => 'Please provide a reason for the change',
            'notes.max' => 'Reason must be less than 500 characters',
            'changes.required' => 'Please specify the changes',
            'changes.json' => 'Invalid changes format',
            'client_id.exists' => 'The selected client does not exist'
        ]);

     
        // Check if user has permission to create request changes
        if (!FeatureAccess::canCreateById($userID,$featureId)) {
            abort(403, 'Unauthorized action.');
        }
       
        // Validate if referenced model exists
        $model = $validated['changeable_type']::find($validated['changeable_id']);
       
        if (!$model) {
            return back()->withInput()->with('error', 'The referenced record does not exist.');
        }
        
        //get Client's Name
        $client = \App\Models\Client::find($validated['client_id']);
        if (!$client) {
            return back()->withInput()->with('error', 'The referenced client does not exist.');
        }
        $clientController = new \App\Http\Controllers\ClientController();
        $clientData = $clientController->getClientDetails($client)->getData();
      
        // Prepare data for insertion
        
        try {
            $insertData = [
                ...$validated,
                'title' => $request->input('title'),
                'category' => $request->input('category'),
                'client_id' => $validated['client_id'] ?? null,
                'client_name' => $clientData->company_name,
                'changeable_code'=>$request->input('changeable_code'),
                'status' => 'pending',
                'created_by' => Auth::id(),
                'changes' => $validated['changes'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $this->log('Attempting to insert request change with data:', ['data' => $insertData]);
            
            try {
                $success = DB::table('request_changes')->insert($insertData);
                $this->log('Insert operation result:', ['success' => $success]);
                
                if (!$success) {
                    $this->log('Failed to insert request change', ['error' => true]);
                    throw new \Exception('Failed to insert request change');
                }
                
                $this->log('Request change inserted successfully');
            } catch (\Exception $insertError) {
                $this->log('Exception during insert operation:', ['error' => $insertError->getMessage(), 'error_type' => 'exception']);
                throw $insertError;
            }
            return true;
        } catch (\Exception $e) {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Failed to submit change request: ' . $e->getMessage());
        }
    }

    public function show(RequestChange $requestChange)
    {
        return view('request-changes.show', compact('requestChange'));
    }

    public function approve(Request $request, RequestChange $requestChange)
    {
        if (!FeatureAccess::canEdit('request_changes') || $requestChange->status !== 'pending') {
            abort(403, 'Unauthorized action or invalid status.');
        }

        try {
            DB::transaction(function () use ($requestChange) {
                $requestChange->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now()
                ]);

                // Apply changes to the changeable model
                $model = $requestChange->changeable_type::find($requestChange->changeable_id);
                $model->update($requestChange->changes);
            });

            return redirect()->route('request-changes.index')
                ->with('success', 'Request change approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, RequestChange $requestChange)
    {
        if (!FeatureAccess::canEdit('request_changes') || $requestChange->status !== 'pending') {
            abort(403, 'Unauthorized action or invalid status.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $requestChange->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'notes' => $validated['rejection_reason']
            ]);

            return redirect()->route('request-changes.index')
                ->with('success', 'Request change rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    public function archive(RequestChange $requestChange)
    {
        if (!FeatureAccess::canEdit('request_changes')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $requestChange->update([
                'is_archived' => true,
                'archived_at' => now(),
                'original_status' => $requestChange->status
            ]);

            return redirect()->route('request-changes.index')
                ->with('success', 'Request change archived successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to archive request: ' . $e->getMessage());
        }
    }

    public function unarchive(RequestChange $requestChange)
    {
        if (!FeatureAccess::canEdit('request_changes')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $requestChange->update([
                'is_archived' => false,
                'archived_at' => null
            ]);

            return redirect()->route('request-changes.index')
                ->with('success', 'Request change unarchived successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to unarchive request: ' . $e->getMessage());
        }
    }
}