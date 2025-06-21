@extends('admin.admin_master')
@section('admin')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

 <style>

 /* Table responsiveness */
.table-sm {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
}

.table-sm th,
.table-sm td {
    padding: 0.75rem;
    text-align: left;
}

.table-sm th {
    background-color: #635bff;
    border-bottom: 2px solid #e8d7d7;
}
i{
    color: #eeeeef
}

/* Make the table scrollable on small screens */
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
    .unit_price {
            width: 100px; /* Adjust this value as needed */
        }
         .buying_price {
            width: 120px; /* Adjust this value as needed */
        }
        .desc {
            width: 190px; /* Adjust this value as needed */
        }
}

/* Responsive select2 dropdown */
.select2-container {
    width: 100% !important;
}


 </style>

<div class="page-content">
<div class="container-fluid">
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 brand-heading">Stock Items  </h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">


    <div class="row">
        <div class="col-md-4">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Date</label>
                 <input class="form-control example-date-input" name="date" type="date"  id="date">
            </div>
        </div>

        <div class="col-md-4">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Stock No.</label>
                 {{-- <input class="form-control example-date-input" name="purchase_no" type="text"  id="purchase_no"> --}}
                 <input  name="purchase_no" type="text"class="form-control" value="STK-{{ substr(rand(0,time()),0,7) }}" placeholder="Purchase No" id="purchase_no" readonly required>
            </div>
        </div>


        <div class="col-md-4">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Retailer Name </label>
                <select id="supplier_id" name="supplier_id" class="form-select select2" aria-label="Default select example" >
                <option value="">select retailer</option>
                @foreach($supplier as $supp)
                <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                @endforeach
                </select>
            </div>
        </div>


       <div class="col-md-4">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Ingredient Name </label>
                <select name="category_id" id="category_id" class="form-select select2" aria-label="Default select example" required>
                <option selected disabled>select ingredient</option>

                </select>
            </div>
        </div>


         <div class="col-md-4">
            <div class="md-3">
                <label for="example-text-input" class="form-label">Product Name </label>
                <select name="product_id" id="product_id" class="form-select select2" aria-label="Default select example" required>
                <option selected="" disabled>select product</option>

                </select>
            </div>
        </div>


        <div class="col-md-4">
            <div class="md-3">
                <label for="example-text-input" class="form-label" style="margin-top:43px;">  </label>


                <i class="btn btn-custom btn-rounded waves-effect waves-light fas fa-plus-circle addeventmore"> Add item</i>
            </div>
        </div>





    </div> <!-- // end row  -->

        </div> <!-- End card-body -->





<!--  ---------------------------------- -->
<div class="card-body">
    <form method="post" action="{{ route('purchase.store') }}" >
            @csrf
        <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
            <thead class="table-header">
                <tr>
                    <th width="9%">
                        <i class="fas fa-tags"></i>&nbsp; Category</th>
                    <th width="12%">
                        <i class="fas fa-cube"></i>&nbsp;Product Name </th>
                    <th width="10%"><i class="fas fa-balance-scale"></i>&nbsp;Quantity(PCS/KG)</th>
                    <th width="10%"><i class="fas fa-dollar-sign"></i>&nbsp;Unit Price </th>
                    <th width="21%">Description</th>
                    <th width="14%"><i class="fas fa-money-bill-wave"></i>&nbsp;Total Price</th>
                    <th width="7%">Action</th>

                </tr>
            </thead>

            <tbody id="addRow" class="addRow">

            </tbody>

            <tbody>
                <tr>
                    <td colspan="5"></td>
                    <td>
                        <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;" >
                    </td>
                    <td></td>
                </tr>

            </tbody>
        </table><br>
        <div class="form-group">
            <button type="submit" class="btn btn-success" id="storeButton"> Stock product</button>

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
            <input type="hidden" name="date[]" value="@{{date}}">
            <input type="hidden" name="purchase_no[]" value="@{{purchase_no}}">
            <input type="hidden" name="supplier_id[]" value="@{{supplier_id}}">

        <td>
            <input type="hidden" name="category_id[]" value="@{{category_id}}">
            @{{ category_name }}
        </td>

         <td>
            <input type="hidden" name="product_id[]" value="@{{product_id}}">
            @{{ product_name }}
        </td>

         <td>
            <input type="number" min="1" class="form-control buying_qty text-right" name="buying_qty[]" value="" required>
        </td>

        <td>
            <input type="number" class="form-control unit_price text-right" name="unit_price[]" value="" required>
        </td>

        <td>
            <input type="text" class="form-control desc" name="description[]">
        </td>

         <td>
            <input type="number" class="form-control buying_price text-right" name="buying_price[]" value="0" readonly>
        </td>

         <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
        </td>

        </tr>

    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on("click",".addeventmore", function(){
                var date = $('#date').val();
                var purchase_no = $('#purchase_no').val();
                var supplier_id = $('#supplier_id').val();

                var category_id  = $('#category_id').val();
                var category_name = $('#category_id').find('option:selected').text();
                var product_id = $('#product_id').val();
                var product_name = $('#product_id').find('option:selected').text();
                if(date == ''){
                    $.notify("Date is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;
                     }
                      if(purchase_no == ''){
                    $.notify("Purchase No is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;
                     }
                      if(supplier_id == ''){
                    $.notify("Supplier is Required" ,  {globalPosition: 'top right', className:'error' });
                    return false;

                     }
                      if(category_id == ''){
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
        $.notify("Product is already added", { globalPosition: 'top right', className: 'error' });
        return false;
    }
                     var source = $("#document-template").html();
                     var tamplate = Handlebars.compile(source);

                     var data = {
                    date:date,
                    purchase_no:purchase_no,
                    supplier_id:supplier_id,
                    category_id:category_id,
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
                $(document).on('input', 'input[name="description[]"]', function () {
            const maxLength = 110;
            const currentLength = $(this).val().length;
            if (currentLength >= maxLength) {
                $.notify("Character limit reached.Please shorten your text.", { globalPosition: 'top right', className: 'warn' });
            }
        });

        $(document).on('keyup click','.unit_price,.buying_qty', function(){
            var unit_price = $(this).closest("tr").find("input.unit_price").val();
            var qty = $(this).closest("tr").find("input.buying_qty").val();
            var total = unit_price * qty;
            $(this).closest("tr").find("input.buying_price").val(total);
            totalAmountPrice();
        });
         // Calculate sum of amout in invoice
         function totalAmountPrice(){
            var sum = 0;
            $(".buying_price").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length != 0){
                    sum += parseFloat(value);
                }
            });
            $('#estimated_amount').val(sum);
        }

        });
    </script>

<script type="text/javascript">
    $(function(){
        $(document).on('change','#supplier_id',function(){
            var supplier_id = $(this).val();
            $.ajax({
                url:"{{ route('get-category') }}",
                type: "GET",
                data:{supplier_id:supplier_id},
                success:function(data){
                    var html = '<option value="">Select Category</option>';
                    $.each(data,function(key,v){
                        html += '<option value=" '+v.category_id+' "> '+v.category.name+'</option>';
                    });
                    $('#category_id').html(html);
                }
            })
        });
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


@endsection
