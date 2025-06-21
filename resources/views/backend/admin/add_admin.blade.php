@extends('admin.admin_master')
@section('admin')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
<div class="container-fluid">

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Add Admin </h4><br><br>



            <form method="post" action="{{ route('admin.user.store') }}" id="myForm" enctype="multipart/form-data">
                @csrf

            <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">Admin full Name </label>
                <div class="form-group col-sm-10">
                    <input name="name" class="form-control" type="text" placeholder="enter Full name"      >
                </div>
            </div>
            <!-- end row -->

            <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">username Name </label>
                <div class="form-group col-sm-10">
                    <input name="username" class="form-control" type="text"  placeholder="enter username"  >
                </div>
            </div>
            <!-- end row -->



              <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">Phone number </label>
                <div class="form-group col-sm-10">
                    <input name="mobile_no" class="form-control" type="text"    >
                </div>
            </div>
            <!-- end row -->


  <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">Customer Email </label>
                <div class="form-group col-sm-10">
                    <input name="email" class="form-control" type="email"  >
                </div>
            </div>
            <!-- end row -->


  <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">Customer Address </label>
                <div class="form-group col-sm-10">
                    <input name="address" class="form-control" type="text"  >
                </div>
            </div>
            <!-- end row -->

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Asign Roles </label>

				<div class="col-sm-10 text-secondary">
                    <select name="roles" class="form-select mb-3" aria-label="Default select example">
						<option selected="">Open this select menu</option>
						@foreach($roles as $role)
						<option value="{{ $role->id }}">{{ $role->name }}</option>
						 @endforeach
					</select>
				</div>
			</div>

            <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">Customer Image </label>
                <div class="form-group col-sm-10">
       <input name="photo" class="form-control" type="file"  id="image">
                </div>
            </div>
            <!-- end row -->
              <div class="row mb-3">
                 <label for="example-text-input" class="col-sm-2 col-form-label">  </label>
                <div class="col-sm-10">
   <img id="showImage" class="rounded avatar-lg" src="{{  url('upload/no_image.jpg') }}" alt="Card image cap">
                </div>
            </div>
            <!-- end row -->








<input type="submit" class="btn btn-info waves-effect waves-light" value="Register admin">
            </form>



        </div>
    </div>
</div> <!-- end col -->
</div>



</div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                },
                 mobile_no: {
                    required : true,
                },

                 address: {
                    required : true,
                },
                password: {
                    required : true,
                },
                //  customer_image: {
                //     required : true,
                // },
            },
            messages :{
                name: {
                    required : 'Please Enter Your Name',
                },
                mobile_no: {
                    required : 'Please Enter Your Mobile Number',
                },

                address: {
                    required : 'Please Enter Your Address',
                },
                password: {
                    required : 'Please Select one Image',
                 },
            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });

</script>


<script type="text/javascript">

    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>



@endsection
