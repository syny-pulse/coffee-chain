@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading">Invoice details</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>Invoice No # {{ $invoice->invoice_no }}</strong></h4>
                                    <h3>
                                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" height="24"/> &nbsp;&nbsp; Deqow's Inventory
                                    </h3>
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>BBS Shopping Mall:</strong><br>
                                            Nairobi, Eastleigh<br>
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                                            <strong>Invoice Date:</strong><br>
                                            {{ date('d-m-Y', strtotime($invoice->date)) }}<br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $payment = App\Models\Payment::where('invoice_id', $invoice->id)->first();
                        @endphp

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Customer Invoice</strong></h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border: 1px solid #ddd; background-color: #f9f9f9; padding: 15px;">
                                            <tbody>
                                                <tr class="table-header">
                                                    <td style="font-weight: bold; padding: 10px; text-align: left; border: 1px solid #ddd;">Customer Info</td>
                                                    <td style="padding: 10px; border: 1px solid #ddd;">
                                                        <p><strong>Name:</strong> {{ $payment['customer']['name'] }}</p>
                                                    </td>
                                                    <td style="padding: 10px; border: 1px solid #ddd;">
                                                        <p><strong>Mobile:</strong> {{ $payment['customer']['mobile_no'] }}</p>
                                                    </td>
                                                    <td style="padding: 10px; border: 1px solid #ddd;">
                                                        <p><strong>Email:</strong> {{ $payment['customer']['email'] }}</p>
                                                    </td>
                                                </tr>

                                                <tr style="background-color: #f7f7f7;">
                                                    <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">
                                                        <p><strong>Description:</strong> {{ \Str::limit($invoice->description, 300) }}</p>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2"></div>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" >
                                            <thead class="table-header">
                                                <tr>
                                                    <td width="5%"><strong>Sl</strong></td>
                                                    <td class="text-center"><strong>Category</strong></td>
                                                    <td class="text-center"><strong>Product Name</strong></td>
                                                    <td class="text-center"><strong>Date</strong></td>
                                                    <td class="text-center"><strong>Quantity</strong></td>
                                                    <td class="text-center"><strong>Unit Price</strong></td>
                                                    <td class="text-center"><strong>Total Price</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_sum = 0;
                                                @endphp
                                                @foreach($invoice['invoice_details'] as $key => $details)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td class="text-center">{{ $details['category']['name'] }}</td>
                                                        <td class="text-center">{{ $details['product']['name'] }}</td>
                                                        {{-- <td class="text-center">{{ $details['product']['quantity'] }}</td> --}}
                                                        <td class="text-center">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                                        <td class="text-center">{{ $details->selling_qty }}</td>
                                                        <td class="text-center">{{ 'KSh ' . number_format($details->unit_price) }}</td>
                                                        <td class="text-center">{{ 'KSh ' . number_format($details->selling_price) }}</td>
                                                    </tr>
                                                    @php
                                                        $total_sum += $details->selling_price;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="text-center"><strong>Subtotal</strong></td>
                                                    <td class="text-end">{{ 'KSh ' . number_format($total_sum) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="text-center"><strong>Discount</strong></td>
                                                    <td class="text-end">{{ 'KSh ' . number_format($payment->discount_amount) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="text-center"><strong>Paid Amount</strong></td>
                                                    <td class="text-end">{{ 'KSh ' . number_format($payment->paid_amount) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="text-center"><strong>Balance</strong></td>
                                                    <td class="text-end" style="background-color: {{ $payment->due_amount == 0 ? '#c8e6c9' : '#f19b9b' }}">{{ 'KSh ' . number_format($payment->due_amount) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @php
                                                        // Start the QR code content
                                                        $qrContent = 'Customer Name: ' . $payment['customer']['name'] . "\n";
                                                        $qrContent .= "---------------------------------\n";
                                                        $qrContent .= ' Product        | Quantity  | Price  ' . "\n";
                                                        $qrContent .= "---------------------------------\n";

                                                        // Loop through each product and append in a table-like format
                                                        foreach ($invoice['invoice_details'] as $details) {
                                                            $qrContent .= sprintf("%-15s | %-9s | %-7s\n",
                                                                $details['product']['name'],
                                                                $details->selling_qty,
                                                                'KSh ' . number_format($details->unit_price)
                                                            );
                                                        }

                                                        // Add additional payment info
                                                        $qrContent .= "---------------------------------\n";
                                                        $qrContent .= ' Discount: ' . 'KSh ' . number_format($payment->discount_amount) . "\n";
                                                        $qrContent .= ' Total Amount: ' . 'KSh ' . number_format($payment->total_amount) . "\n";
                                                        $qrContent .= ' Paid Amount: ' . 'KSh ' . number_format($payment->paid_amount) . "\n";
                                                        $qrContent .= ' Balance: ' . 'KSh ' . number_format($payment->due_amount);
                                                    @endphp

                                                    {{ QrCode::size(100)->generate($qrContent) }}
                                                {{-- {{ QrCode::size(100)->generate(route('invoice.pdf.scan', ['id' => $invoice->id])) }} --}}
                                        </td>
                                                    <td colspan="4"></td>

                                                    <td class="text-center"><strong>Grand Amount</strong></td>
                                                    <td class="text-end"><h5 class="m-0">{{ 'KSh ' . number_format($payment->total_amount) }}</h5></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @php
                                    $date = new DateTime('now', new DateTimeZone('Africa/Nairobi'));
                                    @endphp
                                    <i>Printing Time : {{ $date->format('F j, Y, g:i a') }}</i>
                                    <div class="d-print-none">
                                        <div class="float-end">
                                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                            {{-- <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Download</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<style>
    @media (max-width: 768px) {
        .invoice-title h4 {
            float: none;
            text-align: center;
        }
        .text-end {
            text-align: center !important;
        }
        .float-end {
            float: none !important;
        }
    }
</style>

@endsection
