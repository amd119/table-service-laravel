<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="id" content="">
        <meta name="auth_id" content="{{ Auth::id() }}">
        <meta name="url" content="{{ public_path() }}">
        <meta name="description" content="">
        <meta name="keyword" content="">
        <meta name="author" content="WRAPCODERS">
        <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
        <title>
            @yield('title') | Kasir Restoran
        </title>
        <!--! BEGIN: Favicon-->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
        <!--! END: Favicon-->
        <!--! BEGIN: Bootstrap CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
        <!--! END: Bootstrap CSS-->
        <!--! BEGIN: Vendors CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/vendors.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/daterangepicker.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/dataTables.bs5.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/select2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/select2-theme.min.css') }}">
        <!--! END: Vendors CSS-->
        <!--! BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/theme.min.css') }}">
        <!-- Core theme CSS -->
        @if(isset($styles))
            <link rel="stylesheet" type="text/css" href="{{ asset('css/' . $styles) }}" />
        @endif
        <!--! END: Custom CSS-->
        <!-- Font Awesome and Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
        <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
        <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
        <!--[if lt IE 9]>
                <script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
    </head>
    
    <body>
        @if(Route::is('home'))
            @yield('content-home')
        @elseif(Auth::guest())
            @yield('content-guest')
        @else
            <!-- Authenticated layout structure -->
            @yield('content-dashboard')
        @endif

        <!--! BEGIN: Vendors JS !-->
        <script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
        <!-- vendors.min.js {always must need to be top} -->
        <script src="{{ asset('vendors/js/daterangepicker.min.js') }}"></script>
        <script src="{{ asset('vendors/js/apexcharts.min.js') }}"></script>
        <script src="{{ asset('vendors/js/circle-progress.min.js') }}"></script>
        <script src="{{ asset('vendors/js/dataTables.min.js') }}"></script>
        <script src="{{ asset('vendors/js/dataTables.bs5.min.js') }}"></script>
        <script src="{{ asset('vendors/js/select2.min.js') }}"></script>
        <script src="{{ asset('vendors/js/select2-active.min.js') }}"></script>
        <script src="{{ asset('vendors/js/sweetalert2.min.js') }}"></script>
        {{-- <script src="{{ asset('vendors/js/jquery.min.js') }}"></script> --}}
        <!--! END: Vendors JS !-->
        <!--! BEGIN: Apps Init  !-->
        <script src="{{ asset('js/common-init.min.js') }}"></script>
        <script src="{{ asset('js/dashboard-init.min.js') }}"></script>
        <script src="{{ asset('js/leads-init.min.js') }}"></script>
        <script src="{{ asset('js/leads-view-init.min.js') }}"></script>
        <script src="{{ asset('js/customers-create-init.min.js') }}"></script>
        <script src="{{ asset('js/apps-notes-init.min.js') }}"></script>
        <script src="{{ asset('js/proposal-create-init.min.js') }}"></script>
        <!--! END: Apps Init !-->
        <!--! BEGIN: Theme Customizer  !-->
        <script src="{{ asset('js/theme-customizer-init.min.js') }}"></script>
        <!--! END: Theme Customizer !-->
        <script src="{{ asset('js/logout.js') }}"></script>
        <script src="{{ asset('js/table-service.js') }}"></script>

        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        <script src="{{ asset('js/sweetalert2@11.min.js') }}"></script>

        {{-- @stack('create-menu-scripts')
        @stack('index-menu-scripts') --}}
        @stack('scripts')
    </body>
</html>