@extends('layouts.app')

@section('title', 'Create Order')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Create Order</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Orders</a></li>
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
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            
            <div class="notification-container px-3">
                @include('layouts.partials.notifications')
            </div>
            
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('order.store') }}" method="POST" id="createOrderForm">
                            @csrf
                            <div class="card stretch stretch-full mb-4">
                                <div class="card-header">
                                    <h5>Customer Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required>
                                            @error('nama_pelanggan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp') }}" required>
                                            @error('no_hp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" data-select2-selector="tag" style="width: 100%;" required>
                                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Male</option>
                                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            @error('jenis_kelamin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" value="{{ old('alamat') }}">
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full mb-4">
                                <div class="card-header">
                                    <h5>Table Selection</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Table <span class="text-danger">*</span></label>
                                            <select class="form-select @error('idmeja') is-invalid @enderror" name="idmeja" data-select2-selector="default" style="width: 100%;" required>
                                                <option value="">Select Table</option>
                                                @foreach($tables as $table)
                                                <option value="{{ $table->idmeja }}" {{ old('idmeja') == $table->idmeja ? 'selected' : '' }}>
                                                    Table {{ $table->nomor }} (Capacity: {{ $table->kapasitas }})
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('idmeja')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Menu Items</h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="addMenuItem">
                                        <i class="feather-plus me-1"></i> Add Menu Item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="menuItemsContainer">
                                        <div class="menu-item-row row mb-3">
                                            <!-- Hidden menu data for JavaScript -->
                                            <div style="display: none;">
                                                @foreach($menus as $menu)
                                                <div class="menu-data" 
                                                    data-id="{{ $menu->idmenu }}" 
                                                    data-name="{{ $menu->nama_menu }}" 
                                                    data-price="{{ $menu->harga }}">
                                                </div>
                                                @endforeach
                                            </div>
                                            <!-- End of hidden menu data -->
                                            <div class="col-md-6 col-12 col-sm-6 mb-2 mb-sm-0">
                                                <label class="form-label">Menu <span class="text-danger">*</span></label>
                                                <select class="form-select menu-select @error('menu_items.0.idmenu') is-invalid @enderror" name="menu_items[0][idmenu]" data-select2-selector="default" style="width: 100%;" required>
                                                    <option value="">Select Menu</option>
                                                    @foreach($menus as $menu)
                                                    <option value="{{ $menu->idmenu }}" data-price="{{ $menu->harga }}" {{ old('menu_items.0.idmenu') == $menu->idmenu ? 'selected' : '' }}>
                                                        {{ $menu->nama_menu }} - Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('menu_items.0.idmenu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 col-8 col-sm-4 mb-2 mb-sm-0">
                                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control quantity-input @error('menu_items.0.jumlah') is-invalid @enderror" 
                                                       name="menu_items[0][jumlah]" 
                                                       value="{{ old('menu_items.0.jumlah', 1) }}" required>
                                                @error('menu_items.0.jumlah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-4 col-sm-2 col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-sm btn-danger remove-menu-item" style="display: none;">
                                                    <i class="feather-trash-2"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="feather-save me-2"></i> Create Order
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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