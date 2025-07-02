<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of messages.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->company) {
            return redirect()->route('login')->with('error', 'Please log in to view messages.');
        }

        // Get messages for the current user's company
        $messages = Message::where('receiver_company_id', $user->company->company_id)
                       ->orWhere('sender_company_id', $user->company->company_id)
                       ->latest()
                       ->get();

        return view('processor.message.index', compact('messages'));
    }

    /**
     * Show the form for creating a new message.
     */
    public function create()
    {
       // Fetch companies of type farmer or retailer with acceptance_status 'accepted'
    $companies = Company::whereIn('company_type', ['farmer', 'retailer'])
                        ->where('acceptance_status', 'accepted')
                        ->get();

    // Fetch users who have associated companies and have status 'active'
    $users = User::has('company')
                 ->where('status', 'active')
                 ->get();

        return view('processor.message.create', compact('companies', 'users'));
    }

    /**
     * Store a newly created message in the database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company) {
            return redirect()->route('login')->with('error', 'Please log in to send messages.');
        }

        // Validate the request
        $validated = $request->validate([
            'receiver_company_id' => 'required|exists:companies,company_id',
            'receiver_user_id' => 'nullable|exists:users,id',
            'subject' => 'nullable|string|max:200',
            'message_body' => 'required|string',
            'message_type' => 'required|in:general,order_inquiry,quality_feedback,delivery_update,system_notification',
        ]);

        // Create the message
        Message::create([
            'sender_company_id' => $user->company->company_id,
            'sender_user_id' => $user->id,
            'receiver_company_id' => $validated['receiver_company_id'],
            'receiver_user_id' => $validated['receiver_user_id'] ?? null,
            'subject' => $validated['subject'],
            'message_body' => $validated['message_body'],
            'message_type' => $validated['message_type'],
            'is_read' => false,
        ]);

        // Redirect back to message index with success message
        return redirect()->route('processor.message.index')->with('success', 'Message sent successfully!');
    }

    /**
     * Display a single message.
     */
    public function show(Message $message)
    {
        $user = Auth::user();
        if (!$user || !$user->company) {
            return redirect()->route('login')->with('error', 'Please log in to view messages.');
        }

        // Ensure the authenticated user's company is either the sender or receiver
        if ($message->sender_company_id !== $user->company->company_id &&
            $message->receiver_company_id !== $user->company->company_id) {
            abort(403, 'Unauthorized access to this message.');
        }

        // Mark the message as read if the user is the receiver
        if ($message->receiver_company_id === $user->company->company_id && !$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('processor.message.show', compact('message'));
    }
}
