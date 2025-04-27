@extends('layouts.app')

@section('title', 'Table Create')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Table Data</h5>
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
                            <a href="{{ route('table.index') }}" class="btn btn-light-brand">
                                <i class="feather-x me-2"></i>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" form="tableForm" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Table</span>
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

                                        <form id="tableForm" action="{{ route('table.store') }}" method="POST"> {{-- Tambahkan secara manual tag form beserta isinya --}}
                                            @csrf
                                        
                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label for="nomor" class="fw-semibold">Table Number: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-text"><i class="feather-hash"></i></div>
                                                        <input type="text" class="form-control @error('nomor') is-invalid @enderror" id="nomor" name="nomor" value="{{ old('nomor') }}" placeholder="Table Number" required>
                                                    </div>
                                                    @error('nomor')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-4 align-items-center">
                                                <div class="col-lg-4">
                                                    <label for="kapasitas" class="fw-semibold">Table Capacity: </label>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <div class="input-group-text"><span style="font-family:nunito,sans-serif;font-size:16px;font-weight:bold;">|-|</span></div>
                                                        <input type="text" class="form-control no-arrows @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" placeholder="Table Capacity" required>
                                                    </div>
                                                    @error('kapasitas')
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
                                                        <option value="terisi" {{ old('status') == 'terisi' ? 'selected' : '' }} data-bg="bg-danger">Filled</option>
                                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }} data-bg="bg-warning">Reserved</option>
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
    
