@extends('admin.admin_master')
@section('admin')

 <!-- Mobile responsiveness styles -->
 <style>
    @media (max-width: 768px) {
        /* Ensure table is scrollable horizontally on small devices */
        .table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        /* Keep table headers and cells readable */
        .table th,
        .table td {
            white-space: nowrap;
            text-align: center;
        }
    }
</style>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">Approve invoice </h4>
        </div>
    </div>
</div>
<!-- end page title -->
        @php
        $payment = App\Models\Payment::where('invoice_id',$invoice->id)->first();
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6>Invoice No: #{{ $invoice->invoice_no }} &nbsp; {{ date('d-m-Y', strtotime($invoice->date)) }} </h6>

                        <a href="{{ route('invoice.pending.list') }}" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;">
                            <i class="fa fa-list"> Pending Invoice List </i>
                        </a>
                        <br><br>

                        {{-- <table class="table " width="100%">
                            <tbody>
                                <tr>
                                    <td><p> Customer Info </p></td>
                                    <td><p> Name: <strong> {{ $payment['customer']['name'] }} </strong> </p></td>
                                    <td><p> Mobile: <strong> {{ $payment['customer']['mobile_no'] }} </strong> </p></td>
                                    <td><p> Email: <strong> {{ $payment['customer']['email'] }} </strong> </p></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td colspan="3"><p> Description : <strong> {{ $invoice->description }} </strong> </p></td>
                                </tr>
                            </tbody>
                        </table> --}}

                        <table class="table table-striped  table-hover" width="100%" style="border-collapse: collapse; border: 1px solid #ddd;  padding: 15px;">
                            <tbody>
                                <tr class="table-header">
                                    <td style="font-weight: bold; padding: 10px; text-align: left; border: 1px solid #ddd;">Customer Info</td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">
                                        <p><strong>Name:</strong> <b> {{ $payment['customer']['name'] }} </b></p>
                                    </td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">
                                        <p><strong>Mobile:</strong> {{ $payment['customer']['mobile_no'] }}</p>
                                    </td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">
                                        <p><strong>Email:</strong> {{ $payment['customer']['email'] }}</p>
                                    </td>
                                </tr>

                                <tr style="background-color: #f7f7f7;">
                                    <td style="padding: 10px; border: 1px solid #ddd;" colspan="4">
                                        <p><strong>Description:</strong> {{ $invoice->description }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <form method="post" action="{{ route('approval.store', $invoice->id) }}">
                            @csrf

                            <table border="1" class="table" width="100%">
                               <thead style="background-color: #635bff; color:azure">
                                   <tr>
                                       <th class="text-center">Sl</th>
                                       <th class="text-center">Category</th>
                                       <th class="text-center">Product Name</th>
                                       <th class="text-center" >Current Stock</th>
                                       <th class="text-center">Quantity</th>
                                       <th class="text-center">Unit Price </th>
                                       <th class="text-center">Total Price</th>
                                   </tr>
                               </thead>
                               <tbody>
                                    @php
                                    $total_sum = 0;
                                    @endphp
                                    @foreach($invoice['invoice_details'] as $key => $details)
                                    <tr>
                                        <input type="hidden" name="category_id[]" value="{{ $details->category_id }}">
                                        <input type="hidden" name="product_id[]" value="{{ $details->product_id }}">
                                        <input type="hidden" name="selling_qty[{{$details->id}}]" value="{{ $details->selling_qty }}">

                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-center">{{ $details['category']['name'] }}</td>
                                        <td class="text-center">{{ $details['product']['name'] }}</td>
                                        <td class="text-center" style="background-color: {{ $details['product']['quantity'] == 0 ? '#f19b9b' : '#c8e6c9' }}">
                                            {{ $details['product']['quantity'] }}
                                        </td>

                                        <td class="text-center">{{ $details->selling_qty }}</td>
                                        <td class="text-center">{{ 'KSh ' . number_format($details->unit_price) }}</td>
                                        <td class="text-center">{{ 'KSh ' . number_format($details->selling_price) }}</td>
                                    </tr>
                                    @php
                                    $total_sum += $details->selling_price;
                                    @endphp
                                    @endforeach

                                    <tr>
                                        <td colspan="6"> Sub Total </td>
                                        <td> {{ 'KSh ' . number_format($total_sum) }} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"> Discount </td>
                                        <td> {{ 'KSh ' . number_format($payment->discount_amount) }} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"> Paid Amount </td>
                                        <td>{{ 'KSh ' . number_format($payment->paid_amount) }} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"> Balance </td>
                                        {{-- <td> {{ 'KSh ' . number_format($payment->due_amount) }} </td> --}}
                                        <td  style="background-color: {{ $payment->due_amount == 0 ? '#c8e6c9' : '#f19b9b' }}">{{ 'KSh ' . number_format($payment->due_amount) }}</td>

                                    </tr>
                                    <tr>
                                        <td colspan="6"> Grand Amount </td>
                                        <td>{{ 'KSh ' . number_format($payment->total_amount) }}</td>
                                    </tr>
                               </tbody>
                            </table>

                            <button type="submit" class="btn btn-info" data-bs-toggle="tooltip" title="Click to approve this invoice">
                                <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Approve Invoice
                            </button>

                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection
