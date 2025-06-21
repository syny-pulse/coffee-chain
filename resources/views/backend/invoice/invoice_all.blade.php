{{-- @extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ route('invoice.add') }}" class="btn btn-light-green btn-rounded waves-effect waves-light" style="float:right;">
                            <i class="fas fa-plus-circle"> Add Invoice </i>
                        </a>
                        <br><br>

                        <h4 class="card-title">Invoice All Data</h4>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped  table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-header"> <!-- Added class for styling the header -->
                                <tr>
                                    <th>Sl</th>
                                    <th>Customer Name</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($allData as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->customer_name }}</td>
                                    <td>#{{ $item->invoice_no }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->selling_qty }} {{$item->unit_name}}</td>
                                    <td>{{ 'KSh ' . number_format($item->unit_price) }}</td>
                                    <td>{{ 'KSh ' . number_format($item->discount_amount) }}</td>
                                    <td>{{ 'KSh ' . number_format($item->total_amount) }}</td>
                                    <td>{{ 'KSh ' . number_format($item->paid_amount) }}</td>
                                    <td>{{ 'KSh ' . number_format($item->due_amount) }}</td>
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

@endsection --}}


@extends('admin.admin_master')
@section('admin')

<!-- Custom CSS -->
<style>
    /* Custom styles for the page */
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .table-header {
        background-color: #635bff;
        color: white;
    }

    .table-header th {
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(99, 91, 255, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(99, 91, 255, 0.1);
    }

    .btn-custom {
        background-color: #635bff;
        color: white;
        border-radius: 25px;
        padding: 10px 20px;
        font-weight: 500;
    }

    .btn-custom:hover {
        background-color: #4a43d1;
    }

    .btn-info, .btn-danger {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
    }

    .btn-info:hover, .btn-danger:hover {
        opacity: 0.9;
    }

    .brand-heading {
        color: #635bff;
        font-weight: 600;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading">All Approved Invoices</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Add Invoice Button -->
                        <a href="{{ route('invoice.add') }}" class="btn btn-custom btn-rounded waves-effect waves-light mb-4" style="float:right;">
                            <i class="fas fa-plus-circle"></i> Add Invoice
                        </a>
                        <br><br>

                        <!-- Invoice Table -->
                        <h4 class="card-title mb-4">Invoice All Data</h4>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover" style="width: 100%;">
                                <thead class="table-header">
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Total Amount</th>
                                        <th>Discount</th>
                                        <th>Paid</th>
                                        <th>Due Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allData as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ \Str::limit($item->customer_name, 20) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                        <td>{{ \Str::limit($item->product_names, 30) }}</td>
                                        <td>{{ \Str::limit($item->quantities, 20) }}</td>
                                        <td>{{ 'KSh ' . number_format($item->total_amount) }}</td>
                                        <td>{{ 'KSh ' . number_format($item->discount_amount) }}</td>
                                        <td>{{ 'KSh ' . number_format($item->paid_amount) }}</td>
                                        <td>{{ 'KSh ' . number_format($item->due_amount) }}</td>
                                        <td>
                                            <a href="{{ route('invoice.details', $item->invoice_id) }}" class="btn btn-info btn-sm" target="_blank" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('print.invoice', $item->invoice_id) }}" class="btn btn-danger btn-sm" title="Print Invoice">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


