<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function PurchaseAll()
    {
        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->get();
        return view('backend.purchase.purchase_all', compact('allData'));
    }

    public function PurchaseAdd(){

        $supplier = Supplier::all();
        $unit = Unit::all();
        $category = Category::all();
        return view('backend.purchase.purchase_add',compact('supplier','unit','category'));

    } // End Method

    public function PurchaseStore(Request $request){

        if ($request->category_id == null) {

           $notification = array(
            'message' => 'please add atleast one item',
            'alert-type' => 'error'
        );
        return redirect()->back( )->with($notification);
        } else {

            $count_category = count($request->category_id);
            for ($i=0; $i < $count_category; $i++) {
                $purchase = new Purchase();
                $purchase->date = date('Y-m-d', strtotime($request->date[$i]));
                $purchase->purchase_no = $request->purchase_no[$i];
                $purchase->supplier_id = $request->supplier_id[$i];
                $purchase->category_id = $request->category_id[$i];

                $purchase->product_id = $request->product_id[$i];
                $purchase->buying_qty = $request->buying_qty[$i];
                $purchase->unit_price = $request->unit_price[$i];
                $purchase->buying_price = $request->buying_price[$i];
                $purchase->description = $request->description[$i];

                $purchase->created_by = Auth::user()->id;
                $purchase->status = '0';
                $purchase->save();
            } // end foreach
        } // end else

        $notification = array(
            'message' => 'Data Save Successfully',
            'alert-type' => 'info'
        );
        return redirect()->route('purchase.all')->with($notification);
        }// End Method

        public function PurchaseDelete($id)
        {
         Purchase::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Purchase item  deleted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
        }

        public function PurchasePending(){

            $allData = Purchase::orderBy('date','asc')->orderBy('id','desc')->where('status','0')->get();
            return view('backend.purchase.purchase_pending',compact('allData'));
        }// End Method

        public function PurchaseApprove($id)
        {

            $purchase = Purchase::findOrFail($id);

            $product = Product::where('id',$purchase->product_id)->first();

           $purchase_qty = ((float)($product->quantity) + (float)($purchase->buying_qty));
           $product->quantity = $purchase_qty;


           if($product->save())
           {

            Purchase::findOrFail($id)->update([

                'status' => '1',

            ]);
            $notification = array(
                'message' => 'Status Approved Successfully',
                'alert-type' => 'info'
                  );
            return redirect()->route('purchase.all')->with($notification);
           }

        }
        public function PurchaseApproveAll(Request $request)
         {
                $ids = $request->input('ids');

                if (!$ids || !is_array($ids)) {
                    return response()->json(['message' => 'No purchases selected.'], 400);
                }

                foreach ($ids as $id) {
                    $purchase = Purchase::findOrFail($id);
                    $product = Product::where('id', $purchase->product_id)->first();

                    $purchase_qty = ((float)($product->quantity) + (float)($purchase->buying_qty));
                    $product->quantity = $purchase_qty;

                    if ($product->save()) {
                        $purchase->update(['status' => '1']);
                    }
                }

                return response()->json(['message' => 'Selected purchases approved successfully.']);
        }


        public function DailyPurchaseReport(){
            return view('backend.purchase.daily_purchase_report');
        }// End Method


        public function DailyPurchasePdf(Request $request){

            $sdate = date('Y-m-d',strtotime($request->start_date));
            $edate = date('Y-m-d',strtotime($request->end_date));
            if($sdate > $edate)
            {

                $notification = array(
                    'message' => 'Please choose valid range of dates',
                    'alert-type' => 'error'
                );
                return redirect()->back( )->with($notification);
            }

                $allData = Purchase::whereBetween('date',[$sdate,$edate])->where('status','1')->get();



            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            return view('backend.pdf.daily_purchase_report_pdf',compact('allData','start_date','end_date'));

        }// End Method
}
