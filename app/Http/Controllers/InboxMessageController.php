<?php

namespace App\Http\Controllers;

use App\Models\InboxMessage;
use App\Models\MessageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class InboxMessageController extends Controller
{
    public function index()
    {
        $messages = InboxMessage::forUser(Auth::id())
            ->with(['sender', 'replies'])
            ->when(request('search'), function($query) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        $users = User::where('id', '!=', Auth::id())->get();

        return view('inbox.index', compact('messages', 'users'));
    }

    public function sent()
    {
        $messages = InboxMessage::where('sent_from', Auth::id())
            ->with(['recipient', 'replies'])
            ->when(request('search'), function($query) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        $users = User::where('id', '!=', Auth::id())->get();

        return view('inbox.index', [
            'messages' => $messages,
            'users' => $users,
            'view_type' => 'sent'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sent_to' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority_status' => 'required|in:1,2,3'
        ]);

        $message = InboxMessage::create([
            'sent_from' => Auth::id(),
            'sent_to' => $validated['sent_to'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority_status' => $validated['priority_status'],
            'is_read' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully'
        ]);
    }

    public function show(InboxMessage $message)
    {
        if ($message->sent_to !== Auth::id()) {
            abort(403);
        }

        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('inbox.show', compact('message'));
    }

    public function reply(Request $request, InboxMessage $message)
    {
        if (!$message->canBeRepliedTo()) {
            return back()->with('error', 'Cannot reply to system messages');
        }

        $validated = $request->validate([
            'message' => 'required|string'
        ]);

        InboxMessage::create([
            'subject' => 'Re: ' . $message->subject,
            'message' => $validated['message'],
            'message_parent_id' => $message->id,
            'sent_from' => Auth::id(),
            'sent_to' => $message->sent_from,
            'priority_status' => 'normal'
        ]);

        return redirect()->route('inbox.show', $message)
            ->with('success', 'Reply sent successfully');
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
}