@extends('layouts.app')

@section('title', 'User Create')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Users</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Create</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('user.index') }}" class="btn btn-light-brand">
                                <i class="feather-x me-2"></i>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" form="userForm" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create User</span>
                            </button>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border-top-0">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                                    <div class="card-body personal-info">
                                        @if(session('danger'))
                                            <div class="alert alert-danger">
                                                {{ session('danger') }}
                                            </div>
                                        @endif

                                        <div class="mb-4 d-flex align-items-center justify-content-between">
                                            <h5 class="fw-bold mb-0 me-4">
                                                <span class="d-block mb-2">Table Information</span>
                                            </h5>
                                        </div>

                                        <form id="userForm" action="{{ route('user.store') }}" method="POST"> {{-- Tambahkan secara manual tag form beserta isinya --}}
                                            @csrf
                                        
                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label for="username" class="fw-semibold">Username: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-text"><i class="feather-hash"></i></div>
                                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Username" required>
                                                    </div>
                                                    @error('username')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label for="password" class="fw-semibold">Password: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-text"><span style="font-family:nunito,sans-serif;font-size:16px;font-weight:bold;">|-|</span></div>
                                                        <input type="password" class="form-control no-arrows @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" placeholder="Password" required>
                                                    </div>
                                                    @error('password')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label class="fw-semibold">Role: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <select class="form-control @error('role') is-invalid @enderror" name="role" id="role" data-select2-selector="role">
                                                        <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }} data-bg="bg-success">Administrator</option>
                                                        <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }} data-bg="bg-danger">Waiter</option>
                                                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }} data-bg="bg-warning">Cashier</option>
                                                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }} data-bg="bg-warning">Owner</option>
                                                    </select>
                                                    @error('role')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
        <!-- [ Footer ] start -->
        @include('layouts.partials.footer')
        <!-- [ Footer ] end -->
    </main>

@endsection
    
