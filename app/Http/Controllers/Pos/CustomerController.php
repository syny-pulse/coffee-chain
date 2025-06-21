<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\PaymentDetail;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{

    public function CustomerAll(){
    $customers = Customer::latest()->get();
    return view('backend.customer.customer_all',compact('customers'));
    } // End Method

   public function CustomerAdd(){
    return view('backend.customer.customer_add');
   }    // End Method

   public function CustomerStore(Request $request){



    if ($request->file('customer_image')) {

        $image = $request->file('customer_image');
   $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); // 343434.png
   Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
   $save_url = 'upload/customer/'.$name_gen;

    Customer::insert([
        'name' => $request->name,
        'mobile_no' => $request->mobile_no,
        'email' => $request->email,
        'address' => $request->address,
        'customer_image' => $save_url ,
        'created_by' => Auth::user()->id,
        'created_at' => Carbon::now(),

    ]);

     $notification = array(
        'message' => 'Customer Inserted with image Successfully',
        'alert-type' => 'info'
    );
    return redirect()->route('customer.all')->with($notification);
    }
    else{

         Customer::insert([
             'name' => $request->name,
             'mobile_no' => $request->mobile_no,
             'email' => $request->email,
             'address' => $request->address,
             'created_by' => Auth::user()->id,
             'created_at' => Carbon::now(),

         ]);

          $notification = array(
             'message' => 'Customer Inserted without image Successfully',
             'alert-type' => 'info'
         );
         return redirect()->route('customer.all')->with($notification);
         }// end else


//    $image = $request->file('customer_image');
//    $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); // 343434.png
//    Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
//    $save_url = 'upload/customer/'.$name_gen;

//     Customer::insert([
//         'name' => $request->name,
//         'mobile_no' => $request->mobile_no,
//         'email' => $request->email,
//         'address' => $request->address,
//         'customer_image' => $save_url ,
//         'created_by' => Auth::user()->id,
//         'created_at' => Carbon::now(),

//     ]);

//      $notification = array(
//         'message' => 'Customer Inserted Successfully',
//         'alert-type' => 'success'
//     );

//     return redirect()->route('customer.all')->with($notification);

} // End Method


public function CustomerEdit($id){

    $customer = Customer::findOrFail($id);
    return view('backend.customer.customer_edit',compact('customer'));

 } // End Method


 public function CustomerUpdate(Request $request){

     $customer_id = $request->id;
     if ($request->file('customer_image')) {

     $image = $request->file('customer_image');
     $data = Customer::find($customer_id);
     @unlink(public_path($data->customer_image));

     $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); // 343434.png
     Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
     $save_url = 'upload/customer/'.$name_gen;

     Customer::findOrFail($customer_id)->update([
         'name' => $request->name,
         'mobile_no' => $request->mobile_no,
         'email' => $request->email,
         'address' => $request->address,
         'customer_image' => $save_url ,
         'updated_by' => Auth::user()->id,
         'updated_at' => Carbon::now(),

     ]);

      $notification = array(
         'message' => 'Customer Updated with Image Successfully',
         'alert-type' => 'info'
     );

     return redirect()->route('customer.all')->with($notification);

     } else{

       Customer::findOrFail($customer_id)->update([
         'name' => $request->name,
         'mobile_no' => $request->mobile_no,
         'email' => $request->email,
         'address' => $request->address,
         'updated_by' => Auth::user()->id,
         'updated_at' => Carbon::now(),

     ]);

      $notification = array(
         'message' => 'Customer Updated without Image Successfully',
         'alert-type' => 'info'
     );

     return redirect()->route('customer.all')->with($notification);

     } // end else

 } // End Method



 public function CustomerDelete($id){

    $customers = Customer::findOrFail($id);
    $img = $customers->customer_image;
    if($img != null)
    {
        unlink($img);
    }

    // @unlink(public_path('upload/customer'.$img));
    Customer::findOrFail($id)->delete();

    $notification = array(
        'message' => 'Customer Deleted Successfully',
        'alert-type' => 'info'
    );

    return redirect()->back()->with($notification);

} // End Method

public function CreditCustomer(){

    // $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->orderBy('id','desc')->get();

    // $allData = DB::table('invoices')
    // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    // ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    // // ->join('payment_details', 'invoices.id', '=', 'payment_details.invoice_id')
    // ->join('customers', 'customers.id', '=', 'payments.customer_id')
    // ->join('categories', 'invoice_details.category_id', '=', 'categories.id') // Join categories table
    // ->join('products', 'invoice_details.product_id', '=', 'products.id')
    // ->select(
    //     'invoices.invoice_no',
    //     'invoices.date as date',
    //     'payments.total_amount',
    //     'payments.invoice_id',
    //     'payments.paid_amount',
    //     'payments.due_amount',
    //     'customers.name as customer_name',
    //     'products.name as product_name'
    // )
    // ->whereIn('payments.paid_status', ['full_due', 'partial_paid']) // Add whereIn condition
    // ->orderBy('payments.id', 'desc') // Order by invoices id in descending order
    // ->get();


    $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->select(
        'invoices.invoice_no',
        'invoices.date as date',
        'payments.invoice_id',
        'payments.total_amount',
        'payments.paid_amount',
        'payments.due_amount',
        'customers.name as customer_name',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names') // Combine product names
    )
    ->whereIn('payments.paid_status', ['full_due', 'partial_paid']) // Add whereIn condition
    ->where('invoices.status', 1) // Filter by status

    ->groupBy(
        'invoices.invoice_no',
        'invoices.date',
        'payments.invoice_id',
        'payments.total_amount',
        'payments.paid_amount',
        'payments.due_amount',
        'customers.name'
    ) // Group by unique fields
    ->orderBy('payments.id', 'desc') // Order by payments id in descending order
    ->get();



    return view('backend.customer.customer_credit',compact('allData'));

} // End Method

public function CreditCustomerPrintPdf(){

    $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
    return view('backend.pdf.customer_credit_pdf',compact('allData'));

}// End Method

public function CustomerEditInvoice($invoice_id){

    $payment = Payment::where('invoice_id',$invoice_id)->first();
    return view('backend.customer.edit_customer_invoice',compact('payment'));

}// End Method

public function CustomerUpdateInvoice(Request $request,$invoice_id){

    if ($request->new_paid_amount < $request->paid_amount) {

        $notification = array(
        'message' => 'Amount exceeds the remaining balance. Please enter a valid amount.',
        'alert-type' => 'error'
    );

    return redirect()->back()->with($notification);
    } else{

        $payment = Payment::where('invoice_id',$invoice_id)->first();
        $payment_details = new PaymentDetail();
        $payment->paid_status = $request->paid_status;

        if ($request->paid_status == 'full_paid') {
             $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount']+$request->new_paid_amount;
             $payment->due_amount = '0';
             $payment_details->current_paid_amount = $request->new_paid_amount;

        } elseif ($request->paid_status == 'partial_paid') {
            $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount']+$request->paid_amount;
            $payment->due_amount = Payment::where('invoice_id',$invoice_id)->first()['due_amount']-$request->paid_amount;
            $payment_details->current_paid_amount = $request->paid_amount;

        }

        $payment->save();
        $payment_details->invoice_id = $invoice_id;
        $payment_details->date = date('Y-m-d',strtotime($request->date));
        $payment_details->updated_by = Auth::user()->id;
        $payment_details->save();

          $notification = array(
        'message' => 'Invoice Update Successfully',
        'alert-type' => 'info'
    );
    return redirect()->route('credit.customer')->with($notification);
    }

}// End Method

public function CustomerInvoiceDetails($invoice_id){

    $payment = Payment::where('invoice_id',$invoice_id)->first();
    return view('backend.pdf.invoice_details_pdf',compact('payment'));

}// End Method

public function PaidCustomer(){
    // $allData = Payment::where('paid_status','!=','full_due')->orderBy('id','desc')->get();



    // $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->orderBy('id','desc')->get();

    // $allData = DB::table('invoices')
    // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    // ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    // ->join('customers', 'customers.id', '=', 'payments.customer_id')
    // ->join('products', 'invoice_details.product_id', '=', 'products.id')
    // ->select(
    //     'invoices.invoice_no',
    //     'invoices.date as date',

    //     'payments.invoice_id',
    //     'payments.paid_amount',
    //     'payments.due_amount',
    //     'customers.name as customer_name',
    //     'products.name as product_name'
    // )
    // ->where('payments.paid_status', '!=', 'full_due') // Filter out full due payments
    // ->orderBy('payments.id', 'desc') // Order by payment ID in descending order
    // ->get();

    $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->select(
        'invoices.invoice_no',
        'invoices.date as date',
        'payments.invoice_id',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.total_amount',
        'customers.name as customer_name',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names') // Combine product names
    )
    ->where('payments.paid_status', '!=', 'full_due') // Filter out full due payments
    ->where('invoices.status', 1) // Filter by status
    ->groupBy(
        'invoices.invoice_no',
        'invoices.date',
        'payments.invoice_id',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.total_amount',

        'customers.name'
    ) // Group by non-aggregated columns
    ->orderBy('payments.id', 'desc') // Order by payment ID in descending order
    ->get();




    return view('backend.customer.customer_paid',compact('allData'));
}// End Method



public function unPaidCustomer(){
    // $allData = Payment::whereIn('paid_status', ['full_due', 'partial_paid'])->get();
    // $allData = Payment::where('paid_status','!=','full_paid')->get();
    // $allData = Payment::where('due_amount', '>', 0)->orderBy('id','desc')->get();



    $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->select(
        'invoices.invoice_no',
        'invoices.date as date',
        'payments.total_amount',
        'payments.invoice_id',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',
        'customers.name as customer_name',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names') // Combine product names
    )
    ->where('payments.due_amount', '>', 0) // Filter out full due payments
    ->where('invoices.status', 1) // Filter by status

    ->groupBy(
        'invoices.invoice_no',
        'invoices.date',
        'payments.total_amount',
        'payments.invoice_id',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',
        'customers.name'
    ) // Group by non-aggregated fields
    ->orderBy('payments.id', 'desc') // Order by payment ID in descending order

    ->get();



    return view('backend.customer.customer_unpaid',compact('allData'));
}// End Method

public function unPaidCustomerPrintPdf(){

    // $allData = Payment::where('paid_status','!=','full_due')->get();
    // $allData = Payment::where('due_amount', '>', 0)->orderBy('id','desc')->get();

    // $allData = DB::table('invoices')
    // ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    // ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    // ->join('customers', 'customers.id', '=', 'payments.customer_id')
    // ->join('products', 'invoice_details.product_id', '=', 'products.id')
    // ->select(
    //     'invoices.invoice_no',
    //     'invoices.date as date',
    //     'payments.total_amount',
    //     'payments.invoice_id',
    //     'invoice_details.selling_qty',
    //     'invoice_details.unit_price',
    //     'payments.paid_amount',
    //     'payments.due_amount',
    //     'payments.discount_amount',
    //     'customers.name as customer_name',
    //     'products.name as product_name'
    // )
    // ->where('payments.due_amount', '>', 0)
    // ->orderBy('date', 'desc')
    // ->get();

    // $allData = Payment::with(['invoice', 'invoice.invoiceDetails.product', 'customer'])
    // ->where('due_amount', '>', 0)
    // ->orderBy('date', 'desc')
    // ->get();
    // $allData = Payment::with(['invoice', 'invoice.invoiceDetails.product', 'customer'])
    // ->where('due_amount', '>', 0)  // Filter payments with due_amount > 0
    // ->whereHas('invoice', function ($query) {
    //     $query->orderBy('date', 'desc'); // Ensure invoice date is considered
    // })
    // ->get()
    // ->sortByDesc(fn($payment) => $payment->invoice->date); // Sort payments by invoice date

    $allData = Payment::with(['invoice', 'invoice.invoiceDetails.product', 'customer'])
    ->where('due_amount', '>', 0)  // Filter payments with due_amount > 0
    ->whereHas('invoice', function ($query) {
        $query->where('status', 1) // Filter invoices where status = 1
              ->orderBy('date', 'desc'); // Ensure invoice date is considered
    })
    ->get()
    ->sortByDesc(fn($payment) => $payment->invoice->date); // Sort payments by invoice date






    return view('backend.pdf.customer_paid_pdf',compact('allData'));
}// End Method

public function CustomerWiseReport(){

    $customers = Customer::all();
    return view('backend.customer.customer_wise_report',compact('customers'));

}// End Method

public function CustomerWiseCreditReport(Request $request){

    // $allData = Payment::where('customer_id',$request->customer_id)->whereIn('paid_status',['full_due','partial_paid'])->get();



    $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->select(
        'invoices.invoice_no',
        'invoices.status',
        'invoices.date as date',
        'payments.total_amount',
        'payments.invoice_id',

                DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities
                DB::raw('GROUP_CONCAT(CONCAT("Ugx ", FORMAT(invoice_details.unit_price, 0)) SEPARATOR ", ") as prices'), // Combine prices

        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',
        'customers.name as customer_name',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names') // Concatenate product names
    )
    ->where('payments.customer_id', $request->customer_id)
    ->where('payments.due_amount', '>', 0) // Filter out full due payments
    ->groupBy(
        'invoices.invoice_no',
        'invoices.date',
        'invoices.status',
        'payments.total_amount',
        'payments.invoice_id',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',
        'customers.name'
    ) // Group by non-aggregated fields
    ->orderBy('payments.id', 'desc') // Order by date in descending order
    ->get();



   return view('backend.pdf.customer_wise_credit_pdf',compact('allData'));
}// End Method
public function CustomerWisePaidReport(Request $request){

    // $allData = Payment::where('customer_id',$request->customer_id)->where('paid_status','!=','full_due')->get();


    $allData = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->join('units', 'products.unit_id', '=', 'units.id')
    ->select(
        'invoices.invoice_no',
        'invoices.date as date',
        'units.name as unit_name',
        'payments.invoice_id',
        'customers.name as customer_name',
        'payments.total_amount',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',

        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'), // Combine product names

        DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities
        DB::raw('GROUP_CONCAT(CONCAT("Ugx ", FORMAT(invoice_details.unit_price, 0)) SEPARATOR ", ") as prices'), // Combine prices
    )
    ->where('payments.customer_id', $request->customer_id)
    ->whereIn('payments.paid_status', ['full_due', 'partial_paid', 'full_paid']) // Include specific statuses
    ->where('invoices.status', 1) // Filter by status
    ->groupBy(
        'invoices.invoice_no',
        'invoices.date',
        'units.name',
       'payments.total_amount',
        'payments.invoice_id',
        'payments.paid_amount',
        'payments.due_amount',
        'payments.discount_amount',
        'customers.name'
    ) // Group by non-aggregated fields
    ->orderBy('payments.id', 'desc') // Order by date in descending order
    ->get();



   return view('backend.pdf.customer_wise_paid_pdf',compact('allData'));
}// End Method

}
