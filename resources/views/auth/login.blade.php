
@extends('layouts.app', ['titlePage' => 'Login'])
@section('content-guest')
    <!--! ================================================================ !-->
    <!--! [Start] Main Content !-->
    <!--! ================================================================ !-->
    <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">
                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                    <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                        <img src="./../assets/images/logo-abbr.png" alt="" class="img-fluid">
                    </div>
                    <div class="card-body p-sm-5">
                        <h2 class="fs-20 fw-bolder mb-4">Login</h2>
                        <h4 class="fs-13 fw-bold mb-2">Login to your account</h4>
                        <p class="fs-12 fw-medium text-muted">Thank you for get back <strong>Nelel</strong> web applications, let's access our the best recommendation for you.</p>
                        <form method="POST" action="{{ route('login') }}" class="w-100 mt-4 pt-2">
                            @csrf
                            <div class="mb-4">
                                <input type="email" class="form-control" placeholder="Email or Username" value="wrapcode.info@gmail.com" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" value="123456" required>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="rememberMe">
                                        <label class="custom-control-label c-pointer" for="rememberMe">Remember Me</label>
                                    </div>
                                </div>
                                <div>
                                    <a href="auth-reset-minimal.html" class="fs-11 text-primary">Forget password?</a>
                                </div>
                            </div>
                            <div class="mt-5">
                                <button type="submit" class="btn btn-lg btn-primary w-100">Login</button>
                            </div>
                        </form>
                        <div class="w-100 mt-5 text-center mx-auto">
                            <div class="mb-4 border-bottom position-relative"><span class="small py-1 px-3 text-uppercase text-muted bg-white position-absolute translate-middle">or</span></div>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login with Facebook">
                                    <i class="feather-facebook"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login with Twitter">
                                    <i class="feather-twitter"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login with Github">
                                    <i class="feather-github text"></i>
                                </a>
                            </div>
                        </div>
                        <div class="mt-5 text-muted">
                            <span> Don't have an account?</span>
                            <a href="auth-register-minimal.html" class="fw-bold">Create an Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->
@endsection