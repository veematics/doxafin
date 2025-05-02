<?php

namespace App\Http\Controllers;

use App\Models\RequestChange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FeatureAccess;

class RequestChangeController extends Controller
{
    public function index()
    {
        $requestChanges = RequestChange::with(['creator', 'approver', 'changeable'])
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $archivedChanges = RequestChange::with(['creator', 'approver', 'changeable', 'client'])
            ->where('is_archived', true)
            ->orderBy('archived_at', 'desc')
            ->paginate(10);

        $clients = \App\Models\Client::orderBy('company_name')->get();

        return view('request-changes.index', compact('requestChanges', 'archivedChanges', 'clients'));
    }

    public function create()
    {
        return view('request-changes.create');
    }

    public function storeDB(Request $request)
    {
       $userID=$request->user_id; 
       $featureId = $request->input('featureId');
        // Validate the request chang
        $validated = $request->validate([
           'changeable_type' => 'required|string|in:App\Models\PurchaseOrder,App\Models\User,App\Models\Client',
            'changeable_id' => 'required|integer',
            'notes' => 'required|string|max:500',
            'changes' => 'required|json'
        ], [
            'changeable_type.required' => 'Please select what you want to change',
            'changeable_id.required' => 'Invalid reference ID',
            'notes.required' => 'Please provide a reason for the change',
            'notes.max' => 'Reason must be less than 500 characters',
            'changes.required' => 'Please specify the changes',
            'changes.json' => 'Invalid changes format'
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

       
        try {
            $requestChange = RequestChange::create([
                ...$validated,
                'status' => 'pending',
                'created_by' => Auth::id(),
                'data' =>($validated['changes']
            ]);

            return redirect()->route('purchase-orders.index')
                ->with('success', 'Change request submitted successfully!');
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