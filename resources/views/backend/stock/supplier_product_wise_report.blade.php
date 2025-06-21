@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="page-content">
    <div class="container-fluid">

       <!-- start page title -->
       <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 brand-heading">Retailer And Product wise  Report</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <strong>Retailer report</strong>
                                <input type="radio" name="supplier_product_wise" value="supplier_wise" class="search_value"> &nbsp; &nbsp;

                                <strong>Product report</strong>
                                <input type="radio" name="supplier_product_wise" value="product_wise" class="search_value">
                            </div>
                        </div> <!-- end row -->

                        <!-- Supplier Wise -->
                        <div class="show_supplier" style="display:none">
                            <form method="GET" action="{{ route('supplier.wise.pdf') }}" id="myForm" target="_blank">
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>Retailer Name</label>
                                        <select name="supplier_id" id="supplier_id" class="form-select select2" aria-label="Default select example">
                                            <option value="">Select Retailer</option>
                                            @foreach($suppliers as $supp)
                                                <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4" style="padding-top:28px">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Supplier Wise -->

                        <!-- Product Wise -->
                        <div class="show_product" style="display:none;">
                            <form method="GET" action="{{ route('product.wise.pdf') }}" id="myForm1" target="_blank">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="category_id" class="form-label">Ingredient Name</label>
                                            <select name="category_id" id="category_id" class="form-select select2" aria-label="Default select example">
                                                <option selected="" disabled>Select Ingredient</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="product_id" class="form-label">Product Name</label>
                                            <select name="product_id" id="product_id" class="form-select select2" aria-label="Default select example">
                                                <option selected="">Please first select product</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" style="padding-top: 28px;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Product Wise -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Scripts -->
<script type="text/javascript">
    $(document).ready(function () {
        // Initialize select2 for all elements with the select2 class
        $('.select2').select2();

        // Initialize validation for both forms
        initializeValidation();

        // Show and hide supplier and product forms based on radio selection
        $(document).on('change', '.search_value', function () {
            var search_value = $(this).val();
            if (search_value === 'supplier_wise') {
                $('.show_supplier').show();
                $('.show_product').hide();
            } else if (search_value === 'product_wise') {
                $('.show_product').show();
                $('.show_supplier').hide();
            }
        });

        // Category change event to fetch products
        $(document).on('change', '#category_id', function () {
            var category_id = $(this).val();
            $.ajax({
                url: "{{ route('get-product') }}",
                type: "GET",
                data: { category_id: category_id },
                success: function (data) {
                    var html = '<option value="">Select Product</option>';
                    $.each(data, function (key, v) {
                        html += '<option value="' + v.id + '">' + v.name + '</option>';
                    });
                    $('#product_id').html(html); // Populate product dropdown
                    $('#product_id').val(''); // Reset product selection
                    $('#product_id').trigger('change'); // Trigger change to reinitialize validation
                }
            });
        });
    });

    // Function to initialize form validation
    function initializeValidation() {
        // Validation for Supplier Form
        $('#myForm').validate({
            rules: {
                supplier_id: {
                    required: true,
                },
            },
            messages: {
                supplier_id: {
                    required: 'Please select a supplier.',
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });

        // Validation for Product Form
        $('#myForm1').validate({
            rules: {
                category_id: {
                    required: true,
                },
                product_id: {
                    required: true,
                },
            },
            messages: {
                category_id: {
                    required: 'Please select a category.',
                },
                product_id: {
                    required: 'Please select a product.',
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                if (element.hasClass('select2')) {
                    // Handle error placement for select2 elements
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    element.closest('.form-group').append(error);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
    }
</script>

@endsection
