@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
           <!-- start page title -->
           <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading"> Update invoice </h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-12">
                <div class="card border-primary"> <!-- Added border-primary for card -->
                    <div class="card-body">

                        <a href="{{ route('credit.customer') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;">
                            <i class="fa fa-list"> Back </i>
                        </a>
                        <br><br>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2" style="background-color: #F5F7FA;"> <!-- Added background color -->
                                        <h3 class="font-size-16" style="color: #4A90E2;">
                                            <strong>Customer Invoice ( Invoice No: #{{ $payment['invoice']['invoice_no'] }} )</strong>
                                        </h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped"> <!-- Added table-bordered and table-striped -->
                                                <thead style="background-color: #c8e6c9;"> <!-- Added table-primary for header -->
                                                <tr>
                                                    <td><strong>Customer Name</strong></td>
                                                    <td class="text-center"><strong>Customer Mobile</strong></td>
                                                    <td class="text-center"><strong>Address</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{ $payment['customer']['name'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['mobile_no'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['email'] }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <form method="post" action="{{ route('customer.update.invoice',$payment->invoice_id) }}">
                                    @csrf
                                    <div>
                                        <div class="p-2" style="background-color: #F5F7FA;"> <!-- Added background color -->
                                        </div>
                                        <div class="">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped"> <!-- Added table-bordered and table-striped -->
                                                    <thead style="background-color: #c8e6c9;"> <!-- Added table-primary for header -->
                                                    <tr>
                                                        <td><strong>Sl</strong></td>
                                                        <td class="text-center"><strong>Category</strong></td>
                                                        <td class="text-center"><strong>Product Name</strong></td>
                                                        <td class="text-center"><strong>Current Stock</strong></td>
                                                        <td class="text-center"><strong>Quantity</strong></td>
                                                        <td class="text-center"><strong>Unit Price</strong></td>
                                                        <td class="text-center"><strong>Total Price</strong></td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $total_sum = '0';
                                                        $invoice_details = App\Models\InvoiceDetail::where('invoice_id',$payment->invoice_id)->get();
                                                    @endphp
                                                    @foreach($invoice_details as $key => $details)
                                                        <tr>
                                                            <td class="text-center">{{ $key+1 }}</td>
                                                            <td class="text-center">{{ $details['category']['name'] }}</td>
                                                            <td class="text-center">{{ $details['product']['name'] }}</td>
                                                            <td class="text-center">{{ $details['product']['quantity'] }}</td>
                                                            <td class="text-center">{{ $details->selling_qty }}</td>
                                                            <td class="text-center">{{ 'KSh ' . number_format($details->unit_price) }}</td>
                                                            <td class="text-center">{{ 'KSh ' . number_format($details->selling_price) }}</td>
                                                        </tr>
                                                        @php
                                                            $total_sum += $details->selling_price;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5" class="thick-line"></td>
                                                        <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                                        <td class="thick-line text-end">{{ 'KSh ' . number_format($total_sum) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="no-line"></td>
                                                        <td class="no-line text-center"><strong>Discount</strong></td>
                                                        <td class="no-line text-end">{{ 'KSh ' . number_format($payment->discount_amount) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="no-line"></td>
                                                        <td class="no-line text-center"><strong>Paid Amount</strong></td>
                                                        <td class="no-line text-end">{{ 'KSh ' . number_format($payment->paid_amount) }}</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td colspan="5" class="no-line"></td>
                                                        <td class="no-line text-center"><strong>Balance</strong></td>
                                                        <td class="no-line text-end">{{ 'KSh ' . number_format($payment->due_amount) }}</td>
                                                        <td class="no-line text-end">
                                                            <h4 class="m-0" style="color: {{ $payment->due_amount > 0 ? 'red' : 'green' }};">
                                                                {{ 'KSh ' . number_format($payment->due_amount) }}
                                                            </h4>
                                                        </td>
                                                    </tr> --}}

                                                    <tr>
                                                        <td colspan="5" class="no-line"></td>
                                                        <td class="no-line text-center"><strong>Balance</strong></td>
                                                        {{-- <td class="no-line text-end" style="background-color: #8B008B">
                                                            <h4 class="m-0" >
                                                                {{ 'KSh ' . number_format($payment->due_amount) }}
                                                            </h4>
                                                        </td> --}}
                                                        <td class="no-line text-end" style="background-color: #f2a4a4; color:black">
                                                            <h4 class="m-0" >  {{ 'KSh ' . number_format($payment->due_amount) }}
                                                            </h4>
                                                        </td>

                                                    </tr>

                                                    <tr>
                                                        <td colspan="5" class="no-line"></td>
                                                        <td class="no-line text-center"><strong>Grand Amount</strong></td>
                                                        <input type="hidden" name="new_paid_amount" value="{{$payment->due_amount}}">


                                                        <td class="no-line text-end">
                                                            <h4 class="m-0" style="color: #4A90E2;">{{ 'KSh ' . number_format($payment->total_amount) }}</h4> <!-- Styled grand amount -->
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row">

                                                <div class="form-group col-md-3">
                                                    <label>Paid Status</label>
                                                    <select name="paid_status" id="paid_status" class="form-select">
                                                        <option value="">Select Status</option>
                                                        <option value="full_paid">Full Pay</option>
                                                        <option value="partial_paid">Installments</option>
                                                    </select>
                                                    <input type="number" name="paid_amount" class="form-control paid_amount" placeholder="Enter Amount" style="display:none;">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <div class="md-3">
                                                        <label for="example-text-input" class="form-label">Date</label>
                                                        <input class="form-control example-date-input" placeholder="YYYY-MM-DD" required="required" name="date" type="date" id="date">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <div class="md-3" style="padding-top: 30px;">
                                                        <button type="submit" class="btn btn-info">Invoice Update</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end row -->

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<script type="text/javascript">
    $(document).on('change','#paid_status', function(){
        var paid_status = $(this).val();
        if (paid_status == 'partial_paid') {
            $('.paid_amount').show();
        } else {
            $('.paid_amount').hide();
        }
    });
</script>
@endsection
