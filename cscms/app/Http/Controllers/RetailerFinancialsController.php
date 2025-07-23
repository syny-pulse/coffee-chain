<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class RetailerFinancialsController extends Controller
{
    public function index()
    {
        // Financial dashboard
        return view('retailers.financials.index');
    }

    public function invoices(Request $request)
    {
        $query = Invoice::query();
        if ($request->filled('customer')) {
            $query->where('customer', 'like', '%' . $request->customer . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        $invoices = $query->orderByDesc('date')->get();
        return view('retailers.financials.invoices', [
            'invoices' => $invoices,
            'filters' => $request->only(['customer','status','date_from','date_to','min_amount','max_amount'])
        ]);
    }

    public function payments(Request $request)
    {
        $query = Payment::query();
        if ($request->filled('payer')) {
            $query->where('payer', 'like', '%' . $request->payer . '%');
        }
        if ($request->filled('payee')) {
            $query->where('payee', 'like', '%' . $request->payee . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        $payments = $query->orderByDesc('date')->get();
        return view('retailers.financials.payments', [
            'payments' => $payments,
            'filters' => $request->only(['payer','payee','status','date_from','date_to','min_amount','max_amount'])
        ]);
    }

    public function profitLoss()
    {
        // Profit/loss statement
        return view('retailers.financials.profit_loss');
    }

    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:invoices,number',
            'date' => 'required|date',
            'customer' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:unpaid,paid',
            'due_date' => 'nullable|date',
        ]);
        \App\Models\Invoice::create($validated);
        return redirect()->route('retailer.financials.invoices')->with('success', 'Invoice created.');
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'payer' => 'required|string|max:255',
            'payee' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'method' => 'nullable|string|max:255',
            'status' => 'required|in:completed,pending,refunded',
            'invoice_id' => 'nullable|exists:invoices,id',
        ]);
        \App\Models\Payment::create($validated);
        return redirect()->route('retailer.financials.payments')->with('success', 'Payment recorded.');
    }

    public function markInvoicePaid($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        $invoice->status = 'paid';
        $invoice->paid_at = now();
        $invoice->save();
        return redirect()->route('retailer.financials.invoices')->with('success', 'Invoice marked as paid.');
    }

    public function showInvoice($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        return view('retailers.financials.show_invoice', compact('invoice'));
    }

    public function exportInvoices()
    {
        $invoices = \App\Models\Invoice::all();
        $csv = "number,date,customer,amount,status,due_date,paid_at\n";
        foreach ($invoices as $inv) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s"\n',
                $inv->number,
                $inv->date,
                $inv->customer,
                $inv->amount,
                $inv->status,
                $inv->due_date,
                $inv->paid_at
            );
        }
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoices.csv"',
        ]);
    }

    public function downloadInvoicePdf($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        $pdf = Pdf::loadView('retailers.financials.invoice_pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->number . '.pdf');
    }
} 