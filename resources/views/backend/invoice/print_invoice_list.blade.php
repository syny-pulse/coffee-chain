@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">
                            <!-- start page title -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0 brand-heading">Print All Inovices </h4>
                                    </div>
                                </div>
                            </div>
                            <!-- end page title -->


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                 <a href="{{ route('invoice.add') }}" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Add Inovice </i></a> <br>  <br>
                    <h4 class="card-title">All Inovice</h4>

                    <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th>
                            <th>Date </th>
                            <th>Invoice No. </th>
                            <th>Product</th>
                            <th>Quantity</th>
                            {{-- <th>price</th> --}}
                            <th>Total Amount</th>

                            <th>Discount</th>
                            <th>Paid</th>
                            <th>Balance </th>
                             <th>Action</th>
                        </thead>
                        <tbody>

                        	@foreach($allData as $key => $item)
            <tr>
                <td> {{ $key+1}} </td>
                <td>
                    {{ $item->customer_name}}
                 </td>
                <td> {{ date('d-m-Y',strtotime($item->date))  }} </td>
                <td>
                    #{{ $item->invoice_no}}
                </td>
                <td>
                   {{ \Str::limit($item->product_names, 40) }}

                </td>
                <td>
                    {{ \Str::limit($item->quantities,15)}}
                     </td>

                     <td>
                        {{ 'KSh ' . number_format($item->total_amount)}}
                    </td>
                <td>
                    {{ 'KSh ' . number_format($item->discount_amount)}}
                </td>

                <td>
                    {{ 'KSh ' . number_format($item->paid_amount)}}
                </td>
                <td>
                   {{ $item->due_amount}}
               </td>

                <td>
              <a href="{{ route('print.invoice',$item->invoiceid) }}" class="btn btn-danger sm" title="Print Invoice" >  <i class="fa fa-print"></i> </a>
                </td>
            </tr>
                        @endforeach

                        </tbody>
                    </table>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- container-fluid -->
                </div>


@endsection
