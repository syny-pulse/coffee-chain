<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Login | Admin </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

         <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        <link href="{{ asset('backend/assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css" />

         <style>
            .card {
            background-color: rgba(255, 255, 255, 0.3);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
               }
            /* Ensure card and form elements scale properly on mobile */
          @media (max-width: 576px) {

                .wrapper-page {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }

                .card-body {
                    padding: 1.5rem;
                    width: 100%;
                    max-width: 400px; /* Optional: Set a max-width for mobile */
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

            /* Responsive Logo Adjustment */
            @media (max-width: 576px) {
                .auth-logo img {
                    height: 25px;
                }
            }


      </style>
    </head>

    <body class="auth-body-bg">
        <div class="bg-overlay"></div>
        <div class="wrapper-page">
            <div class="container-fluid p-0">
                <div class="card">
                    <div class="card-body">

                        <div class="text-center mt-4">
                            <div class="mb-3">
                                <a href="index.html" class="auth-logo">
                                    <img src="{{ asset('backend/assets/images/logo-dark.png') }}" height="30" class="logo-dark mx-auto" alt="">
                                    <img src="{{ asset('backend/assets/images/logo-light.png') }}" height="30" class="logo-light mx-auto" alt="">
                                </a>
                            </div>
                        </div>

                        <h4 class="text-center font-size-18" style="color: #ffffff"><b>Log In</b></h4>

                        <div class="p-3">
 <!-- Error Display -->
 {{-- @if ($errors->any())
 <div class="alert alert-danger">
     <ul>
         @foreach ($errors->all() as $error)
             <li>{{ $error }}</li>
         @endforeach
     </ul>
 </div>
@endif --}}

<!-- Error Display -->
<!-- Error Display -->
@if ($errors->any())
    <div id="error-alert" class="alert alert-danger alert-dismissible fade show shadow-lg border border-danger rounded" role="alert" style="background-color: #ffe6e6; color: #d9534f; font-family: 'Arial', sans-serif;">

        <ul class="mb-0 ps-3" style="list-style: square;">
            @if ($errors->has('login'))
                <li style="font-size: 1rem; font-weight: 500;">{{ $errors->first('login') }}</li>
            @endif
            @if ($errors->has('password'))
                <li style="font-size: 1rem; font-weight: 500;">{{ $errors->first('password') }}</li>
            @endif
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; top: 4px; right: 7px;"></button>
    </div>
@endif




 <form class="form-horizontal mt-3" method="POST" action="{{ route('login') }}">
            @csrf

        <div class="form-group mb-3 row">
            <div class="col-12">
                <input class="form-control" id="login" name="login" type="text" required="" placeholder="Enter username, phone number or email " required>
            </div>
        </div>

        <div class="form-group mb-3 row">
            <div class="col-12">
                <input class="form-control" id="password" name="password" type="password" required="" placeholder="Password">
            </div>
        </div>

        <div class="form-group mb-3 row">
            <div class="col-12">
                <div class="custom-control custom-checkbox">


                </div>
            </div>
        </div>

        <div class="form-group mb-3 text-center row mt-3 pt-1">
            <div class="col-12">
                <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Log In</button>
            </div>
        </div>

        <div class="form-group mb-0 row mt-2">
            <div class="col-sm-7 mt-3">
                <a href="{{ route('password.request') }}" class="" style="color: white"><i class="mdi mdi-lock"></i> Forgot your password?</a>
            </div>
            <div class="col-sm-5 mt-3">
                {{-- <a href="{{ route('register') }}" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a> --}}
            </div>
        </div>
    </form>
                        </div>
                        <!-- end -->
                    </div>
                    <!-- end cardbody -->
                </div>
                <!-- end card -->
            </div>
            <!-- end container -->
        </div>
        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

        <script src="{{ asset('backend/assets/js/app.js') }}"></script>

         <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break;
 }
 @endif
</script>


<script>
    // Automatically fade out the alert after 5 seconds (5000ms)
    setTimeout(() => {
        const alertBox = document.getElementById('error-alert');
        if (alertBox) {
            alertBox.classList.remove('show');
            alertBox.classList.add('fade');
            setTimeout(() => alertBox.remove(), 400); // Remove element after fade transition
        }
    }, 4000);
</script>

    </body>
</html>
