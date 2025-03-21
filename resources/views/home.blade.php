<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Our Café - Landing Page</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets') }}/img/icon1.png" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    </head>
    <body id="page-top">
        <style>
            .img-fluid {
                height: 620px; 
                width: 620px; 
                object-fit: cover;
            }
            .bg-gradient-primary {
                background-image: linear-gradient(195deg, #c8846b 0%, #c88d6b 100%); 
            }
    
            .btn-primary {
                color: #FFF;
                background-color: #c88d6b;
                border-color: #c88d6b; }
                .btn-primary:hover {
                    color: #FFF;
                    background-color: #8a3d27;
                    border-color: #94432d; }
                .btn-check:focus + .btn-primary, .btn-primary:focus {
                    color: #FFF;
                    background-color: #8a3d27;
                    border-color: #94432d;
                    box-shadow: 0 0 0 0.2rem rgb(148, 67, 45, 0.50); }
                .btn-check:checked + .btn-primary,
                .btn-check:active + .btn-primary, .btn-primary:active, .btn-primary.active,
                .show > .btn-primary.dropdown-toggle {
                    color: #FFF;
                    background-color: #8a3d27;
                    border-color: #94432d; }
                    .btn-check:checked + .btn-primary:focus,
                    .btn-check:active + .btn-primary:focus, .btn-primary:active:focus, .btn-primary.active:focus,
                    .show > .btn-primary.dropdown-toggle:focus {
                    box-shadow: 0 0 0 0.2rem rgb(148, 67, 45, 0.5); }
                .btn-primary:disabled, .btn-primary.disabled {
                    color: #FFF;
                    background-color: hsl(22, 46%, 60%);
                    border-color: #c88d6b; }
    
            .btn-primary,
            .btn.bg-gradient-primary {
            box-shadow: 0 3px 3px 0 rgb(133, 96, 63, 0.15), 0 3px 1px -2px rgb(133, 96, 63, 0.2), 0 1px 5px 0 rgb(133, 96, 63, 0.15); }
            .btn-primary:hover,
            .btn.bg-gradient-primary:hover {
                background-color: #c88d6b;
                border-color: #c88d6b;
                box-shadow: 0 14px 26px -12px rgb(133, 96, 63, 0.4), 0 4px 23px 0 rgb(133, 96, 63, 0.15), 0 8px 10px -5px rgb(133, 96, 63, 0.2); }
            .btn-primary .btn.bg-outline-primary,
            .btn.bg-gradient-primary .btn.bg-outline-primary {
                border: 1px solid #c88d6b; }
            .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active,
            .show > .btn-primary.dropdown-toggle,
            .btn.bg-gradient-primary:not(:disabled):not(.disabled).active,
            .btn.bg-gradient-primary:not(:disabled):not(.disabled):active,
            .show >
            .btn.bg-gradient-primary.dropdown-toggle {
                color: color-yiq(#c88d6b);
                background-color: #c88d6b; }
            .btn-primary.focus, .btn-primary:focus,
            .btn.bg-gradient-primary.focus,
            .btn.bg-gradient-primary:focus {
                color: #fff; }
            
            .btn-outline-primary {
            color: #c88d6b;
            border-color: #c88d6b;
            }
            .btn-outline-primary:hover {
            color: #fff;
            background-color: #c88d6b;
            border-color: #c88d6b;
            }
            .btn-check:focus + .btn-outline-primary, .btn-outline-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(100, 161, 157, 0.5);
            }
            .btn-check:checked + .btn-outline-primary, .btn-check:active + .btn-outline-primary, .btn-outline-primary:active, .btn-outline-primary.active, .btn-outline-primary.dropdown-toggle.show {
            color: #fff;
            background-color: #c88d6b;
            border-color: #c88d6b;
            }
            .btn-check:checked + .btn-outline-primary:focus, .btn-check:active + .btn-outline-primary:focus, .btn-outline-primary:active:focus, .btn-outline-primary.active:focus, .btn-outline-primary.dropdown-toggle.show:focus {
             box-shadow: 0 0 0 0.25rem rgba(200, 141, 107, 0.5);
            }
            .btn-outline-primary:disabled, .btn-outline-primary.disabled {
            color: #c88d6b;
            background-color: transparent;
            }
        </style>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#">Kasir-Restoran</a>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container px-4 px-lg-5 d-flex h-100 align-items-center justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="text-center">
                        <h1 class="mx-auto my-0 text-uppercase">Kasir Restoran</h1>
                        <h2 class="text-white-50 mx-auto mt-2 mb-5">Web Kasir Restoran Responsive untuk memanajemen Restoran dengan Mudah.</h2>
                        <a class="btn btn-primary" href="{{ route('login') }}">Ayo Mulai</a>
                    </div>
                </div>
            </div>
        </header>

        <footer class="footer bg-black small text-center text-white-50"><div class="container px-4 px-lg-5">Copyright &copy; DDev - Kasir Restoran 2025</div></footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- @include('_partials.session-script') --}}
        <!-- Core theme JS-->
        <script src="{{ asset('js/scripts.js') }}"></script>
    </body>
</html>
