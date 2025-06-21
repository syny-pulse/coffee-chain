{{-- <x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout> --}}


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Forgot Password | Admin </title>
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

        /* Styling the error messages */
        .alert {
            border-radius: 10px;
            font-family: 'Arial', sans-serif;
        }

        /* Card styles */
        .card-body {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .auth-body-bg {
            background-image: url(../images/bg.webp);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .auth-body-bg .bg-overlay {
            background: rgba(51, 51, 51, 0.5);
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            bottom: 0;
            right: 0px;
            top: 0px;
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

                    <h4 class="text-center font-size-18" style="color: #ffffff"><b>Forgot Password</b></h4>

                    <div class="p-3">
                        <!-- Success Message -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show shadow-lg border border-success rounded" role="alert" style="background-color: #e6ffee; color: #28a745; font-family: 'Arial', sans-serif;">
                                {{ session('status') }}
                                {{-- <p>If your email is correct, a reset link has been sent to your inbox.</p> --}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; top: 4px; right: 7px;"></button>
                            </div>
                        @endif

                        <!-- Error Display -->
                        @if ($errors->any())
                            <div id="error-alert" class="alert alert-danger alert-dismissible fade show shadow-lg border border-danger rounded" role="alert" style="background-color: #ffe6e6; color: #d9534f; font-family: 'Arial', sans-serif;">
                                <ul class="mb-0 ps-3" style="list-style: square;">
                                    @if ($errors->has('email'))
                                        <li style="font-size: 1rem; font-weight: 500;">{{ $errors->first('email') }}</li>
                                    @endif
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="position: absolute; top: 4px; right: 7px;"></button>
                            </div>
                        @endif

                        <form class="form-horizontal mt-3" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group mb-3 row">
                                <div class="col-12">
                                    <input class="form-control" id="email" name="email" type="email" required="" placeholder="Enter your registered email">
                                </div>
                            </div>

                            <div class="form-group mb-3 text-center row mt-3 pt-1">
                                <div class="col-12">
                                    <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Send Reset Link</button>
                                </div>
                            </div>

                            <div class="form-group mb-0 row mt-2">
                                <div class="col-sm-7 mt-3">
                                    <a href="{{ route('login') }}" class="" style="color: white"><i class="mdi mdi-lock"></i> Back to Login</a>
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
            var type = "{{ Session::get('alert-type','info') }}";
            switch(type){
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
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
        }, 5000);
    </script>

</body>
</html>
