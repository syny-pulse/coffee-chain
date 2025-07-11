<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Company;
use App\Models\Message;
use App\Models\FarmerOrder;
use App\Models\User;

class CommunicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        // Get processors the farmer has worked with (via messages or orders)
       $processorIdsFromMessages = Message::where('sender_company_id', $company->company_id)
            ->orWhere('receiver_company_id', $company->company_id)
            ->pluck('sender_company_id', 'receiver_company_id')
            ->flatten()
            ->unique()
            ->filter(fn($id) => $id !== $company->company_id)
            ->values();
        $processorIdsFromOrders = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->pluck('processor_company_id')
            ->unique()
            ->filter();
        $processorIds = $processorIdsFromMessages->merge($processorIdsFromOrders)->unique();
        $processors = Company::where('company_type', 'processor')
            ->whereIn('company_id', $processorIds)
            ->get();
        // Split messages into inbox and sent
        $inboxMessages = Message::where('receiver_company_id', $company->company_id)
            ->orderByDesc('created_at')->get();
        $sentMessages = Message::where('sender_company_id', $company->company_id)
            ->orderByDesc('created_at')->get();
        return view('farmers.communication.index', compact('inboxMessages', 'sentMessages', 'processors'));
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$user || !$company) {
            Log::warning('Unauthenticated attempt to send message');
            return redirect()->route('login')->with('error', 'Please log in to send messages.');
        }

        // Validate the request
        $request->validate([
            'processor_id' => 'required|integer|exists:companies,company_id',
            'content' => 'required|string|max:1000',
            'subject' => 'nullable|string|max:200', // Optional, with default
        ]);

        try {
            // Find an active user for the receiver company
            $receiverUser = User::where('company_id', $request->processor_id)
                ->where('status', 'active')
                ->first();

            if (!$receiverUser) {
                Log::warning('No active user found for receiver_company_id: ' . $request->processor_id);
                return redirect()->route('farmers.communication.index')
                    ->with('error', 'Cannot send message: No active user found for the selected processor.');
            }

            // Create the message
            Message::create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $receiverUser->id,
                'sender_company_id' => $company->company_id,
                'receiver_company_id' => $request->processor_id,
                'subject' => $request->input('subject', 'Message from Farmer'),
                'message_body' => $request->content,
                'message_type' => 'general',
                'is_read' => false,
            ]);

            Log::info('Message sent from user ID: ' . $user->id . ' to company ID: ' . $request->processor_id);
            return redirect()->route('farmers.communication.index')
                ->with('success', 'Message sent successfully to the processor.');
        } catch (\Exception $e) {
            Log::error('Failed to send message: ' . $e->getMessage());
            return redirect()->route('farmers.communication.index')
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

        $processor = Company::find($message->sender_company_id == $company->company_id ? $message->receiver_company_id : $message->sender_company_id);

        return response()->json([
            'processor' => $processor,
            'original_message' => $message,
        ]);
    }
}
