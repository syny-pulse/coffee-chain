@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Outstanding Balances</h4>



                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

    {{-- <a href="{{ route('customer.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Print Paid Customers </i></a> <br>  <br> --}}
                    <a href="{{ route('paid.customer.print.pdf') }}" class="btn btn-success btn-rounded waves-effect waves-light" target="_black" style="float:right;"><i class="fa fa-print"> Print outstanding balances </i></a> <br>  <br>


                    <h4 class="card-title">Paid All Data </h4>


                    <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th>
                            <th>Invoice No </th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Total Amount</th>
                            <th>Paid</th>
                            <th>Discount</th>
                            <th>Balance</th>
                            <th>Action</th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
                        <tr>
                            <td> {{ $key+1}} </td>
                            <td> {{ $item->customer_name }} </td>
                            <td> #{{ $item->invoice_no }}</td>
                            <td> {{ date('d-m-Y',strtotime($item->date)) }} </td>
            <td >{{ \Str::limit($item->product_names, 40) }}</td>


                            <td> {{ 'Ugx ' . number_format($item->total_amount) }} </td>
                            <td> {{ 'Ugx ' . number_format($item->paid_amount) }} </td>
                            <td> {{ 'Ugx ' . number_format($item->discount_amount) }} </td>
                            <td> {{ 'Ugx ' . number_format($item->due_amount) }} </td>
                            <td>
                                <a href="{{ route('customer.invoice.details.pdf',$item->invoice_id) }}" class="btn btn-info sm" target="_black" title="Customer Details">  <i class="fa fa-eye"></i> </a>


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
