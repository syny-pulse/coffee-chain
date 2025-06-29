<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Message;

class CommunicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        // Get all processors
        $processors = Company::where('company_type', 'processor')->get();
        // Get all messages where this farmer is sender or receiver
        $messages = Message::where(function($q) use ($company) {
            $q->where('sender_company_id', $company->company_id)
              ->orWhere('receiver_company_id', $company->company_id);
        })->orderByDesc('created_at')->get();
        return view('farmers.communication.index', compact('messages', 'processors'));
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'processor_id' => 'required|integer|exists:companies,company_id',
            'content' => 'required|string|max:1000',
        ]);
        Message::create([
            'sender_user_id' => $user->id,
            'receiver_user_id' => null,
            'sender_company_id' => $company->company_id,
            'receiver_company_id' => $request->processor_id,
            'subject' => $request->input('subject', 'Message from Farmer'),
            'message_body' => $request->content,
            'message_type' => 'general',
            'is_read' => false,
        ]);
        return redirect()->route('farmers.communication.index')
            ->with('success', 'Message sent successfully to the processor.');
    }
}