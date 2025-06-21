@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<style>

    /* Modal Custom Styling */
   .modal-dialog {
       max-width: 500px; /* Reduced width */
       margin: 30px auto; /* Centers the modal */
   }

   .modal-content {
       border-radius: 8px;
       box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
       background-color: #f9f9f9;
   }

   .modal-header {
       background-color: #4caf50;
       color: white;
       padding: 15px;
       font-size: 1.25rem;
       border-radius: 8px 8px 0 0;
   }

   .modal-title {
       font-weight: bold;
       color:white
   }

   .modal-body {
       padding: 20px;
       font-size: 1rem;
       color: #333;
       text-align: justify;
   }

   .modal-footer {
       background-color: #f1f1f1;
       padding: 15px;
       border-radius: 0 0 8px 8px;
   }

   .btn-close {
       background: none;
       border: none;
       font-size: 1.25rem;
       color: #fff;
       cursor: pointer;
   }

   .btn-close:hover {
       color: #4caf50;
   }

   .btn-secondary {
       background-color: #4caf50;
       border-color: #4caf50;
   }

   .btn-secondary:hover {
       background-color: #45a049;
       border-color: #45a049;
   }

   /* Responsive Adjustments */
   @media (max-width: 768px) {
       .modal-dialog {
           max-width: 80%; /* Adjust the width for smaller screens */
       }
   }

   </style>

 <div class="page-content">
                    <div class="container-fluid">
 <!-- start page title -->
 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">All purchases</h4>
        </div>
    </div>
</div>
<!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <a href="{{ route('purchase.add') }}" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Stock products </i></a> <br>  <br>



                    <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Product Name</th>
                            <th>Date </th>

                            <th>Purhase No</th>
                            <th>Retailer</th>
                            <th>Ingredient</th>
                            <th>Qty</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Action</th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
            <tr>
                <td> {{ $key+1}} </td>
                <td> {{ $item['product']['name'] }} </td>
                <td> {{ date('d-m-Y',strtotime($item->date))  }} </td>

                <td> {{ $item->purchase_no }} </td>
                 <td> {{ $item['supplier']['name'] }} </td>
                 <td> {{ $item['category']['name'] }}</td>
                 <td> {{ $item->buying_qty }} {{ $item['product']['unit']['name'] }}</td>
                 <td>
                    @if($item->description)
                    <span class="short-description">
                        {{ \Str::limit($item->description, 20) }}
                    </span>
                    <span class="full-description" style="display:none;">
                        {{ $item->description }}
                    </span>
                    <a href="javascript:void(0);" class="read-more-toggle text-primary">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                @else
                    <!-- Empty or placeholder text if no description -->
                    <span>N/A</span>
                @endif
                </td>
                 @if ($item->status == 0)
                 <td> <span class="btn btn-warning">Pending</span> </td>
                 @elseif ($item->status == 1)
                 <td> <span class="btn btn-success">Approved</span> </td>
                 @endif

                <td>
                    @if ($item->status == 0)
                        <a href="{{ route('purchase.delete',$item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete">  <i class="fas fa-trash-alt"></i> </a>
                    @elseif ($item->status == 1)
                    @endif

                </td>

            </tr>
                        @endforeach

                        </tbody>
                    </table>
{{-- modal --}}
<!-- Modal for full description -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Full Remarks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modal-description-content" style="word-wrap: break-word; line-height: 1.6;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->



                    </div> <!-- container-fluid -->
                </div>


                <script type="text/javascript">
                    $(document).on('click', '.read-more-toggle', function() {
                        var fullDescription = $(this).siblings('.full-description').text(); // Get full description
                        $('#modal-description-content').text(fullDescription); // Insert into modal
                        $('#descriptionModal').modal('show'); // Show the modal
                    });
                </script>
@endsection
