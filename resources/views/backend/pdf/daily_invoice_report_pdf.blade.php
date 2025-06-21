@extends('admin.admin_master')
@section('admin')

 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        {{-- <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Daily Invoice Report</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"> </a></li>
                                            <li class="breadcrumb-item active">Daily Invoice Report</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div> --}}
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

    <div class="row">
        <div class="col-12">
            <div class="invoice-title">

                <h3>
                    <img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" height="24"/>&nbsp;&nbsp; Deqow's inventory
                </h3>
            </div>
            <hr>

            <div class="row">
                <div class="col-6 mt-4">
                    <address>
                        <strong>BBS Mall:</strong><br>
                        Easleigh, Nairobi<br>
                    </address>
                </div>
                <div class="col-6 mt-4 text-end">
                    <address>

                    </address>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-12">
            <div>
                <div class="p-2">
                    <h3 class="font-size-16"><strong>Daily Invoice Report
    <span class="btn btn-info"> {{ date('d-m-Y',strtotime($start_date)) }} </span> -
     <span class="btn btn-success"> {{ date('d-m-Y',strtotime($end_date)) }} </span>
                    </strong></h3>
                </div>

            </div>

        </div>
    </div> <!-- end row -->





   <div class="row">
        <div class="col-12">
            <div>
                <div class="p-2">

                </div>
                <div class="">
<div class="table-responsive">
    <table class="table table-bordered dt-responsive nowrap table-striped table-hover">
        <thead class="table-header">
        <tr>
            <td class="text-center"><strong>Sl </strong></td>
            <td class="text-center"><strong>Customer Name </strong></td>
            <td class="text-center"><strong>Invoice No  </strong> </td>
            <td class="text-center"><strong>Date</strong></td>
            <td class="text-center"><strong>Product</strong></td>
            <td class="text-center"><strong>Qty</strong></td>
            <td class="text-center"><strong>Price</strong></td>
            <td class="text-center"><strong>Discount</strong></td>
            <td class="text-center"  style="background-color: #C8E6C9; color:black"><strong>Total Amount  </strong> </td>
            <td class="text-center" style="background-color: #BBDEFB; color: #0D47A1;"><strong>Paid  </strong> </td>
            <td class="text-center" style="background-color: #f2a4a4; color:black"><strong>Balance  </strong> </td>


        </tr>
        </thead>
        <tbody>
        <!-- foreach ($order->lineItems as $line) or some such thing here -->

      @php
        $total_sum = '0';
        $total_paid = '0';
        $total_balance = '0';
        @endphp
        @foreach($allData as $key => $item)
        <tr>
           <td class="text-center">{{ $key+1 }}</td>
            <td class="text-center"> {{ $item->customer_name }} </td>
            <td class="text-center">#{{ $item->invoice_no }}</td>
            <td class="text-center">{{ date('d-m-Y', strtotime($item->date)) }}</td>
            <td class="text-center">{{ \Str::limit($item->product_names, 40) }}</td>
            <td class="text-center">{{ \Str::limit($item->quantities,15) }} </td>
            <td class="text-center"> {{ \Str::limit($item->unit_prices,20)}} </td>
            <td class="text-center">{{ 'KSh ' . number_format($item->discount_amount) }}</td>

            <td class="text-center" style="background-color: #f0fff0; color:black">
                {{ 'KSh ' . number_format($item->total_amount)}}
            </td>
            <td class="text-center"  style="background-color: #e3f2fe; color: #0D47A1;">
                {{ 'KSh ' . number_format($item->paid_amount)}}
            </td>
            <td class="text-center" style="background-color: #fee3e3; color:black">
                {{ 'KSh ' . number_format($item->due_amount)}}
            </td>

        </tr>
         @php
        // $total_sum += $item['payment']['total_amount'];
        $total_sum += $item->total_amount;
        $total_paid += $item->paid_amount;
        $total_balance += $item->due_amount
        @endphp
        @endforeach



            <tr>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                    <td class="no-line text-center">
                        <strong><span class="badge bg-success" style="font-size: 1.0rem; padding: 0.3rem ;">Totals</span></strong>
                    </td>
                <td class="no-line text-end"  style="background-color: #C8E6C9; color:black"><h4 class="m-0">{{ 'KSh ' . number_format($total_sum) }}</h4></td>
                <td class="no-line text-end" style="background-color: #BBDEFB; color: #0D47A1;"><h4 class="m-0">{{ 'KSh ' . number_format($total_paid) }}</h4></td>
                <td class="no-line text-end" style="background-color: #f2a4a4; color:black"><h4 class="m-0">{{ 'KSh ' . number_format($total_balance) }}</h4></td>

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

        </div>
    </div> <!-- end row -->






</div>
</div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>


@endsection
