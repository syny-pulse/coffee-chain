{{-- @extends('admin.admin_master')
@section('admin')

<div class="page-content">
<div class="container-fluid">


<div class="row">
    <div class="col-lg-6">
        <div class="card"><br><br>
<center>
            <img class="rounded-circle avatar-xl" src="{{ (!empty($adminData->profile_image))? url('upload/admin_images/'.$adminData->profile_image):url('upload/no_image.jpg') }}" alt="Card image cap">
</center>

            <div class="card-body">
                <h4 class="card-title">Name : {{ $adminData->name }} </h4>
                <hr>
                <h4 class="card-title">User Email : {{ $adminData->email }} </h4>
                <hr>
                <h4 class="card-title">User Name : {{ $adminData->username }} </h4>
                <hr>
                <a href="{{ route('edit.profile') }}" class="btn btn-info btn-rounded waves-effect waves-light" > Edit Profile</a>

            </div>
        </div>
    </div>


                        </div>


</div>
</div>

@endsection  --}}


@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <!-- Profile Card with Cover Image and Profile Image -->
                <div class="card shadow-lg border-0 rounded-5">
                    <!-- Cover Photo Section with Background Color -->
                    <div class="card-header p-0 position-relative" style="background-color: #6e9f71; height: 120px;">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <!-- Profile Image with Light Green Background -->
                            <img class="rounded-circle avatar-lg border-4 border-white"
                            src="{{ (!empty($adminData->profile_image))? url('upload/admin_images/'.$adminData->profile_image):url('upload/no_image.jpg') }}"
                            alt="Profile Image"
                             style="width: 120px; height: 120px; box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1); background-color: #d1f7c4;">
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body pt-5" style="background-color: #ffffff;">
                        <!-- Profile Name and Username Section -->
                        <div class="text-center mb-4">
                            <h3 class="font-weight-bold text-dark" style="font-size: 26px;">{{ $adminData->name }}</h3>
                            <h5 class="text-muted" style="font-size: 18px;">@ {{ $adminData->username }}</h5>
                        </div>

                        <!-- Divider Line -->
                        <hr style="border-top: 2px solid rgba(0, 0, 0, 0.1); width: 50%; margin: 0 auto 30px;">

                        <!-- Personal Info Section -->
                        <div class="mb-4">
                            <h5 class="text-dark mb-3" style="font-size: 18px;">Personal Information</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="mdi mdi-account-outline me-2"></i>Name:</span>
                                <span class="font-weight-bold">{{ $adminData->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="mdi mdi-email-outline me-2"></i>Email:</span>
                                <span class="font-weight-bold">{{ $adminData->email }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="mdi mdi-account-circle-outline me-2"></i>Username:</span>
                                <span class="font-weight-bold">{{ $adminData->username }}</span>
                            </div>
                        </div>

                        <!-- Account Info Section -->
                        <div class="mb-4">
                            <h5 class="text-dark mb-3" style="font-size: 18px;">Account Settings</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="mdi mdi-calendar-clock me-2"></i>Joined:</span>
                                <span class="font-weight-bold">{{ $adminData->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="mdi mdi-lock-outline me-2"></i>Password:</span>
                                <span class="font-weight-bold">**********</span>
                            </div>
                        </div>

                        <!-- Edit Profile Button -->
                        <div class="text-center">
                            <a href="{{ route('edit.profile') }}" class="btn btn-light btn-rounded px-4 py-2" style="background-color: #ffffff; color: #388e3c; font-size: 16px; border-color:green">
                                <i class="mdi mdi-account-edit me-1"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Profile Card -->
            </div>
        </div>
    </div>
</div>

@endsection
