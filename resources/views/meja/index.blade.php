@extends('layouts.app')

@section('title', 'Table Data')

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
                        <li class="breadcrumb-item">Table Data</li>
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
                            <div class="dropdown">
                                <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                                    <i class="feather-paperclip"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="bi bi-filetype-pdf me-3"></i>
                                        <span>PDF</span>
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="bi bi-filetype-exe me-3"></i>
                                        <span>Excel</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="bi bi-printer me-3"></i>
                                        <span>Print</span>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('table.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Table</span>
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
                                                <th>Table Number</th>
                                                <th>Table Capacity</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $meja)
                                                    <tr class="single-item">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <div>
                                                                <span class="text-truncate-1-line">{{ $meja->nomor }}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $meja->kapasitas }}</td>
                                                        <td>
                                                            @if ($meja->status == 'tersedia')
                                                                <span class="badge bg-soft-success text-success">Available</span>
                                                            @elseif ($meja->status == 'terisi')
                                                                <span class="badge bg-soft-danger text-danger">Filled</span>
                                                            @elseif ($meja->status == 'reserved')
                                                                <span class="badge bg-soft-warning text-warning">Reserved</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="hstack justify-content-end">
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown">
                                                                        <i class="feather feather-more-horizontal"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a href="{{ route('table.create') }}" class="dropdown-item d-flex d-md-none">
                                                                                <i class="feather feather-plus me-3"></i>
                                                                                <span>Create</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item edit-table" href="javascript:void(0);" data-id="{{ $meja->idmeja }}">
                                                                                <i class="feather feather-edit-3 me-3"></i>
                                                                                <span>Edit</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item delete-table" href="javascript:void(0);" data-id="{{ $meja->idmeja }}" >
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

    <!-- [ Edit Table Modal ] start -->
    @foreach($data as $table)
        <form action="{{ route('table.update', $table->idmeja) }}" method="POST" enctype="multipart/form-data" id="editForm-{{ $table->idmeja }}">
            @csrf
            @method('PUT')
            <div class="modal fade" id="edittablemodal-{{ $table->idmeja }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">Edit Meja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="notes-box">
                                <div class="notes-content">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Table Number: </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <input type="text" class="form-control @error('nomor') is-invalid @enderror" id="nomor" name="nomor" value="{{ old('nomor', $table->nomor) }}" placeholder="Table Number" required>
                                            </div>
                                            @error('nomor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label">Capacity: </label>
                                            <div class="input-group">
                                                <div class="input-group-text"><span style="font-family:nunito,sans-serif;font-size:16px;font-weight:bold;">[]</span></div>
                                                <input type="text" class="form-control no-arrows @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $table->kapasitas) }}" placeholder="Capacity" required>
                                            </div>
                                            @error('kapasitas')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Status: </label>
                                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status-{{ $table->idmeja }}" data-select2-selector="status">
                                                <option value="tersedia" {{ $table->status == 'tersedia' ? 'selected' : '' }} data-bg="bg-success">Available</option>
                                                <option value="terisi" {{ $table->status == 'terisi' ? 'selected' : '' }} data-bg="bg-danger">Filled</option>
                                                <option value="reserved" {{ $table->status == 'reserved' ? 'selected' : '' }} data-bg="bg-warning">Reserved</option>
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
    <!-- [ Edit Table Modal ] end -->

    <!-- [ Delete Table Form ] start -->
    <form id="deleteTableForm" style="display:none;" method="POST" action="{{ route('table.delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="idmeja" id="delete_idmeja">
    </form>
    <!-- [ Delete Table Form ] end -->
@endsection

