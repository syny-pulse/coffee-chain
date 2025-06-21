@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">All customers</h4>



        </div>
        <a href="{{ route('customer.add') }}" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Add Customer </i></a> <br>  <br>

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
                            <th>Name</th>
                            <th>Customer Image </th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Action</th>

                        </thead>


                        <tbody>

                        	@foreach($customers as $key => $item)
                        <tr>
                            <td> {{ $key+1}} </td>
                            <td> {{ $item->name }} </td>
                            @if($item->customer_image == null)
                                <td> <img src="{{ asset('noimage.jpg') }}" style="width:60px; height:60px"> </td>
                            @else
                                <td > <img src="{{ asset( $item->customer_image ) }}" style="width:65px; height:65px; border-radius:45px"> </td>
                            @endif


                              <td> {{ $item->email }} </td>
                               <td> {{ $item->address }} </td>
                            <td>
                                <a href="{{ route('customer.edit',$item->id) }}" class="btn btn-info sm" title="Edit Data">  <i class="fas fa-edit"></i> </a>

                            <a href="{{ route('customer.delete',$item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete">  <i class="fas fa-trash-alt"></i> </a>

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
