@extends('layouts.app')

@section('title', 'Order Details')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Order Details</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active">Order #{{ $orders->first()->idpesanan }}</li>
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
                            <a href="{{ route('order.index') }}" class="btn btn-secondary">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back to Orders</span>
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
                                <!-- Order Header -->
                                <div class="p-4 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h1 class="h5 mb-1">Order #{{ $orders->first()->idpesanan }}</h1>
                                            <p class="small text-muted mb-0">Created: {{ $orders->first()->tanggal->format('d M Y - H:i') }}</p>
                                        </div>
                                        <span class="badge 
                                            @if($mainStatus == 'baru') bg-info
                                            @elseif($mainStatus == 'diproses') bg-warning
                                            @elseif($mainStatus == 'selesai') bg-success
                                            @elseif($mainStatus == 'dibayar') bg-primary
                                            @endif">
                                            {{ ucfirst($mainStatus) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Customer Info -->
                                <div class="p-4 border-bottom">
                                    <h5 class="mb-3">Customer Information</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small text-muted mb-1">Name</label>
                                            <p class="mb-0">{{ $customer->nama_pelanggan }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small text-muted mb-1">Phone</label>
                                            <p class="mb-0">{{ $customer->no_hp }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small text-muted mb-1">Table</label>
                                            <p class="mb-0">
                                                @if($table)
                                                    Table {{ $table->nomor }} (Capacity: {{ $table->kapasitas }})
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small text-muted mb-1">Address</label>
                                            <p class="mb-0">{{ $customer->alamat ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Items -->
                                <div class="p-4">
                                    <h5 class="mb-3">Order Items</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table">
                                                <tr>
                                                    <th>Item</th>
                                                    <th class="text-end">Price</th>
                                                    <th class="text-end">Quantity</th>
                                                    <th class="text-end">Subtotal</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orderItems as $item)
                                                <tr>
                                                    <td>{{ $item['menu_name'] }}</td>
                                                    <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                                    <td class="text-end">{{ $item['quantity'] }}</td>
                                                    <td class="text-end">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($item['status'] == 'baru') bg-info
                                                            @elseif($item['status'] == 'diproses') bg-warning
                                                            @elseif($item['status'] == 'selesai') bg-success
                                                            @elseif($item['status'] == 'dibayar') bg-primary
                                                            @endif">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        @if($item['status'] == 'baru' || $item['status'] == 'diproses')
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown">
                                                                    <i class="feather-more-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    @if($item['status'] == 'baru')
                                                                    <li>
                                                                        <form action="{{ route('order.update-status', $item['idpesanan']) }}" method="POST">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <input type="hidden" name="scope" value="single">
                                                                            <input type="hidden" name="status" value="diproses">
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="feather-rotate-cw me-2"></i> Mark as Processing
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                    @elseif($item['status'] == 'diproses')
                                                                    <li>
                                                                        <form action="{{ route('order.update-status', $item['idpesanan']) }}" method="POST">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <input type="hidden" name="scope" value="single">
                                                                            <input type="hidden" name="status" value="selesai">
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="feather-check me-2"></i> Mark as Completed
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table">
                                                <tr>
                                                    <th colspan="3" class="text-end">Total:</th>
                                                    <th class="text-end">Rp {{ number_format($totalAmount, 0, ',', '.') }}</th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="p-4 border-top">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            {{-- Handle All Items Data Status --}}
                                            @if(in_array($mainStatus, ['baru', 'diproses']))
                                                <div class="d-flex gap-2 mb-3">
                                                    <form action="{{ route('order.update-status', $customer->idpelanggan) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="scope" value="all">
                                                        @if($mainStatus == 'baru')
                                                            <input type="hidden" name="status" value="diproses">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="feather-check-circle me-2"></i> Process All Items
                                                            </button>
                                                        @else
                                                            <input type="hidden" name="status" value="selesai">
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="feather-check me-2"></i> Complete All Items
                                                            </button>
                                                        @endif
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('order.index') }}" class="btn btn-outline-secondary me-2">
                                                <i class="feather-arrow-left me-2"></i> Back
                                            </a>
                                        </div>
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