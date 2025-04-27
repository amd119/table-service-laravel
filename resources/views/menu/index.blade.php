@extends('layouts.app')

@section('title', 'Menu List')

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
                        <li class="breadcrumb-item">Menu List</li>
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
                            <a href="{{ route('menu.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Menu</span>
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
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="leadList">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Menu Name</th>
                                                <th>Price (Rp)</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $menu)
                                                    <tr class="single-item">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <a class="hstack gap-3">
                                                                <div class="avatar-image avatar-md">
                                                                    <img src="{{ asset($menu->gambar) }}" alt="Gambar Menu" class="img-fluid">
                                                                </div>
                                                                <div>
                                                                    <span class="text-truncate-1-line">{{ $menu->nama_menu }}</span>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td>{{ number_format($menu->harga, 0, ',', '.') }}</td>
                                                        <td>{{ $menu->created_at }}</td>
                                                        <td>
                                                            @if ($menu->status == 'tersedia')
                                                                <span class="badge bg-soft-success text-success">Available</span>
                                                            @elseif ($menu->status == 'habis')
                                                                <span class="badge bg-soft-danger text-danger">Sold Out</span>
                                                            @endif
                                                            {{-- <div class="badge bg-soft-success text-success">{{ $menu->status }}</div> --}}
                                                        </td>
                                                        <td>
                                                            <div class="hstack justify-content-end">
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown">
                                                                        <i class="feather feather-more-horizontal"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a href="{{ route('menu.create') }}" class="dropdown-item d-flex d-md-none">
                                                                                <i class="feather feather-plus me-3"></i>
                                                                                <span>Create</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item edit-menu" href="javascript:void(0);" data-id="{{ $menu->idmenu }}">
                                                                                <i class="feather feather-edit-3 me-3"></i>
                                                                                <span>Edit</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item delete-menu" href="javascript:void(0);" data-id="{{ $menu->idmenu }}">
                                                                                <i class="feather feather-trash-2 me-3"></i>
                                                                                <span>Delete</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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

    <!-- [ Edit Menu Modal ] start -->
    @foreach($data as $menu)
        <form action="{{ route('menu.update', $menu->idmenu) }}" method="POST" enctype="multipart/form-data" id="editForm-{{ $menu->idmenu }}">
            @csrf
            @method('PUT')
            <div class="modal fade" id="editmenumodal-{{ $menu->idmenu }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">Edit Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Picture: </label>
                                            <div class="col-lg-8">
                                                <div class="mb-4 mb-md-0 d-flex gap-4 your-brand">
                                                    <div class="wd-100 ht-100 position-relative overflow-hidden border border-gray-2 rounded">
                                                        <img src="{{ asset($menu->gambar) }}" class="upload-pic img-fluid rounded h-100 w-100 preview-image" alt="Upload" data-original="{{ asset($menu->gambar) }}">
                                                        <div class="position-absolute start-50 top-50 end-0 bottom-0 translate-middle h-100 w-100 hstack align-items-center justify-content-center c-pointer upload-button">
                                                            <i class="feather feather-camera" aria-hidden="true"></i>
                                                        </div>
                                                        <input class="file-upload" type="file" name="gambar" accept="image/*">
                                                        <!-- Remove image button will be added by JavaScript -->
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
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Menu Name: </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" id="nama_menu" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" placeholder="Menu Name" required>
                                            </div>
                                            @error('nama_menu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Price: </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><span style="font-family:nunito,sans-serif;font-size:16px;font-weight:bold;">Rp</span></div>
                                                <input type="text" class="form-control no-arrows @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $menu->harga) }}" placeholder="Price" required>
                                            </div>
                                            @error('harga')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Status: </label>
                                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status-{{ $menu->idmenu }}" data-select2-selector="status">
                                                <option value="tersedia" {{ $menu->status == 'tersedia' ? 'selected' : '' }} data-bg="bg-success">Available</option>
                                                <option value="habis" {{ $menu->status == 'habis' ? 'selected' : '' }} data-bg="bg-danger">Sold Out</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- Original button will be replaced by JavaScript with Cancel and Clear Form buttons -->
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="float-left btn btn-success">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach
    <!-- [ Edit Menu Modal ] end -->

    <!-- [ Delete Menu Form ] start -->
    <form id="deleteMenuForm" style="display:none;" method="POST" action="{{ route('menu.delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="menu_id" id="delete_menu_id">
    </form>
    <!-- [ Delete Menu Form ] end -->
@endsection
