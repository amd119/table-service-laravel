@extends('layouts.app')
@section('title', 'Home')

@section('content-home')
<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container px-4">
            <a class="navbar-brand" href="#">Kasir-Restoran</a>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 d-flex h-100 align-items-center justify-content-center">
            <div class="text-center">
                <h1 class="mx-auto my-0 text-uppercase">D-Res</h1>
                <h2 class="text-white-50 mx-auto mt-2 mb-5">Web D-Res Responsive untuk memanajemen Restoran dengan Mudah.</h2>
                @if (Route::has('login'))
                    @auth
                        @if (in_array(Auth::user()?->role, ['administrator', 'waiter', 'kasir', 'owner']))
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Ayo Mulai
                        </a>
                    @endauth
            @endif
            </div>
        </div>
    </header>
    <footer class="footer bg-black small text-center text-white-50">
        <div class="container px-4">Copyright &copy; DDev - Kasir Restoran 2025</div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
@endsection
