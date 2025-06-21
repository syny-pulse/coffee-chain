<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Customer;
// use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;



class InvoiceController extends Controller
{
    public function InvoiceAll(){
        // $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();

                $allData = DB::table('invoice_details')
            ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->join('products', 'invoice_details.product_id', '=', 'products.id')
            ->select(
                'invoices.id as invoice_id',
                'invoices.invoice_no',
                'invoices.date',
                'customers.name as customer_name',
                DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'), // Combine product names
                DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities
                DB::raw('GROUP_CONCAT(CONCAT("Ugx ", FORMAT(invoice_details.unit_price, 0)) SEPARATOR ", ") as prices'), // Combine prices
                'payments.discount_amount', // Retrieve discount directly from payments
                'payments.total_amount', // Retrieve total amount directly
                'payments.paid_amount', // Retrieve paid amount directly
                'payments.due_amount' // Retrieve due amount directly
            )
    ->where('invoices.status', 1) // Filter by status

            ->groupBy(
                'invoices.id',
                'invoices.invoice_no',
                'invoices.date',
                'customers.name',
                'payments.discount_amount',
                'payments.total_amount',
                'payments.paid_amount',
                'payments.due_amount' // Include these fields in the group-by clause
            ) // Group by non-aggregated columns
            ->orderBy('invoices.id', 'desc')
            ->get();


            return view('backend.invoice.invoice_all',compact('allData'));

    } // End Method

    public function InvoiceDetails($id){
        $invoice = Invoice::with('invoice_details')->findOrFail($id);

            return view('backend.pdf.invoice_pdf',compact('invoice'));

    }//end method

    public function PendingList(){
        // $allData = DB::table('invoices')
        // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
        // ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
        // ->join('customers', 'customers.id', '=', 'payments.customer_id')
        // ->join('products', 'invoice_details.product_id', '=', 'products.id')
        // ->join('units', 'products.unit_id', '=', 'units.id')

        // ->select('invoices.invoice_no',
        // 'invoices.status',
        // 'units.name as unit_name',
        // 'invoices.date as date',
        // 'invoices.id as invoiceid',
        // 'payments.total_amount',
        // 'payments.paid_amount',
        // 'payments.due_amount',
        // 'payments.discount_amount',
        // 'invoice_details.unit_price',
        // 'invoice_details.selling_qty',
        // 'customers.name as customer_name',
        // 'products.name as product_name')
        // ->orderBy('invoices.id', 'desc')
        // ->orderBy('invoices.date', 'desc')
        // ->where('invoices.status', 0)
        // ->get();

        $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->join('units', 'products.unit_id', '=', 'units.id')
    ->select(
        'invoices.invoice_no',
        'invoices.status',
        'invoices.date as date',
        'invoices.id as invoiceid',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'), // Combine product names
        // DB::raw('GROUP_CONCAT(units.name SEPARATOR ", ") as unit_names'), // Combine unit names
        DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities
        // DB::raw('GROUP_CONCAT(invoice_details.unit_price SEPARATOR ", ") as unit_prices'), // Combine unit prices
        'payments.total_amount', // Total amount directly
        'payments.paid_amount', // Paid amount directly
        'payments.due_amount', // Due amount directly
        'payments.discount_amount', // Discount amount directly
        'customers.name as customer_name'
    )
    ->groupBy(
        'invoices.invoice_no',
        'invoices.status',
        'invoices.date',
        'invoices.id',
        'payments.total_amount',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',
        'customers.name'
    ) // Group by these columns
    ->orderBy('invoices.id', 'desc')
    ->orderBy('invoices.date', 'desc')
    ->where('invoices.status', 0) // Filter by status 0
    ->get();


            return view('backend.invoice.invoice_pending_list',compact('allData'));
    } // End Method

    public function invoiceAdd(){
        $costomer = Customer::all();
        $category = Category::all();
        $invoice_data = Invoice::orderBy('id','desc')->first();
        if($invoice_data == null)
        {
            $first_reg = '0';
            $invoice_no = $first_reg + 1;
        }
        else
        {
           $invoice_data = Invoice::orderBy('id','desc')->first()->invoice_no;
           $invoice_no = $invoice_data + 1;
        }
        $date= date('Y-m-d');
        return view('backend.invoice.invoice_add',compact('category','invoice_no','date','costomer'));

    } // End Method


    public function InvoiceStore(Request $request){

        if ($request->category_id == null) {

           $notification = array(
            'message' => 'Sorry You do not select any item',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);

        } else{
            if ($request->paid_amount > $request->estimated_amount) {

               $notification = array(
            'message' => 'Sorry Paid Amount is Maximum the total price',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);

            } else {


                $invoice = new Invoice();
                $invoice->invoice_no = $request->invoice_no;
                $invoice->date = date('Y-m-d', strtotime($request->date)) ;
                $invoice->description = $request->description;
                $invoice->status = '0';
                $invoice->created_by = Auth::user()->id;

                DB::transaction(function () use($request, $invoice) {

                    if($invoice->save())
                    {

                        $count_category = count($request->category_id);
                        for($i=0; $i < $count_category; $i++)
                        {

                            $invoice_details = new InvoiceDetail();
                            $invoice_details->date = date('Y-m-d',strtotime($request->date));
                            $invoice_details->invoice_id = $invoice->id;
                            $invoice_details->category_id = $request->category_id[$i];
                            $invoice_details->product_id = $request->product_id[$i];
                            $invoice_details->selling_qty = $request->selling_qty[$i];
                            $invoice_details->unit_price = $request->unit_price[$i];
                            $invoice_details->selling_price = $request->selling_price[$i];
                            $invoice_details->status = '0';
                            $invoice_details->save();
                        } // end foreach

                        if($request->customer_id == '0')
                        {
                           $customer = new Customer();
                           $customer->name = $request->name;
                           $customer->mobile_no = $request->mobile_no;
                           $customer->email = $request->email;
                           $customer->save();
                           $customer_id = $customer->id;
                        }
                        else
                        {
                            $customer_id = $request->customer_id;
                        }

                        $payment = new Payment();
                        $payment_details = new PaymentDetail();
                        $payment->invoice_id = $invoice->id;
                        $payment->customer_id = $customer_id;
                        $payment->paid_status = $request->paid_status;
                        $payment->discount_amount = $request->discount_amount;
                        $payment->total_amount = $request->estimated_amount;
                        if($request->paid_status == 'full_paid')
                        {
                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $payment_details->current_paid_amount = $request->estimated_amount;

                        }
                        elseif($request->paid_status == 'full_due')
                        {
                            $payment->paid_amount = '0' ;
                            $payment->due_amount = $request->estimated_amount;
                            $payment_details->current_paid_amount = '0';
                        }
                        elseif($request->paid_status == 'partial_paid')
                        {
                            $payment->paid_amount = $request->paid_amount ;
                            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                            $payment_details->current_paid_amount = $request->paid_amount;
                        }
                        $payment->save();

                        $payment_details->invoice_id = $invoice->id;
                        $payment_details->date = date('Y-m-d', strtotime($request->date));

                        $payment_details->save();

                    }
                });


            }//end else
        }

        $notification = array(
            'message' => '  Invoice data inserted sucessfuly',
            'alert-type' => 'info'
        );
        return redirect()->route('invoice.pending.list')->with($notification);

    } // End Method

    public function InvoiceDelete($id){

        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        InvoiceDetail::where('invoice_id',$invoice->id)->delete();
        Payment::where('invoice_id',$invoice->id)->delete();
        PaymentDetail::where('invoice_id',$invoice->id)->delete();

         $notification = array(
        'message' => 'Invoice Deleted Successfully',
        'alert-type' => 'info'
    );
    return redirect()->back()->with($notification);

    }// End Method


    public function InvoiceApprove($id){

        $invoice = Invoice::with('invoice_details')->findOrFail($id);
        return view('backend.invoice.invoice_approve',compact('invoice'));

    }// End Method



    public function ApprovalStore(Request $request, $id){

        foreach($request->selling_qty as $key => $val){
            $invoice_details = InvoiceDetail::where('id',$key)->first();
            $product = Product::where('id',$invoice_details->product_id)->first();
            if($product->quantity < $request->selling_qty[$key]){

        $notification = array(
        'message' => 'Sorry '.$product->name. ' product is out of stock',
        'alert-type' => 'error'
    );
    return redirect()->back()->with($notification);

            }
        } // End foreach

        $invoice = Invoice::findOrFail($id);
        $invoice->updated_by = Auth::user()->id;
        $invoice->status = '1';


        DB::transaction(function () use($request,$invoice,$id) {

            foreach($request->selling_qty as $key => $val)
            {
                $invoice_details = InvoiceDetail::where('id',$key)->first();

                $invoice_details->status = '1';
                $invoice_details->save();

                $product = Product::where('id',$invoice_details->product_id)->first();
                $product->quantity = ((float)$product->quantity) - ((float)$request->selling_qty[$key]);
                $product->save();
            } // end foreach
            $invoice->save();
        });

        $notification = array(
            'message' => 'Invoice approved successfully ',
            'alert-type' => 'info'
        );
        return redirect()->route('invoice.pending.list')->with($notification);

    } // End Method


    public function PrintInvoiceList(){

        // $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->where('status','1')->get();
        // $allData = DB::table('invoices')
        // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
        // ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
        // ->join('customers', 'customers.id', '=', 'payments.customer_id')
        // ->join('products', 'invoice_details.product_id', '=', 'products.id')
        // ->join('units', 'products.unit_id', '=', 'units.id')

        // ->select('invoices.invoice_no',
        // 'invoices.status',
        // 'invoices.date',
        // 'units.name as unit_name',
        // 'invoices.id as invoiceid',
        // 'payments.total_amount',
        // 'payments.due_amount',
        // 'payments.paid_amount',
        // 'payments.discount_amount',
        // 'invoice_details.unit_price',
        // 'invoice_details.selling_qty',
        // 'customers.name as customer_name',
        // 'products.name as product_name')
        // ->orderBy('invoices.date', 'desc')
        // ->orderBy('invoices.id', 'desc')
        // ->where('invoices.status', 1)
        // ->get();


        $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->join('units', 'products.unit_id', '=', 'units.id')
    ->select(
        'invoices.invoice_no',
        'invoices.date',
        'invoices.id as invoiceid',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'), // Combine product names
        DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities
        'payments.total_amount', // Total amount directly
        'payments.due_amount', // Due amount directly
        'payments.paid_amount', // Paid amount directly
        'payments.discount_amount', // Discount amount directly
        'customers.name as customer_name'
    )
    ->groupBy(
        'invoices.invoice_no',
        'invoices.date',
        'invoices.id',
        'payments.total_amount',
        'payments.due_amount',
        'payments.paid_amount',
        'payments.discount_amount',
        'customers.name'
    )
    ->orderBy('invoices.date', 'desc')
    ->orderBy('invoices.id', 'desc')
    ->where('invoices.status', 1)
    ->get();


           return view('backend.invoice.print_invoice_list',compact('allData'));
        } // End Method


        public function PrintInvoice($id){
            $invoice = Invoice::with('invoice_details')->findOrFail($id);

            return view('backend.pdf.invoice_pdf',compact('invoice'));


            // $pdf = Pdf::loadView('backend.pdf.invoice_pdf', compact('invoice'));
            // return $pdf->download('invoice-' . $id . '.pdf'); // Download the PDF


            // $pdf = Pdf::loadView('backend.package.package_history_invoice', compact('packagehistory'))->setPaper('a4')->setOption([
            //     'tempDir' => public_path(),
            //     'chroot' => public_path(),
            // ]);
            // return $pdf->download('invoice.pdf');

        } // End Method


        public function ScanpdfInvoice($id)
{
    $invoice = Invoice::with('invoice_details')->findOrFail($id);

    // Load the view and generate the PDF
    $pdf = PDF::loadView('backend.pdf.invoice_pdf', compact('invoice'));

    // Optionally set paper size or other settings
    $pdf->setPaper('a4', 'portrait');

    // Return the generated PDF for download
    return $pdf->download('invoice-' . $id . '.pdf'); // Forces the browser to download the PDF
}

        public function DailyInvoiceReport(){
            return view('backend.invoice.daily_invoice_report');
        } // End Method

        public function DailyInvoicePdf(Request $request){

            $sdate = date('Y-m-d',strtotime($request->start_date));
            $edate = date('Y-m-d',strtotime($request->end_date));
            // $allData = Invoice::whereBetween('date',[$sdate,$edate])->where('status','1')->get();
            if($sdate > $edate)
            {

                $notification = array(
                    'message' => 'Please choose valid range of dates',
                    'alert-type' => 'error'
                );
                return redirect()->back( )->with($notification);
            }
            // $allData = DB::table('invoices')
            // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')//
            // ->join('payments', 'invoices.id', '=', 'payments.invoice_id')//
            // ->join('customers', 'customers.id', '=', 'payments.customer_id')
            // ->join('categories', 'invoice_details.category_id', '=', 'categories.id')
            // ->join('products', 'invoice_details.product_id', '=', 'products.id')
            // ->join('units', 'products.unit_id', '=', 'units.id')
            // ->select(
            //     'invoices.id as invoice_id',
            //     'invoices.date as date',
            //     'invoices.invoice_no as invoice_no',
            //     'units.name as unit_name',
            //     'customers.name as customer_name',
            //     'categories.name as category_name',
            //     'products.name as product_name',
            //     'invoice_details.selling_qty',
            //     'invoice_details.unit_price',
            //     'payments.total_amount',
            //     'payments.due_amount',
            // 'payments.paid_amount',
            // 'payments.discount_amount',
            // )

            // ->whereBetween('invoices.date', [$sdate, $edate])
            // ->where('invoices.status', 1)
            // ->orderBy('invoices.id', 'desc')
            // ->get();


            $allData = DB::table('invoices')
            ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->join('products', 'invoice_details.product_id', '=', 'products.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select(
                'invoices.id as invoice_id',
                'invoices.date as date',
                'invoices.invoice_no as invoice_no',
                'customers.name as customer_name',
                DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'), // Combine product names
                DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities
                // DB::raw('GROUP_CONCAT(invoice_details.unit_price SEPARATOR ", ") as unit_prices'), // Combine unit prices
                DB::raw('GROUP_CONCAT(CONCAT("Ugx ", FORMAT(invoice_details.unit_price, 0)) SEPARATOR ", ") as unit_prices'), // Combine prices

                'payments.total_amount', // Retrieve total amount directly
                'payments.due_amount', // Retrieve due amount directly
                'payments.paid_amount', // Retrieve paid amount directly
                'payments.discount_amount' // Retrieve discount amount directly
            )
            ->whereBetween('invoices.date', [$sdate, $edate]) // Filter by date range
            ->where('invoices.status', 1) // Filter by status
            ->groupBy(
                'invoices.id',
                'invoices.date',
                'invoices.invoice_no',
                'customers.name',
                'payments.total_amount',
                'payments.due_amount',
                'payments.paid_amount',
                'payments.discount_amount'
            ) // Group by unique columns
            ->orderBy('invoices.id', 'desc')
            ->get();


            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));

            return view('backend.pdf.daily_invoice_report_pdf',compact('allData','start_date','end_date'));
        } // End Method
}
