<?php

namespace App\Farmers\Controllers;

use App\Farmers\Services\MessageService;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommunicationController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index()
    {
        try {
            $processors = Company::where('company_id', '!=', Auth::user()->company_id)->get();
            $messages = $this->messageService->getAll();
            return view('farmers.communication.index', compact('processors', 'messages'));
        } catch (\Exception $e) {
            Log::error('Communication index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading messages.');
        }
    }

    public function send(Request $request)
    {
        try {
            $this->messageService->create($request->all());
            return redirect()->route('farmers.communication.index')->with('success', 'Message sent successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Message send error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while sending the message.')->withInput();
        }
    }
}