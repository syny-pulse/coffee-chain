@extends('admin.admin_master')
@section('admin')

<style>


</style>
 <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

    <div class="row">
        <div class="col-12">
            <div class="invoice-title">

                <h3>
                    <img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" height="24"/> &nbsp;&nbsp; Deqow's Inventory
                </h3>
            </div>
            <hr>

            <div class="row">
                <div class="col-6 mt-4">
                    <address>
                        <strong> BBS Mall:</strong><br>
                        Nairobi, Eastleigh<br>
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
                    <h3 class="font-size-16"><strong>Daily Purchase Report
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
    <table class="table">
        <thead class="table-header" style="color:rgb(230, 221, 221)">
        <tr>
            <td><strong>Sl </strong></td>
            <td class="text-center"><strong>Purchase No </strong></td>
            <td class="text-center"><strong>Date  </strong>
            </td>
            <td class="text-center"><strong>Product Name</strong>
            </td>
            <td class="text-center"><strong>Remarks</strong>
            </td>
            <td class="text-center"><strong>Quantity</strong>
            </td>
            <td class="text-center"><strong>Unit Price  </strong>
            </td>
            <td class="text-center" style="background-color: #C8E6C9; color:black"><strong>Total Price  </strong>
            </td>


        </tr>
        </thead>
        <tbody>
        <!-- foreach ($order->lineItems as $line) or some such thing here -->

      @php
        $total_sum = '0';
        @endphp
        @foreach($allData as $key => $item)
        <tr>
           <td class="text-center">{{ $key+1 }}</td>
            <td class="text-center">{{ $item->purchase_no }}</td>
            <td class="text-center">{{ date('d-m-Y',strtotime($item->date)) }}</td>
            <td class="text-center">{{ $item['product']['name'] }}</td>
            {{-- <td class="text-center">{{ $item->description }}</td> --}}
            <td>
                @if($item->description)
                    <span class="short-description">
                        {{ \Str::limit($item->description, 45) }}
                    </span>
                    <span class="full-description" style="display:none;">
                        {{ $item->description }}
                    </span>
                @else
                    <span>N/A</span>
                @endif
            </td>




            <td class="text-center">{{ $item->buying_qty }} {{ $item['product']['unit']['name'] }} </td>
            <td class="text-center">{{ 'KSh ' . number_format($item->unit_price) }}</td>
            <td class="text-center" style="background-color: #f0fff0; color:black">{{ 'KSh ' . number_format($item->buying_price) }}</td>


        </tr>
         @php
        $total_sum += $item->buying_price;
        @endphp
        @endforeach



            <tr>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line"></td>
                <td class="no-line text-center">
                    {{-- <strong>Grand </strong> --}}
                    <strong><span class="badge bg-success" style="font-size: 1.0rem; padding: 0.3rem ;">Total Amount</span></strong>

                </td>
                <td class="no-line text-end" style="background-color: #C8E6C9; color:black"><h4 class="m-0">{{ 'KSh ' . number_format($total_sum)}}</h4></td>
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
