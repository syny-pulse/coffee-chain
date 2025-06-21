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
                <h4 class="mb-sm-0 brand-heading">Pending stocks</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <a href="{{ route('purchase.add') }}" class="btn btn-custom btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle">Stock products </i></a> <br>  <br>



                    {{-- <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-header">
                        <tr>
                            <th>Sl</th>
                            <th>Purhase No</th>
                            <th>Date </th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Product Name</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Action</th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $item->purchase_no }} </td>
                                <td> {{ date('d-m-Y',strtotime($item->date))  }} </td>
                                <td> {{ $item['supplier']['name'] }} </td>
                                <td> {{ $item['category']['name'] }} </td>
                                <td> {{ $item->buying_qty }} {{ $item['product']['unit']['name']}} </td>
                                <td> {{ $item['product']['name'] }} </td>
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
                                    <span>N/A</span>
                                @endif
                                </td>



                                <td>
                                    @if($item->status == '0')
                                    <span class="btn btn-warning">Pending</span>
                                    @elseif($item->status == '1')
                                    <span class="btn btn-success">Approved</span>
                                    @endif
                                    </td>

                                <td>
                                @if($item->status == '0')
                                <a href="{{ route('purchase.approve',$item->id) }} " class="btn btn-success sm" title="Approve this product" id="ApproveBtn">  <i class="fas fa-check-circle"></i> </a>
                                @endif
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table> --}}

                    {{-- <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover">
                        <thead class="table-header">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all" />
                                </th>
                                <th>Sl</th>
                                <th>Purhase No</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Category</th>
                                <th>Qty</th>
                                <th>Product Name</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allData as $key => $item)
                                <tr>
                                    <td>
                                        @if($item->status == '0')
                                            <input type="checkbox" class="select-item" name="selected_ids[]" value="{{ $item->id }}" />
                                        @endif
                                    </td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->purchase_no }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                    <td>{{ $item['supplier']['name'] }}</td>
                                    <td>{{ $item['category']['name'] }}</td>
                                    <td>{{ $item->buying_qty }} {{ $item['product']['unit']['name'] }}</td>
                                    <td>{{ $item['product']['name'] }}</td>
                                    <td>
                                        @if($item->description)
                                            <span class="short-description">{{ \Str::limit($item->description, 20) }}</span>
                                            <span class="full-description" style="display:none;">{{ $item->description }}</span>
                                            <a href="javascript:void(0);" class="read-more-toggle text-primary">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == '0')
                                            <span class="btn btn-warning">Pending</span>
                                        @elseif($item->status == '1')
                                            <span class="btn btn-success">Approved</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == '0')
                                            <a href="{{ route('purchase.approve', $item->id) }}" class="btn btn-success sm" title="Approve this product" id="ApproveBtn">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == '0')
                                            <a href="{{ route('purchase.approve', $item->id) }}" class="btn btn-success sm" title="Approve this product" id="ApproveBtn">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        <button id="approve-selected" class="btn btn-primary mt-3">Approve Selected</button>
                    </div> --}}
                    <table id="datatable" class="table table-bordered dt-responsive nowrap table-striped table-hover">
                        <thead class="table-header">
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all" />
                                </th>
                                <th>Sl</th>
                                <th>Product Name</th>

                                <th>Date</th>
                                <th>Retailer</th>
                                <th>Ingredient</th>
                                <th>Qty</th>
                                <th>Purchase No</th>

                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allData as $key => $item)
                                <tr>
                                    <td>
                                        @if($item->status == '0')
                                            <input type="checkbox" class="select-item" name="selected_ids[]" value="{{ $item->id }}" />
                                        @endif
                                    </td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item['product']['name'] }}</td>

                                    <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                    <td>{{ $item['supplier']['name'] }}</td>
                                    <td>{{ $item['category']['name'] }}</td>
                                    <td>{{ $item->buying_qty }} {{ $item['product']['unit']['name'] }}</td>
                                    <td>{{ $item->purchase_no }}</td>
                                    <td>
                                        @if($item->description)
                                            <span class="short-description">{{ \Str::limit($item->description, 20) }}</span>
                                            <span class="full-description" style="display:none;">{{ $item->description }}</span>
                                            <a href="javascript:void(0);" class="read-more-toggle text-primary">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == '0')
                                            <span class="btn btn-warning">Pending</span>
                                        @elseif($item->status == '1')
                                            <span class="btn btn-success">Approved</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == '0')


                                            <a href="{{ route('purchase.approve', $item->id) }}" class="btn btn-success sm" title="Approve this product" id="ApproveBtn">
                                                <i class="fas fa-check-circle"></i>
                                            </a>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        <button id="approve-selected" class="btn btn-primary mt-3">Approve Selected</button>
                    </div>



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


                    // $(document).ready(function () {
                    //     // Select All Checkbox
                    //     $('#select-all').on('change', function () {
                    //         const isChecked = $(this).prop('checked');
                    //         $('.select-item').prop('checked', isChecked);

                    //         // Update button and action states
                    //         toggleApproveButton();
                    //         toggleRowButtons();
                    //     });

                    //     // Individual Checkbox Change
                    //     $(document).on('change', '.select-item', function () {
                    //         toggleApproveButton();
                    //         toggleRowButtons();
                    //     });

                    //     // Approve Selected Button
                    //     $('#approve-selected').on('click', function () {
                    //         const selectedIds = $('.select-item:checked').map(function () {
                    //             return $(this).val();
                    //         }).get();

                    //         if (selectedIds.length > 0) {
                    //             $.ajax({
                    //                 url: "{{ route('purchase.approveAll') }}", // Bulk approval route
                    //                 method: "POST",
                    //                 data: {
                    //                     _token: "{{ csrf_token() }}",
                    //                     ids: selectedIds
                    //                 },
                    //                 success: function (response) {
                    //                     alert(response.message);
                    //                     location.reload(); // Reload to update status
                    //                 },
                    //                 error: function (error) {
                    //                     console.error(error);
                    //                     alert('An error occurred.');
                    //                 }
                    //             });
                    //         } else {
                    //             alert('Please select at least one item to approve.');
                    //         }
                    //     });

                    //     // Toggle the visibility of the "Approve Selected" button
                    //     function toggleApproveButton() {
                    //         const selectedCount = $('.select-item:checked').length;
                    //         if (selectedCount > 0) {
                    //             $('#approve-selected').show();
                    //         } else {
                    //             $('#approve-selected').hide();
                    //         }
                    //     }

                    //     // Disable individual approve buttons for selected rows
                    //     function toggleRowButtons() {
                    //         $('.select-item').each(function () {
                    //             const row = $(this).closest('tr');
                    //             const approveButton = row.find('#ApproveBtn');

                    //             if ($(this).prop('checked')) {
                    //                 approveButton.prop('disabled', true);
                    //             } else {
                    //                 approveButton.prop('disabled', false);
                    //             }
                    //         });
                    //     }

                    //     // Initialize: Hide "Approve Selected" button by default
                    //     $('#approve-selected').hide();
                    // });
                    $(document).ready(function () {
                        // Select All Checkbox
                        $('#select-all').on('change', function () {
                            const isChecked = $(this).prop('checked');
                            $('.select-item').prop('checked', isChecked);

                            // Update button and action states
                            toggleApproveButton();
                            toggleRowButtons();
                        });

                        // Individual Checkbox Change
                        $(document).on('change', '.select-item', function () {
                            toggleApproveButton();
                            toggleRowButtons();
                        });

                        // Approve Selected Button (when clicked, show confirmation)
                        $(document).on('click', '#approve-selected', function (e) {
                            e.preventDefault(); // Prevent the default behavior

                            var selectedIds = $('.select-item:checked').map(function () {
                                return $(this).val();
                            }).get();

                            // Check if at least one item is selected
                            if (selectedIds.length > 0) {
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "You are about to approve all selected items. This action cannot be undone.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, approve all!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Logic to approve the selected items (trigger AJAX after confirmation)
                                        $.ajax({
                                            url: "{{ route('purchase.approveAll') }}", // Bulk approval route
                                            method: "POST",
                                            data: {
                                                _token: "{{ csrf_token() }}",
                                                ids: selectedIds
                                            },
                                            success: function (response) {
                                                Swal.fire(
                                                    'Approved!',
                                                    'All selected items have been successfully approved.',
                                                    'success'
                                                );
                                                location.reload(); // Reload to update statuses
                                            },
                                            error: function (error) {
                                                console.error(error);
                                                Swal.fire(
                                                    'Error!',
                                                    'There was an issue approving the selected items.',
                                                    'error'
                                                );
                                            }
                                        });
                                    } else {
                                        // If user cancels, show a message
                                        Swal.fire(
                                            'Cancelled',
                                            'The approval process has been cancelled.',
                                            'info'
                                        );
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'No Items Selected!',
                                    'Please select at least one item to approve.',
                                    'info'
                                );
                            }
                        });

                        // Toggle the visibility of the "Approve Selected" button
                        function toggleApproveButton() {
                            const selectedCount = $('.select-item:checked').length;
                            if (selectedCount > 0) {
                                $('#approve-selected').show();
                            } else {
                                $('#approve-selected').hide();
                            }
                        }

                        // Disable individual approve buttons for selected rows
                        function toggleRowButtons() {
                            $('.select-item').each(function () {
                                const row = $(this).closest('tr');
                                const approveButton = row.find('#ApproveBtn');

                                if ($(this).prop('checked')) {
                                    approveButton.prop('disabled', true);
                                    approveButton.hide(); // Hide the approve button when the checkbox is selected
                                } else {
                                    approveButton.prop('disabled', false);
                                    approveButton.show(); // Show the approve button when the checkbox is unselected
                                }
                            });
                        }


                        // Initialize: Hide "Approve Selected" button by default
                        $('#approve-selected').hide();
                    });





                </script>
@endsection
