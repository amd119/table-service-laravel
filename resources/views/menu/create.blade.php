@extends('layouts.app')

@section('title', 'Menu Create')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Menu List</h5>
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
                            <a href="{{ route('menu.index') }}" class="btn btn-light-brand">
                                <i class="feather-x me-2"></i>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" form="menuForm" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Menu</span>
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
                                                <span class="d-block mb-2">Menu Information</span>
                                            </h5>
                                        </div>

                                        <form id="menuForm" action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data"> {{-- Tambahkan secara manual tag form beserta isinya --}}
                                            @csrf
                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label class="fw-semibold">Picture: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="mb-4 mb-md-0 d-flex gap-4 your-brand">
                                                        <div class="wd-100 ht-100 position-relative overflow-hidden border border-gray-2 rounded">
                                                            <img src="{{ asset('img/default.jpg') }}" class="upload-pic img-fluid rounded h-100 w-100" id="preview-image" alt="Upload">
                                                            <div class="position-absolute start-50 top-50 end-0 bottom-0 translate-middle h-100 w-100 hstack align-items-center justify-content-center c-pointer upload-button">
                                                                <i class="feather feather-camera" aria-hidden="true"></i>
                                                            </div>
                                                            <input class="file-upload" type="file" name="gambar" id="gambar" accept="image/*">
                                                        </div>
                                                        <div class="d-flex flex-column gap-1">
                                                            <div class="fs-11 text-gray-500 mt-2"># Upload your Menu Picture</div>
                                                            <div class="fs-11 text-gray-500"># Picture size 150x150</div>
                                                            <div class="fs-11 text-gray-500"># Max upload size 1mb</div>
                                                            <div class="fs-11 text-gray-500"># Allowed file types: png, jpg, jpeg</div>
                                                            @error('gambar')
                                                                <div class="fs-11 text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label for="nama_menu" class="fw-semibold">Menu Name: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-text"><i class="feather-user"></i></div>
                                                        <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" id="nama_menu" name="nama_menu" value="{{ old('nama_menu') }}" placeholder="Menu Name" required>
                                                    </div>
                                                    @error('nama_menu')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label for="harga" class="fw-semibold">Price: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-text"><span style="font-family:nunito,sans-serif;font-size:16px;font-weight:bold;">Rp</span></div>
                                                        <input type="text" class="form-control no-arrows @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" placeholder="Price" required>
                                                    </div>
                                                    @error('harga')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label class="fw-semibold">Status: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" data-select2-selector="status">
                                                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }} data-bg="bg-success">Available</option>
                                                        <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }} data-bg="bg-danger">Sold Out</option>
                                                    </select>
                                                    @error('status')
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
    
