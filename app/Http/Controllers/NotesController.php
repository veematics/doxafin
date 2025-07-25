<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Store a newly created note in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'notesCategory' => 'required|string|max:50',
                'notesObjID' => 'required|integer',
                'notesTitle' => 'required|string|max:50',
                'notesSummary' => 'nullable|string',
                'notesDetail' => 'nullable|string',
                'notesBy' => 'required|string|max:50'
            ]);

            // Create the note
            $note = Note::create([
                'notesCategory' => $validated['notesCategory'],
                'notesObjID' => $validated['notesObjID'],
                'notesTitle' => $validated['notesTitle'],
                'notesSummary' => $validated['notesSummary'],
                'notesDetail' => $validated['notesDetail'],
                'notesBy' => $validated['notesBy'],
                'notesHide' => 0 // Default to visible
            ]);

            // Create log entry for note creation
            $this->createNoteCreationLog($note);

            return response()->json([
                'success' => true,
                'message' => 'Note created successfully',
                'note' => $note
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note): JsonResponse
    {
        return response()->json([
            'success' => true,
            'note' => $note
        ]);
    }

    /**
     * Update the specified note in storage.
     */
    public function update(Request $request, Note $note): JsonResponse
    {
        try {
            $validated = $request->validate([
                'notesTitle' => 'sometimes|required|string|max:50',
                'notesSummary' => 'nullable|string',
                'notesDetail' => 'nullable|string',
                'notesHide' => 'sometimes|boolean'
            ]);

            // Store original values for logging
            $originalTitle = $note->notesTitle;
            $changes = [];
            
            if (isset($validated['notesTitle']) && $validated['notesTitle'] !== $note->notesTitle) {
                $changes[] = "Title: '{$note->notesTitle}' → '{$validated['notesTitle']}'";
            }
            if (isset($validated['notesSummary']) && $validated['notesSummary'] !== $note->notesSummary) {
                $changes[] = "Summary updated";
            }
            if (isset($validated['notesDetail']) && $validated['notesDetail'] !== $note->notesDetail) {
                $changes[] = "Detail updated";
            }
            if (isset($validated['notesHide']) && $validated['notesHide'] !== $note->notesHide) {
                $status = $validated['notesHide'] ? 'hidden' : 'visible';
                $changes[] = "Status changed to {$status}";
            }

            $note->update($validated);

            // Create log entry for note update or deletion
            if (!empty($changes)) {
                // Check if this is a soft delete (notesHide set to 1)
                if (isset($validated['notesHide']) && $validated['notesHide'] == 1) {
                    // This is a soft delete, log as deletion
                    $this->createNoteDeleteLog($note->notesTitle, $note->notesID, $note->notesObjID);
                } else {
                    // This is a regular update
                    $this->createNoteUpdateLog($note);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Note updated successfully',
                'note' => $note
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified note from storage (soft delete).
     */
    public function destroy(Note $note): JsonResponse
    {
        try {
            // Store note info for logging before soft deletion
            $noteTitle = $note->notesTitle;
            $noteID = $note->notesID;
            $noteObjID = $note->notesObjID;
            
            // Soft delete by setting notesHide to 1
            $note->update(['notesHide' => 1]);

            // Create log entry for note deletion
            $this->createNoteDeleteLog($noteTitle, $noteID, $noteObjID);

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a log entry for note creation (specific requirements)
     */
    private function createNoteCreationLog($note)
    {
        try {
            $log = new Log();
            $log->logCategory = 'Purchase Order'; // PurchaseOrder category
            $log->logAction = 'Create';   // Create action
            $log->logObjID = $note->notesObjID; // Use Purchase Order ID
            $log->logBy = Auth::user()->name;
            $log->logNotes = 'Notes "' . $note->notesTitle . '" (ID: ' . $note->notesID . ')';
            $log->save();
        } catch (\Exception $e) {
            \Log::error('Failed to create note creation log entry: ' . $e->getMessage());
        }
    }

    /**
     * Create a log entry for note update (specific requirements)
     */
    private function createNoteUpdateLog($note)
    {
        try {
            $log = new Log();
            $log->logCategory = 'Purchase Order'; // PurchaseOrder category
            $log->logAction = 'Update';   // Update action
            $log->logObjID = $note->notesObjID; // Use Purchase Order ID
            $log->logBy = Auth::user()->name;
            $log->logNotes = 'Notes "' . $note->notesTitle . '" (ID: ' . $note->notesID . ')';
            $log->save();
        } catch (\Exception $e) {
            \Log::error('Failed to create note update log entry: ' . $e->getMessage());
        }
    }

    /**
     * Create a log entry for note deletion (specific requirements)
     */
    private function createNoteDeleteLog($noteTitle, $noteID, $noteObjID)
    {
        try {
            $log = new Log();
            $log->logCategory = 'Purchase Order'; // PurchaseOrder category
            $log->logAction = 'Delete';   // Delete action
            $log->logObjID = $noteObjID; // Use Purchase Order ID
            $log->logBy = Auth::user()->name;
            $log->logNotes = 'Notes "' . $noteTitle . '" (ID: ' . $noteID . ')';
            $log->save();
        } catch (\Exception $e) {
            \Log::error('Failed to create note deletion log entry: ' . $e->getMessage());
            // Don't throw the exception to avoid breaking the delete operation
        }
    }

    /**
     * Create a log entry for note operations (update/delete)
     */
    private function createNoteLog($category, $objId, $action, $notes, $user)
    {
        try {
            // Map note categories to log categories
            $logCategory = $this->mapNoteCategoryToLogCategory($category);
            
            // Map actions to log action codes
            $logActionMap = [
                'create' => 'Create', // Create
                'update' => 'Update', // Update
                'delete' => 'Delete'  // Delete
            ];
            
            $logAction = $logActionMap[$action] ?? 'Other'; // Default to 'Other'
            
            Log::create([
                'logCategory' => $logCategory,
                'logAction' => $logAction,
                'logObjID' => $objId,
                'logBy' => $user,
                'logNotes' => $notes,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create note log entry: ' . $e->getMessage());
        }
    }

    /**
     * Map note categories to log categories
     */
    private function mapNoteCategoryToLogCategory($noteCategory)
    {
        // All notes will be logged under the Notes category
        // regardless of their specific category (PurchaseOrder, Invoice, etc.)
        return 'Notes'; // Notes category
    }
}