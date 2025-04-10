<?php

namespace App\Http\Controllers;

use App\Models\InboxMessage;
use App\Models\MessageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class InboxMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = InboxMessage::with(['sender', 'replies'])
            ->forUser(Auth::id())
            ->whereNull('deleted_at'); // Exclude trashed messages

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority_status', $request->priority);
        }

        if ($request->filled('sender')) {
            $query->where('sent_from', $request->sender);
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        $users = User::where('id', '!=', Auth::id())->get();
        
        $unreadCount = InboxMessage::where('sent_to', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at') // Exclude trashed messages
            ->count();
            
        // Change this to count only unread sent messages instead of all sent messages
        $unreadSentCount = InboxMessage::where('sent_from', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at') // Exclude trashed messages
            ->count();
        
        $trashCount = InboxMessage::where(function($query) {
                $query->where('sent_to', Auth::id())
                    ->orWhere('sent_from', Auth::id());
            })
            ->whereNotNull('deleted_at')
            ->count();
        
        // Update the variable name in the view parameters
        return view('inbox.index', compact('messages', 'users', 'unreadCount', 'unreadSentCount', 'trashCount'));
    }

    public function sent(Request $request)
    {
        $query = InboxMessage::where('sent_from', Auth::id())
            ->whereNull('deleted_at') // Exclude trashed messages
            ->with(['recipient', 'replies']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority_status', $request->priority);
        }

        if ($request->filled('sender')) {
            $query->where('sent_to', $request->sender);
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        $users = User::where('id', '!=', Auth::id())->get();
        
        $unreadCount = InboxMessage::where('sent_to', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at') // Exclude trashed messages
            ->count();
            
        // Change variable name to unreadSentCount to match other methods
        $unreadSentCount = InboxMessage::where('sent_from', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at') // Exclude trashed messages
            ->count();
        
        $trashCount = InboxMessage::where(function($query) {
                $query->where('sent_to', Auth::id())
                    ->orWhere('sent_from', Auth::id());
            })
            ->whereNotNull('deleted_at')
            ->count();
        
        // Update to use unreadSentCount instead of sentCount
        return view('inbox.index', compact('messages', 'users', 'unreadCount', 'unreadSentCount', 'trashCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sent_to' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority_status' => 'required|in:1,2,3',
            'message_category' => 'required|string|max:50' // Changed from 'sometimes' to 'required'
        ]);
    
        $message = new InboxMessage([
            'sent_to' => $validated['sent_to'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority_status' => $validated['priority_status'],
            'message_category' => $validated['message_category'] // Removed default value since it's now required
        ]);
    
        $message->sent_from = auth()->id();
        $message->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully'
        ]);
    }

    

    public function reply(Request $request, InboxMessage $message)
    {
        if (!$message->canBeRepliedTo()) {
            return back()->with('error', 'Cannot reply to system messages');
        }
    
        $validated = $request->validate([
            'message' => 'required|string'
        ]);
    
        $reply = InboxMessage::create([
            'subject' => 'Re: ' . $message->subject,
            'message' => $validated['message'],
            'message_parent_id' => $message->id,
            'sent_from' => Auth::id(),
            'sent_to' => $message->sent_from,
            'priority_status' => $message->priority_status,
            'message_category' => $message->message_category
        ]);
    
        // Check if we should redirect back to the thread
        if ($request->has('redirect_to_thread')) {
            // Find the root message to maintain the thread view
            $rootMessage = $message;
            while ($rootMessage->message_parent_id) {
                $rootMessage = InboxMessage::find($rootMessage->message_parent_id);
            }
            
            // Redirect with query parameter instead of with() session flash
            return redirect()->route('inbox.thread', ['message' => $rootMessage->id, 'reply' => 1]);
        }
    
        // Regular redirect to inbox with reply parameter
        return redirect()->route('inbox.index', ['reply' => 1]);
    }

    public function destroy(InboxMessage $message)
    {
        if (!$message->canBeDeleted()) {
            return back()->with('error', 'Cannot delete read messages');
        }

        $message->delete();
        return redirect()->route('inbox.index')
            ->with('success', 'Message deleted successfully');
    }

    public function moveToTrash(InboxMessage $message)
    {
        try {
            // Update the deleted_at timestamp
            $message->deleted_at = now();
            $message->save();
            
            // Return simple success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    public function replyForm(InboxMessage $message)
    {
        // Check permissions
        if ($message->sent_to !== Auth::id() && $message->sent_from !== Auth::id()) {
            abort(403, 'You do not have permission to reply to this message.');
        }
    
        // Get the user we're replying to
        $recipient = null;
        if (Auth::id() === $message->sent_to) {
            $recipient = $message->sender;
        } else {
            $recipient = $message->recipient;
        }
    
        return view('inbox.reply', [
            'message' => $message,
            'recipient' => $recipient
        ]);
    }

    public function thread(InboxMessage $message)
    {
        // Check permissions
        if ($message->sent_to !== Auth::id() && $message->sent_from !== Auth::id()) {
            abort(403, 'You do not have permission to view this thread.');
        }
    
        // Find the root message by traversing up the parent chain
        $rootMessage = $message;
        while ($rootMessage->message_parent_id) {
            $rootMessage = InboxMessage::find($rootMessage->message_parent_id);
        }
    
        // Recursively get all messages in the thread hierarchy
        $messages = collect();
        $this->getThreadMessages($rootMessage, $messages);
    
        return view('inbox.thread', [
            'rootMessage' => $rootMessage,
            'messages' => $messages->sortBy('created_at'),
            'users' => User::all(),
            'isPersonal' => $rootMessage->message_category === 'personal' // Add this line
        ]);
    }
    
    protected function getThreadMessages(InboxMessage $message, &$messages)
    {
        $messages->push($message);
        
        // Get direct replies
        $replies = InboxMessage::where('message_parent_id', $message->id)
            ->with(['sender', 'recipient'])
            ->get();
            
        // Recursively process each reply
        foreach ($replies as $reply) {
            $this->getThreadMessages($reply, $messages);
        }
    }
    
    public function trash(Request $request)
    {
        $query = InboxMessage::where(function($query) {
                $query->where('sent_to', Auth::id())
                    ->orWhere('sent_from', Auth::id());
            })
            ->whereNotNull('deleted_at')
            ->with(['sender', 'recipient', 'replies']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $messages = $query->orderBy('deleted_at', 'desc')
            ->paginate($request->get('per_page', 20));

        $users = User::where('id', '!=', Auth::id())->get();
        
        $unreadCount = InboxMessage::where('sent_to', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at')
            ->count();
            
        // Update to use unreadSentCount like in other methods
        $unreadSentCount = InboxMessage::where('sent_from', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at')
            ->count();
        
        $trashCount = InboxMessage::where(function($query) {
                $query->where('sent_to', Auth::id())
                    ->orWhere('sent_from', Auth::id());
            })
            ->whereNotNull('deleted_at')
            ->count();
        
        // Fix the view name - should be 'inbox.index' not 'inbox.show'
        return view('inbox.index', [
            'messages' => $messages,
            'users' => $users,
            'unreadCount' => $unreadCount,
            'unreadSentCount' => $unreadSentCount,
            'trashCount' => $trashCount,
            'view_type' => 'trash'
        ]);
    }
    
    /**
     * Recover a message from trash
     */
    public function recoverMessage(InboxMessage $message)
    {
        try {
            // Clear the deleted_at timestamp
            $message->deleted_at = null;
            $message->save();
            
            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }
    
   // Add this to the show method in InboxMessageController

    // Fix the show method to use the correct model and field names
    public function show(InboxMessage $message)
    {
        // Check if the message belongs to the current user
        if ($message->sent_to == auth()->id()) {
            // Mark the message as read if it's not already read
            if (!$message->is_read) {
                $message->is_read = 1;
                $message->save();
            }
        }
        
        // Get related users
        $users = User::where('id', '!=', Auth::id())->get();
        
        // Get counts for sidebar
        $unreadCount = InboxMessage::where('sent_to', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at')
            ->count();
            
        $unreadSentCount = InboxMessage::where('sent_from', Auth::id())
            ->where('is_read', false)
            ->whereNull('deleted_at')
            ->count();
        
        $trashCount = InboxMessage::where(function($query) {
                $query->where('sent_to', Auth::id())
                    ->orWhere('sent_from', Auth::id());
            })
            ->whereNotNull('deleted_at')
            ->count();
        
        // Update to pass unreadSentCount instead of sentCount
        return view('inbox.show', compact('message', 'users', 'unreadCount', 'unreadSentCount', 'trashCount'));
    }

    // Fix the markAsRead method to use the correct model and field names
    public function markAsRead(InboxMessage $message)
    {
        // Verify that the current user is the recipient
        if ($message->sent_to !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->is_read = 1;
        $message->save();
        
        return response()->json(['success' => true]);
    }
} // Class closing brace