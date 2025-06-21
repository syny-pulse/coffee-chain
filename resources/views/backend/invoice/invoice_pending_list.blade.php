@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">


        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading">pending Invoices</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Updated button with background color -->
                        <a href="{{ route('invoice.add') }}" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;">
                            <i class="fas fa-plus-circle"> Add Invoice </i>
                        </a>
                        <br><br>

                        <h4 class="card-title">Invoice All Data</h4>

                        <!-- Table with updated header background color -->
                        <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-header">
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Quantities</th>
                                    <th>Total Amount</th>
                                    {{-- <th>Description</th> --}}

                                    <th>Discount</th>

                                    {{-- <th>Amount</th> --}}
                                    <th>Paid</th>
                                    {{-- <th>Balance</th> --}}

                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($allData as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->customer_name }}</td>
                                    <td>#{{ $item->invoice_no }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                    <td>
                   {{ \Str::limit($item->product_names, 40) }}

                                    </td>
                                    <td>{{ \Str::limit($item->quantities,15) }} </td>
                                    <td>{{ 'KSh ' . number_format($item->total_amount)}} </td>


                                    {{-- <td>{{ 'KSh ' . number_format($item->unit_price) }}</td> --}}
                                    {{-- <td> {{ $item->description }} </td> --}}

                                    <td>{{ 'KSh ' . number_format($item->discount_amount) }}</td>

                                    {{-- <td>{{ 'KSh ' . number_format($item->total_amount) }}</td> --}}
                                    <td>{{ 'KSh ' . number_format($item->paid_amount) }}</td>
                                    {{-- <td>{{ 'KSh ' . number_format($item->due_amount) }}</td> --}}

                                    <td>
                                        @if($item->status == '0')
                                        <span class="btn btn-warning">Pending</span>
                                        @elseif($item->status == '1')
                                        <span class="btn btn-success">Approved</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($item->status == '0')
                                        <a href="{{ route('invoice.approve',$item->invoiceid) }}" class="btn btn-success sm" title="Approve this invoice">
                                            <i class="fas fa-check-circle"></i>
                                        </a>
                                        <a href="{{ route('invoice.delete',$item->invoiceid) }}" class="btn btn-danger sm" title="Delete invoice" id="delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        @endif
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
