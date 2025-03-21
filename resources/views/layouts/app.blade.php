<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta name="keyword" content="">
        <meta name="author" content="WRAPCODERS">
        <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
        <!--! BEGIN: Apps Title-->
        <title>
            {{ $titlePage }} | Kasir Restoran
          </title>
        <!--! END:  Apps Title-->
        <!--! BEGIN: Favicon-->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">
        <!--! END: Favicon-->
        <!--! BEGIN: Bootstrap CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
        <!--! END: Bootstrap CSS-->
        <!--! BEGIN: Vendors CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors.min.css') }}">
        <!--! END: Vendors CSS-->
        <!--! BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/theme.min.css') }}">
        <!--! END: Custom CSS-->
        <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
        <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
        <!--[if lt IE 9]>
                <script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
    </head>
    
    <body>
        
    @guest
        @yield('content-guest')
    @endguest

    
    <!--! ================================================================ !-->
    <!--! Footer Script !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Vendors JS !-->
    <script src="./../assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="./../assets/js/common-init.min.js"></script>
    <!--! END: Apps Init !-->
    <!--! BEGIN: Theme Customizer  !-->
    <script src="./../assets/js/theme-customizer-init.min.js"></script>
    <!--! END: Theme Customizer !-->
    </body>
</html>