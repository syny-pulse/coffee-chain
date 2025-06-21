@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">
 <!-- start page title -->
 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">All transactions</h4>



        </div>
    </div>
</div>
<!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- <a href="{{ route('paid.customer.print.pdf') }}" class="btn btn-dark btn-rounded waves-effect waves-light" target="_black" style="float:right;"><i class="fa fa-print"> Print Paid Customer </i></a> <br>  <br> --}}


                    <h4 class="card-title">Paid All Data </h4>


                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Customer Name</th>
                            <th>Invoice No </th>
                            <th>Product </th>
                            <th>Date</th>
                            <th>Total AMount</th>
                            <th>Paid</th>
                            <th>Due Amount</th>
                            <th>Action</th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
                        <tr>
                            <td> {{ $key+1}} </td>
                            <td> {{ $item->customer_name}} </td>
                            <td> #{{ $item->invoice_no}}</td>

            <td >{{ \Str::limit($item->product_names, 40) }}</td>

                            <td> {{ date('d-m-Y',strtotime($item->date)) }} </td>
                           <td>{{ 'Ugx ' . number_format($item->total_amount) }}</td>


                            <td>
                                 {{ 'Ugx ' . number_format($item->paid_amount) }}
                                 @if($item->due_amount == 0)
                                 <span class="text-success" title="Fully Paid">
                                     <i class="fa fa-check-circle"></i>
                                 </span>
                             @else
                                 <span class="text-warning" title="Partially Paid">
                                     <i class="fa fa-exclamation-circle"></i>
                                 </span>
                             @endif
                            </td>
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
