<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Deqow’s Inventory Hub </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

         <!-- Select 2 -->
        <link href="{{ asset('backend/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
        <!-- end Select 2  -->

        <!-- jquery.vectormap css -->
        <link href="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

        <!-- DataTables -->
        <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> --}}

<style>
         /* Primary Colors */
         :root {
            --primary-purple: #635bff;
            --teal: #00b8b8;
            --white: #ffffff;
        }

        /* Button Styling */
        .btn-info {
            background-color: var(--primary-purple) !important;
            border-color: var(--primary-purple) !important;
        }

        .btn-info:hover {
            background-color: var(--teal) !important;
            border-color: var(--teal) !important;
        }

        /* Table Header */
        .table-header {
            background-color: var(--primary-purple) !important;
            color: var(--white) !important;
        }

        /* Table Rows - Alternating Colors */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--teal) !important;
            color: var(--white) !important;
        }



        /* Action Button */
        .btn-info.sm {
            background-color: var(--teal) !important;
            border-color: var(--teal) !important;
        }

        .btn-info.sm:hover {
            background-color: var(--primary-purple) !important;
            border-color: var(--primary-purple) !important;
        }

        /* Page Background */
        /* .page-content {
            background-color: var(--white) !important;
        } */
        body[data-topbar=light-green] #page-topbar {
    /* Changed to Primary Purple for the topbar background */
    background-color: #745b31;


body[data-topbar=light-green] .navbar-header .dropdown .show.header-item {
    /* Slightly tinted version of Primary Purple for a subtle effect */
    background-color: rgba(99, 91, 255, 0.05);
}

body[data-topbar=light-green] .navbar-header .waves-effect .waves-ripple {
    /* Using Primary Purple with alpha for the ripple effect */
    background: #745b31;
}

body[data-topbar=light-green] .header-item {
    color: #000000;
}

body[data-topbar=light-green] .header-item:hover {
    color: #000000;
}

body[data-topbar=light-green] .header-profile-user {
    /* Using a subtle Primary Purple tint */
    background-color: #745b31;
}

body[data-topbar=light-green] .noti-icon i {
    color: #000000;
}

body[data-topbar=light-green] .logo-dark {
    display: none;
}

body[data-topbar=light-green] .logo-light {
    display: block;
}

body[data-topbar=light-green] .app-search .form-control {
    /* Replacing with a Primary Purple based subtle background */
    background-color: rgba(99, 91, 255, 0.07);
    color: #000000;
}

body[data-topbar=light-green] .app-search span,
body[data-topbar=light-green] .app-search input.form-control::-webkit-input-placeholder {
    color: rgba(0, 0, 0, 0.5);
}

/* General styling for all input types, including select */
input[type="text"],
input[type="date"],
input[type="number"],
input[type="email"],
input[type="password"],
select,
input[type="radio"],
input[type="checkbox"] {
    /* Using white background and Teal for the border */
    background-color: #ffffff;
    border: 2px solid #00b8b8;
    padding: 0.375rem 0.75rem;
    transition: all 0.3s ease;
}

/* Styling for Select2 specific */
.select2-container .select2-selection--single {
    /* Using white background and Teal border */
    background-color: #ffffff !important;
    border: 2px solid #00b8b8 !important;
    padding: 0.375rem 0.75rem !important;
    transition: all 0.3s ease !important;
}

.select2-container .select2-selection--single:focus {
    /* Using Teal for focus border and shadow */
    border: 2px solid #00b8b8 !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 184, 184, 0.25) !important;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    color: #495057; /* Text color remains the same */
}

/* Focus styling for select2 */
.select2-container--default .select2-selection--single:focus {
    /* Updated to use Teal */
    border-color: #00b8b8;
    box-shadow: 0 0 0 0.2rem rgba(0, 184, 184, 0.25);
}

.select2-container--default .select2-selection--multiple {
    /* Using white background and Teal border for multiple selections */
    background-color: #ffffff !important;
    border: 2px solid #00b8b8 !important;
}

/* Styling for select dropdown options */
.select2-container .select2-results__option {
    background-color: #ffffff;
    padding: 10px;
}


    /* Custom button styling using your color brands */
    .btn-custom {
        background-color: #00b8b8 !important;  /* Teal */
        color: #ffffff !important;
        /* border-color: #635bff !important;       */
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    /* Hover effect: change background to Primary Purple */
    .btn-custom:hover {
        background-color: #635bff !important;  /* Primary Purple on hover */
        border-color: #635bff !important;
        color: #ffffff !important;
    }

    .brand-heading {
    font-size: 1.5rem;        /* Adjust size as needed */
    font-weight: 700;         /* Bold for emphasis */
    color: #635bff;           /* Use your brand color */
    margin-bottom: 1rem;
    position: relative;
}

/* Create an eye-catching underline effect */
.brand-heading::after {
    content: "";
    display: block;
    width: 60px;              /* Underline width (adjust as needed) */
    height: 1px;              /* Underline thickness */
    background-color: #635bff;/* Brand color underline */
    border-radius: 2px;       /* Slightly rounded corners for the line */
    margin-top: 0.3rem;       /* Space between the heading and the underline */
}

/* */

</style>
    </head>

    <body data-topbar="light-green">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">


          @include('admin.body.header')

            <!-- ========== Left Sidebar Start ========== -->
           @include('admin.body.sidebar')
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

               @yield('admin')
                <!-- End Page-content -->

                @include('admin.body.footer')


            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->

        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>


        <!-- apexcharts -->
        <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Responsive examples -->
        <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
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
<!-- Required datatable js -->
<script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
<script src="{{ asset('backend/assets/js/pages/datatables.init.js') }}"></script>
<script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

 <script src="{{ asset('backend/assets/js/code.js') }}"></script>
 <script src="{{ asset('backend/assets/js/validate.min.js') }}"></script>


 <script src="{{ asset('backend/assets/js/handlebars.js') }}"></script>
 <script src="{{ asset('backend/assets/js/notify.min.js') }}"></script>


<!--  For Select2 -->
<script src="{{ asset('backend/assets/libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/pages/form-advanced.init.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- end  For Select2 -->
</body>

</html>

    </body>

</html>
