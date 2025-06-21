@extends('admin.admin_master')
@section('admin')

s
 <div class="page-content">
                    <div class="container-fluid">
 <!-- start page title -->
 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">All Stock Report </h4>



        </div>
        <a href="{{ route('stock.report.pdf') }}" target="_blank" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;"><i class="fa fa-print"> Stock Report Print </i></a> <br>  <br>

    </div>
</div>
<!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">




                    <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Retailer Name </th>
                            <th>Unit</th>
                            <th>Ingredient</th>
                            <th>Product Name</th>
                            <th>In Qty</th>
                            <th>Out Qty </th>
                            <th>Stock </th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
        @php
        $buying_total = App\Models\Purchase::where('category_id',$item->category_id)->where('product_id',$item->id)->where('status','1')->sum('buying_qty');
        $selling_total = App\Models\InvoiceDetail::where('category_id',$item->category_id)->where('product_id',$item->id)->where('status','1')->sum('selling_qty');
        @endphp
                          <tr>
                            <td> {{ $key+1}} </td>
                            <td> {{ $item['supplier']['name'] }} </td>
                            <td> {{ $item['unit']['name'] }} </td>
                            <td> {{ $item['category']['name'] }} </td>
                            <td> {{ $item->name }} </td>
                            <td> <span class="btn btn-success"> {{ $buying_total  }}</span>  </td>
                            <td> <span class="btn btn-info"> {{ $selling_total  }}</span> </td>
                            <td> <span class="btn btn-danger"> {{ $item->quantity }}</span> </td>


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
