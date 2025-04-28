<?php

namespace App\Http\Controllers;

use App\Models\RequestChange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestChangeController extends Controller
{
    public function index()
    {
        $requestChanges = RequestChange::with(['creator', 'approver', 'changeable'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('request-changes.index', compact('requestChanges'));
    }

    public function create()
    {
        return view('request-changes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'changeable_type' => 'required|string',
            'changeable_id' => 'required|integer',
            'notes' => 'required|string|max:500',
            'changes' => 'required|json'
        ]);

        if (!FeatureAccess::canCreate('request_changes')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $requestChange = RequestChange::create([
                ...$validated,
                'status' => 'pending',
                'created_by' => Auth::id()
            ]);

            return redirect()->route('request-changes.index')
                ->with('success', 'Request change submitted successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to submit request: ' . $e->getMessage());
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
}