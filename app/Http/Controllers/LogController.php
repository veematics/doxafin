<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LogController extends Controller
{
    /**
     * Display a listing of logs
     */
    public function index(Request $request): View
    {
        $query = Log::query()->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('user')) {
            $query->byUser($request->user);
        }

        if ($request->filled('object_id')) {
            $query->byObject($request->object_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        return view('logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new log
     */
    public function create(): View
    {
        return view('logs.create');
    }

    /**
     * Store a newly created log
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'logCategory' => 'required|string|max:50',
            'logAction' => 'required|string|max:50',
            'logObjID' => 'required|integer|min:0',
            'logNotes' => 'required|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $log = Log::create([
                'logCategory' => $request->logCategory,
                'logAction' => $request->logAction,
                'logObjID' => $request->logObjID,
                'logBy' => Auth::user()->name ?? 'System',
                'logNotes' => $request->logNotes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log created successfully',
                'data' => $log
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified log
     */
    public function show(Log $log): View
    {
        return view('logs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified log
     */
    public function edit(Log $log): View
    {
        return view('logs.edit', compact('log'));
    }

    /**
     * Update the specified log
     */
    public function update(Request $request, Log $log): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'logCategory' => 'sometimes|required|string|max:50',
            'logAction' => 'sometimes|required|string|max:50',
            'logObjID' => 'sometimes|required|integer|min:0',
            'logNotes' => 'sometimes|required|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $log->update($request->only([
                'logCategory',
                'logAction',
                'logObjID',
                'logNotes'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Log updated successfully',
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified log
     */
    public function destroy(Log $log): JsonResponse
    {
        try {
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Log deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logs for a specific object
     */
    public function getObjectLogs(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:50',
            'object_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $logs = Log::byCategory($request->category)
                   ->byObject($request->object_id)
                   ->orderBy('created_at', 'desc')
                   ->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Helper method to create log entry
     */
    public static function createLog($category, $action, $objId, $notes, $user = null)
    {
        try {
            return Log::create([
                'logCategory' => $category,
                'logAction' => $action,
                'logObjID' => $objId,
                'logBy' => $user ?? (Auth::user()->name ?? 'System'),
                'logNotes' => $notes,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create log entry: ' . $e->getMessage());
            return null;
        }
    }
}