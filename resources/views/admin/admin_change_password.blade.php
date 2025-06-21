@extends('admin.admin_master')
@section('admin')
<style>
    @media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
    .col-sm-2 {
        text-align: left;
    }
    .form-control {
        font-size: 14px;
        padding: 10px;
    }
    .btn {
        padding: 10px;
        font-size: 16px;
    }
}

</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Change Password Page</h4><br><br>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shadow-lg border border-danger rounded" role="alert" style="background-color: #f8d7da; color: #721c24; font-family: 'Arial', sans-serif;">
                                <ul class="mb-0 ps-3" style="list-style: square;">
                                    @foreach ($errors->all() as $error)
                                        <li style="font-size: 1rem; font-weight: 500;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; top: 4px; right: 7px;"></button>
                            </div>
                        @endif

                        <form method="post" action="{{ route('update.password') }}">
                            @csrf

                            <!-- Old Password Field -->
                            <div class="row mb-3">
                                <label for="oldpassword" class="col-sm-2 col-form-label">Old Password</label>
                                <div class="col-sm-10">
                                    <input name="oldpassword" class="form-control" type="password" id="oldpassword" required>
                                </div>
                            </div>
                            <!-- end row -->

                            <!-- New Password Field -->
                            <div class="row mb-3">
                                <label for="newpassword" class="col-sm-2 col-form-label">New Password</label>
                                <div class="col-sm-10">
                                    <input name="newpassword" class="form-control" type="password" id="newpassword" required>
                                </div>
                            </div>
                            <!-- end row -->

                            <!-- Confirm Password Field -->
                            <div class="row mb-3">
                                <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input name="confirm_password" class="form-control" type="password" id="confirm_password" required>
                                </div>
                            </div>
                            <!-- end row -->

                            <!-- Submit Button -->
                            <div class="text-center">
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Change Password">
                            </div>
                        </form>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end container -->
</div> <!-- end page-content -->




@endsection
