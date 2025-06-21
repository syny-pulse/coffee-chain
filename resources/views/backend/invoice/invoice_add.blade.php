@extends('admin.admin_master')
@section('admin')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <style>
    @media (max-width: 768px) {
      .table-sm {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }

      .table-sm th,
      .table-sm td {
        white-space: nowrap;
      }
    }


/* Input responsiveness */
.form-control {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 0.5rem; /* Reduced padding in input fields */
}

.md-3 {
    margin-bottom: 0.5rem; /* Reduced margin for form inputs */
}

@media (max-width: 768px) {
    .col-md-4 {
        width: 100%;
        margin-bottom: 7px; /* Reduced margin for stacking columns */
    }

    /* Adjust buttons and input sizes for mobile */
    .btn {
        width: 70%;
        margin-bottom: 1px; /* Reduced margin for buttons */
    }

    /* Adjust label sizes */
    .form-label {
        font-size: 14px;
        margin-bottom: 0.5rem; /* Reduced margin between label and input */
    }
    .unit_price, .selling_price {
            width: 90px; /* Adjust this value as needed */
        }
        .selling_qty {
            width: 65px; /* Adjust this value as needed */
        }
}

/* Responsive select2 dropdown */
.select2-container {
    width: 100% !important;
}
/* Style the 'New Customer' option in the Select2 dropdown */

.section-header {
    background-color: #635bff;
    padding: 7px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    color: white;
}

.section-header h5 {
    margin: 0;
    font-size: 15px;
    color: #f1e7e7;
}

.section-content {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    background-color: #fff;
}




label i {
    margin-right: 8px; /* Space between icon and text */
    color: #635bff; /* Icon color (blue) */
}

label {
    font-weight: bold; /* Make the label text bold */
}
i{
    color: #635bff
}

  </style>

<div class="page-content">
<div class="container-fluid">
 <!-- start page title -->
 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">Add Invoice </h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">

            {{-- <h4 class="card-title">Add Invoice  </h4><br><br> --}}

            <div class="section-header">
                <h5><i class="fas fa-cart-plus"></i> Step 1: Add Product</h5>
            </div>
            <div class="section-content">
    <div class="row">

         <div class="col-md-1">
            <div class="md-3">
                <label for="example-text-input" class="form-label"><i class="fas fa-file-invoice"></i>Inv No</label>
                 <input class="form-control example-date-input" value="{{ $invoice_no }}" name="invoice_no" type="text"  id="invoice_no" readonly style="background-color:#ddd" >
            </div>
        </div>


        <div class="col-md-2">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Date</label>
                 <input class="form-control example-date-input" required="required" name="date" type="date" value={{ $date }}  id="date">
            </div>
        </div>


        <div class="col-md-3">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Ingredient Name </label>
                <select name="category_id" id="category_id" class="form-select select2" aria-label="Default select example" required>
                <option value="">Select ingredient</option>
                  @foreach($category as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
               @endforeach
                </select>
            </div>
        </div>


         <div class="col-md-3">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Product Name </label>
                <select name="product_id" id="product_id" class="form-select select2" aria-label="Default select example" required>
                <option selected disabled>first select ingredient</option>

                </select>
            </div>
        </div>


           <div class="col-md-1">
                <div class="md-3">
                    <label for="example-text-input" class="form-label">Stock(Pcs/Kg)</label>
                    <input class="form-control example-date-input" name="current_stock_qty" type="text"  id="current_stock_qty" readonly style="background-color:#ddd" >
                </div>
           </div>


                {{-- <label for="example-text-input" class="form-label">Unit price</label> --}}
                 <input class="form-control example-date-input" name="unit_price" type="hidden"  id="current_unit_price" readonly style="background-color:#ddd" >



            <div class="col-md-2">
                <div class="md-3">
                    <label for="example-text-input" class="form-label" style="margin-top:43px;">  </label>


                    {{-- <i class="btn btn-custom btn-rounded waves-effect waves-light fas fa-plus-circle addeventmore"> Add More</i> --}}
                    <i class="btn btn-custom btn-rounded waves-effect waves-light fas fa-plus-circle addeventmore" aria-label="Add product" data-bs-toggle="tooltip" title="Add a new product to the invoice"> Add Product</i>
                </div>
            </div>





    </div> <!-- // end row  -->
            </div>


        </div> <!-- End card-body -->
<!--  ---------------------------------- -->

        <div class="card-body">
            <form method="post" action="{{ route('invoice.store') }}">
            @csrf

            <div class="section-header">
                <h5><i class="fas fa-list-alt"></i> Step 2: Fill Details</h5>
            </div>
            <div class="section-content">

            <table  class="table-sm table-bordered dt-responsive nowrap" width="100%" style="border-color: #ddd;">
                <thead >
                    <tr style="color: rgb(60, 57, 57)">
                        <th width="9%"><i class="fas fa-tags" style="color: #635bff"></i>&nbsp;Ingredient</th>
                        <th width="12%"> <i class="fas fa-cube"></i>&nbsp;Product Name </th>
                        <th width="7%"><i class="fas fa-balance-scale"></i>&nbsp;Quantity (PCS/KG)</th>
                        <th width="10%"><i class="fas fa-dollar-sign"></i>&nbsp;Unit Price </th>
                        <th width="15%"><i class="fas fa-money-bill-wave"></i>&nbsp;Total Price</th>
                        <th width="7%"><i class="fas fa-trash-alt"></i>&nbsp;Action</th>

                    </tr>
                </thead>

                <tbody id="addRow" class="addRow">

                </tbody>

                <tbody>
                    <tr>
                        <td colspan="4"> Discount</td>
                        <td>
                        <input type="text" name="discount_amount" id="discount_amount" class="form-control estimated_amount" placeholder="Discount Amount"  >
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"> Grand Total</td>
                        <td>
                            <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;" >
                        </td>
                        <td></td>
                    </tr>

                </tbody>
            </table>
            <br>



            <div class="form-row">
                <div class="form-group col-md-12">
                    <textarea name="description" class="form-control" id="description" placeholder="Enter remarks Here"></textarea>
                </div>
            </div>
            </div>
            {{-- <br> --}}

            <div class="section-header">
                <h5><i class="fas fa-user"></i> Step 3: Customer Details</h5>
            </div>
            <div class="section-content">

            <div class="row">
                <div class="form-group col-md-3">
                    <label for="paid_status">
                        <i class="fas fa-money-check-alt"></i> Payment Status
                    </label>
                    <select name="paid_status" id="paid_status" class="form-select" required>
                        <option value="">Select Status </option>
                        <option value="full_paid">Full Paid </option>
                        <option value="full_due">Fully Due  </option>
                        <option value="partial_paid">Installments </option>

                    </select>

                 <input type="text" name="paid_amount" class="form-control paid_amount" placeholder="Enter Paid Amount" style="display:none;">
                </div>

                <div class="form-group col-md-9">
                    <label>
                        <i class="fas fa-user"></i> Customer Name  </label>
                        <select name="customer_id" id="customer_id" class="form-select select2" required>
                            <option value="">Select Customer </option>
                            <option value="0" class="new-customer-option">New Customer </option>
                            @foreach($costomer as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->name }} - {{ $cust->mobile_no }}</option>
                            @endforeach
                        </select>
                </div>
            </div> <!-- // end row --> <br>
        </div>

                <!-- Hide Add Customer Form -->
                <div class="row new_customer" style="display:none">
                    <div class="form-group col-md-4">
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Customer Name">
                    </div> <br></br>

                    <div class="form-group col-md-4">
                        <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Enter Customer Mobile No">
                    </div><br></br>

                    {{-- <div class="form-group col-md-4">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Write Customer Email">
                    </div> --}}
                </div>
                <!-- End Hide Add Customer Form -->
                  <br>


                <div class="form-group">
                    <button type="submit" class="btn btn-info" id="storeButton">
                        <i class="fas fa-save"></i> &nbsp; Save Invoice
                    </button>
                </div>

             </form>



        </div> <!-- End card-body -->







    </div>
</div> <!-- end col -->
</div>



</div>
</div>




<script id="document-template" type="text/x-handlebars-template">

<tr class="delete_add_more_item" id="delete_add_more_item">

        <input type="hidden" name="date" value="@{{date}}">
        <input type="hidden" name="invoice_no" value="@{{invoice_no}}">
    <td>
        <input type="hidden" name="category_id[]" value="@{{category_id}}">
        @{{ category_name }}
    </td>

     <td>
        <input type="hidden" name="product_id[]" value="@{{product_id}}">
        @{{ product_name }}
    </td>

     <td>
        <input type="number" min="1" class="form-control selling_qty text-right" required="required" name="selling_qty[]" value="" required>
    </td>

    <td>
        <input type="number" id="unit_price" class="form-control unit_price text-right" required="required" readonly name="unit_price[]" value="@{{unit_price}}">
    {{-- @{{ unit_price }} --}}

    </td>


     <td>
        <input type="number" class="form-control selling_price text-right" name="selling_price[]" value="0" readonly>
    </td>

     <td>
        <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore" data-bs-toggle="tooltip" title="Remove this product"></i>

    </td>

    </tr>

</script>


<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click",".addeventmore", function(){
            var date = $('#date').val();
            var invoice_no = $('#invoice_no').val();
            var category_id  = $('#category_id').val();
            var unit_price  = $('#current_unit_price').val();
            var category_name = $('#category_id').find('option:selected').text();
            var product_id = $('#product_id').val();
            var product_name = $('#product_id').find('option:selected').text();
            if(date == ''){
                $.notify("Date is Required" ,  {globalPosition: 'top right', className:'error' });
                return false;
                 }

                  if(category_id == ''){
                $.notify("Category is Required" ,  {globalPosition: 'top right', className:'error' });
                return false;
                 }
                 if(category_name == ''){
                $.notify("Category is Required" ,  {globalPosition: 'top right', className:'error' });
                return false;
                 }
                  if(product_id == ''){
                $.notify("Product Field is Required" ,  {globalPosition: 'top right', className:'error' });
                return false;
                 }


  // Check if the product is already added
  var isDuplicate = false;
    $("input[name='product_id[]']").each(function () {
        if ($(this).val() == product_id) {
            isDuplicate = true;
            return false; // Break the loop
        }
    });

    if (isDuplicate) {
        $.notify("This product has already been added", { globalPosition: 'top right', className: 'error' });
        return false;
    }

                 var source = $("#document-template").html();
                 var tamplate = Handlebars.compile(source);
                 var data = {
                    date:date,
                    invoice_no:invoice_no,
                    category_id:category_id,
                    unit_price:unit_price,
                    category_name:category_name,
                    product_id:product_id,
                    product_name:product_name
                 };
                 var html = tamplate(data);
                 $("#addRow").append(html);
        });
        $(document).on("click",".removeeventmore",function(event){
            $(this).closest(".delete_add_more_item").remove();
            totalAmountPrice();
        });
        $(document).on('keyup click','.unit_price,.selling_qty', function(){
            var unit_price = $(this).closest("tr").find("input.unit_price").val();
            var qty = $(this).closest("tr").find("input.selling_qty").val();
            var total = unit_price * qty;
            $(this).closest("tr").find("input.selling_price").val(total);
            $('#discount_amount').trigger('keyup');
        });
        $(document).on('keyup','#discount_amount',function(){
            totalAmountPrice();
        });
        // Calculate sum of amout in invoice
        function totalAmountPrice(){
            var sum = 0;
            $(".selling_price").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length != 0){
                    sum += parseFloat(value);
                }
            });

            var discount_amount = parseFloat($('#discount_amount').val());
            if(!isNaN(discount_amount) && discount_amount.length != 0){
                    sum -= parseFloat(discount_amount);
                }
            $('#estimated_amount').val(sum);
        }
    });
</script>


<script type="text/javascript">
    $(function(){
        $(document).on('change','#category_id',function(){
            var category_id = $(this).val();
            $.ajax({
                url:"{{ route('get-product') }}",
                type: "GET",
                data:{category_id:category_id},
                success:function(data){
                    var html = '<option value="">Select Product</option>';
                    $.each(data,function(key,v){
                        html += '<option value=" '+v.id+' "> '+v.name+'</option>';
                    });
                    $('#product_id').html(html);

                }
            })
        });
    });
</script>


<script type="text/javascript">
    $(function(){
        $(document).on('change','#product_id',function(){
            var product_id = $(this).val();
            $.ajax({
                url:"{{ route('check-product-stock') }}",
                type: "GET",
                data:{product_id:product_id},
                success:function(data){
                    $('#current_stock_qty').val(data);
                }
            });
        });
    });
</script>

<script type="text/javascript">
    $(function(){
        $(document).on('change','#product_id',function(){
            var product_id = $(this).val();
            $.ajax({
                url:"{{ route('check-unit-price') }}",
                type: "GET",
                data:{product_id:product_id},
                success:function(data){
                    if(data == null){
                    $('#current_unit_price').val(0);


                    }else{
                    $('#current_unit_price').val(data);

                    }
                }
            });
        });
    });
</script>

<script type="text/javascript">



    $(document).ready(function() {
        // Show/hide the paid amount input when the paid status changes
        $(document).on('change', '#paid_status', function() {
            var paid_status = $(this).val();
            if (paid_status == 'partial_paid') {
                $('.paid_amount').show();
            } else {
                $('.paid_amount').hide().val('').removeClass('is-invalid'); // Reset if hidden
                $('.error-message').remove(); // Remove previous errors
            }
        });

        // Validate when clicking the submit button
        $('#storeButton').click(function(e) {
            let valid = true;

            // Validate paid amount if "Partial Paid" is selected
            if ($('#paid_status').val() === 'partial_paid') {
                let paidAmountInput = $('.paid_amount');

                if (paidAmountInput.val().trim() === '') {
                    paidAmountInput.addClass('is-invalid');

                    if (!paidAmountInput.next('.error-message').length) {
                        paidAmountInput.after('<span class="error-message text-danger">Paid amount is required</span>');
                    }

                    valid = false;
                } else {
                    paidAmountInput.removeClass('is-invalid');
                    paidAmountInput.next('.error-message').remove();
                }
            }

            if (!valid) {
                e.preventDefault(); // Stop form submission if validation fails
            }
        });
    });

    $(document).ready(function() {
        // Initialize select2
        $('.select2').select2();

        // Bind change event to select2
        $('#customer_id').on('change', function() {
            var customer_id = $(this).val();

            if (customer_id == '0') {
                $('.new_customer').show();
            } else {
                $('.new_customer').hide();

                // Clear input fields and remove validation errors
                $('#name').val('').removeClass('is-invalid');
                $('.error-message').remove();
            }
        });

        // Validate only the customer name field
        $('#storeButton').click(function(e) {
            if ($('.new_customer').is(':visible')) {
                let valid = true;
                let nameInput = $('#name');

                if (nameInput.val().trim() === '') {
                    nameInput.addClass('is-invalid');

                    if (!nameInput.next('.error-message').length) {
                        nameInput.after('<span class="error-message text-danger">Customer name is required</span>');
                    }

                    valid = false;
                } else {
                    nameInput.removeClass('is-invalid');
                    nameInput.next('.error-message').remove();
                }

                if (!valid) {
                    e.preventDefault(); // Prevent form submission if validation fails
                }
            }
        });
        
    });

    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

</script>




@endsection
