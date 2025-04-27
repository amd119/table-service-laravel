@extends('layouts.app')

@section('title', 'Order Data')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Orders Data</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Home</a></li>
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
                            <a href="{{ route('order.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Order</span>
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
                                                <th>Customer</th>
                                                <th>Table</th>
                                                <th>Items</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $i = 1; @endphp
                                        @forelse($orders as $idpelanggan => $customerOrders)
                                            @php 
                                                $firstOrder = $customerOrders->first();
                                                $customer = $firstOrder->pelanggan;
                                                $table = $firstOrder->meja;
                                                $itemCount = $customerOrders->count();
                                            @endphp
                                            <tr class="single-item">
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{ $customer->nama_pelanggan }}</h6>
                                                            <small>{{ $customer->no_hp }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $table->nomor ?? 'N/A' }}</td>
                                                <td>{{ $itemCount }} items</td>
                                                <td>{{ $firstOrder->tanggal->format('d M Y H:i') }}</td>
                                                <td>
                                                    @php
                                                        $statusCounts = $customerOrders->groupBy('status')
                                                            ->map->count();
                                                        $mainStatus = $statusCounts->keys()->first();
                                                    @endphp
                                                    @if ($mainStatus == 'baru')
                                                        <span class="badge bg-soft-info text-info">New</span>
                                                    @elseif ($mainStatus == 'diproses')
                                                        <span class="badge bg-soft-warning text-warning">Processing</span>
                                                    @elseif ($mainStatus == 'selesai')
                                                        <span class="badge bg-soft-success text-success">Completed</span>
                                                    @elseif ($mainStatus == 'dibayar')
                                                        <span class="badge bg-soft-primary text-primary">Paid</span>
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
                                                                    <a href="{{ route('order.show', $idpelanggan) }}" class="dropdown-item">
                                                                        <i class="feather feather-eye me-3"></i>
                                                                        <span>View Details</span>
                                                                    </a>
                                                                </li>
                                                                @if ($mainStatus != 'dibayar')
                                                                    <li>
                                                                        <a class="dropdown-item edit-order" href="javascript:void(0);" data-id="{{ $idpelanggan }}">
                                                                            <i class="feather feather-edit-3 me-3"></i>
                                                                            <span>Edit</span>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No orders found</td>
                                            </tr>
                                        @endforelse
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

    <!-- [ Edit Order Modal ] start -->
    @foreach($orders as $idpelanggan => $customerOrders)
    @php 
        $firstOrder = $customerOrders->first();
        $customer = $firstOrder->pelanggan;
        $table = $firstOrder->meja;
    @endphp
    <div class="modal fade" id="editordermodal-{{ $idpelanggan }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Edit Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('order.update', $idpelanggan) }}" method="POST" id="editForm-{{ $idpelanggan }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="notes-box">
                            <div class="notes-content">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Customer Name: </label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="feather-user"></i></div>
                                            <input type="text" class="form-control" 
                                                name="nama_pelanggan" 
                                                value="{{ $customer->nama_pelanggan }}" 
                                                placeholder="Customer Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Phone Number: </label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="feather-phone"></i></div>
                                            <input type="text" class="form-control" 
                                                name="no_hp" 
                                                value="{{ $customer->no_hp }}" 
                                                placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Gender: </label>
                                        <select class="form-control" name="jenis_kelamin" data-select2-selector="status">
                                            <option value="L" {{ $customer->jenis_kelamin == '1' ? 'selected' : '' }}>Male</option>
                                            <option value="P" {{ $customer->jenis_kelamin == '0' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Address:</label>
                                        <div class="input-group">
                                            <div class="input-group-text"><i class="feather-map-pin"></i></div>
                                            <input type="text" class="form-control" 
                                                name="alamat" 
                                                value="{{ $customer->alamat }}" 
                                                placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Table: </label>
                                        <select class="form-control" name="idmeja" data-select2-selector="status">
                                            @foreach($tables as $meja)
                                                <option value="{{ $meja->idmeja }}"
                                                    {{ $meja->idmeja == $table->idmeja ? 'selected' : '' }}>
                                                    Table {{ $meja->nomor }} (Capacity: {{ $meja->kapasitas }})
                                                    @if($meja->status != 'tersedia' && $meja->idmeja != $table->idmeja)
                                                        - {{ ucfirst($meja->status) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Status: </label>
                                        <select class="form-control" name="status" data-select2-selector="status">
                                            <option value="baru" {{ $mainStatus == 'baru' ? 'selected' : '' }} data-bg="bg-soft-info">New</option>
                                            <option value="diproses" {{ $mainStatus == 'diproses' ? 'selected' : '' }} data-bg="bg-soft-warning">Processing</option>
                                            <option value="selesai" {{ $mainStatus == 'selesai' ? 'selected' : '' }} data-bg="bg-soft-success">Completed</option>
                                            <option value="dibayar" {{ $mainStatus == 'dibayar' ? 'selected' : '' }} data-bg="bg-soft-primary">Paid</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    <!-- [ Edit Order Modal ] end -->

    <!-- [ Delete Table Form ] start -->
    <form id="deleteTableForm" style="display:none;" method="POST" action="{{ route('table.delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="idmeja" id="delete_idmeja">
    </form>
    <!-- [ Delete Table Form ] end -->
@endsection

