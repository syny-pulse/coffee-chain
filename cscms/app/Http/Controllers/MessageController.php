<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $role = $user->user_type;

        // Determine possible recipients based on role
        if ($role === 'farmer') {
            $recipientType = 'processor';
            $recipientIds = Message::where('sender_company_id', $company->company_id)
                ->orWhere('receiver_company_id', $company->company_id)
                ->pluck('sender_company_id', 'receiver_company_id')
                ->flatten()
                ->unique()
                ->filter(fn($id) => $id !== $company->company_id)
                ->values();
            $recipientCompanies = Company::where('company_type', $recipientType)
                ->whereIn('company_id', $recipientIds)
                ->get();
        } elseif ($role === 'processor') {
            $recipientCompanies = Company::whereIn('company_type', ['farmer', 'retailer'])
                ->where('acceptance_status', 'accepted')
                ->get();
        } elseif ($role === 'retailer') {
            $recipientCompanies = Company::where('company_type', 'processor')
                ->where('acceptance_status', 'accepted')
                ->get();
        } else {
            $recipientCompanies = collect();
        }

        // Split messages into inbox and sent
        $inboxMessages = Message::where('receiver_company_id', $company->company_id)
            ->orderByDesc('created_at')->get();
        $sentMessages = Message::where('sender_company_id', $company->company_id)
            ->orderByDesc('created_at')->get();

        return view('messages.index', compact('inboxMessages', 'sentMessages', 'recipientCompanies', 'role'));
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $role = $user->user_type;

        if (!$user || !$company) {
            Log::warning('Unauthenticated attempt to send message');
            return redirect()->route('login')->with('error', 'Please log in to send messages.');
        }

        // Validate the request
        $request->validate([
            'recipient_company_id' => 'required|integer|exists:companies,company_id',
            'content' => 'required|string|max:1000',
            'subject' => 'nullable|string|max:200',
        ]);

        try {
            // Find an active user for the receiver company
            $receiverUser = User::where('company_id', $request->recipient_company_id)
                ->where('status', 'active')
                ->first();

            if (!$receiverUser) {
                Log::warning('No active user found for receiver_company_id: ' . $request->recipient_company_id);
                return redirect()->route('messages.index')
                    ->with('error', 'Cannot send message: No active user found for the selected company.');
            }

            // Create the message
            Message::create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $receiverUser->id,
                'sender_company_id' => $company->company_id,
                'receiver_company_id' => $request->recipient_company_id,
                'subject' => $request->input('subject', 'Message'),
                'message_body' => $request->content,
                'message_type' => 'general',
                'is_read' => false,
            ]);

            Log::info('Message sent from user ID: ' . $user->id . ' to company ID: ' . $request->recipient_company_id);
            return redirect()->route('messages.index')
                ->with('success', 'Message sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send message: ' . $e->getMessage());
            return redirect()->route('messages.index')
                ->with('error', 'Failed to send message.');
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $message = Message::where('message_id', $id)
            ->where(function($q) use ($company) {
                $q->where('sender_company_id', $company->company_id)
                  ->orWhere('receiver_company_id', $company->company_id);
            })->firstOrFail();

        // Mark as read if receiver is the current company
        if ($message->receiver_company_id == $company->company_id && !$message->is_read) {
            $message->is_read = true;
            $message->save();
        }

        return response()->json([
            'message' => $message,
            'sender' => optional($message->senderCompany)->company_name,
            'receiver' => optional($message->receiverCompany)->company_name,
        ]);
    }

    public function replyForm($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $message = Message::where('message_id', $id)
            ->where(function($q) use ($company) {
                $q->where('sender_company_id', $company->company_id)
                  ->orWhere('receiver_company_id', $company->company_id);
            })->firstOrFail();

        $recipient = Company::find($message->sender_company_id == $company->company_id ? $message->receiver_company_id : $message->sender_company_id);

        return response()->json([
            'recipient' => $recipient,
            'original_message' => $message,
        ]);
    }

    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        if (!$company) {
            return redirect()->route('messages.index')->with('error', 'No company found for your account.');
        }
        \App\Models\Message::where('receiver_company_id', $company->company_id)
            ->update(['is_read' => true]);
        return redirect()->route('messages.index')->with('success', 'All messages marked as read.');
    }
} 