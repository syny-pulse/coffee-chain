@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex  align-items-center justify-content-between">
                <h4 class="mb-sm-0 brand-heading">All credit Customers </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- <a href="{{ route('paid.customer.print.pdf') }}" class="btn btn-dark btn-rounded waves-effect waves-light" target="_black" style="float:right;"><i class="fa fa-print"> Print Paid Customer </i></a> <br>  <br> --}}



                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th>
                            <th>Invoice No </th>
                            <th>Date</th>

                            <th>Product </th>
                            <th>Amount </th>
                            <th>Paid </th>
                            <th>Balance</th>
                            <th>Action</th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
    <tr>
        <td> {{ $key+1}} </td>
        <td> {{ $item->customer_name }} </td>
         <td>
            #{{ $item->invoice_no}}
             </td>

        <td>
            {{  date('d-m-Y',strtotime($item->date)) }}
        </td>

            <td >{{ \Str::limit($item->product_names, 40) }}</td>

             <td>{{ 'KSh ' . number_format($item->total_amount)}}</td>

             <td>{{ 'KSh ' . number_format($item->paid_amount)}}</td>

        <td >
            {{ 'KSh ' . number_format($item->due_amount) }}


        </td>
        <td>
            <a href="{{ route('customer.edit.invoice',$item->invoice_id) }}" class="btn btn-info sm" title="Edit Data">  <i class="fas fa-edit"></i> </a>

            <a href="{{ route('customer.invoice.details.pdf',$item->invoice_id) }}" target="_blank" class="btn btn-danger sm" title="Customer Invoice Details">  <i class="fa fa-eye"></i> </a>

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
