@extends('layouts.app')

@section('title', 'Order Report')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Order Report</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Orders</a></li>
                        <li class="breadcrumb-item">Report</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('order.report', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'print' => 'pdf']) }}" target="_blank" class="btn btn-primary">
                                <i class="feather-printer me-2"></i>
                                <span>Print PDF</span>
                            </a>
                            <a href="{{ route('order.report', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'download' => 'pdf']) }}" class="btn btn-primary">
                                <i class="feather-download me-2"></i>
                                <span>Download PDF</span>
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
                        <div class="card stretch stretch-full mb-4">
                            <div class="card-header">
                                <h5>Order Summary ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-light mb-0">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Total Orders</h6>
                                                <h2 class="mb-0">{{ $orders->count() }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light mb-0">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Total Items</h6>
                                                <h2 class="mb-0">{{ $totalItems }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light mb-0">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Average Items Per Order</h6>
                                                <h2 class="mb-0">{{ $orders->count() > 0 ? number_format($totalItems / $orders->count(), 1) : 0 }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card stretch stretch-full mb-4">
                            <div class="card-header">
                                <h5>Order Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Number of Orders</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orderStatuses as $status => $data)
                                                <tr>
                                                    <td>
                                                        @if($status == 'baru')
                                                            <span class="badge bg-soft-warning text-warning">New</span>
                                                        @elseif($status == 'diproses')
                                                            <span class="badge bg-soft-primary text-primary">Processing</span>
                                                        @elseif($status == 'selesai')
                                                            <span class="badge bg-soft-success text-success">Completed</span>
                                                        @elseif($status == 'dibayar')
                                                            <span class="badge bg-soft-info text-info">Paid</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $data['count'] }}</td>
                                                    <td>{{ number_format(($data['count'] / $orders->count()) * 100, 2) }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5>Order List</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="leadList">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Customer</th>
                                                <th>Table</th>
                                                <th>Menu</th>
                                                <th>Quantity</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Waiter</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0">{{ $order->pelanggan->nama_pelanggan }}</h6>
                                                                <small>{{ $order->pelanggan->no_hp }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $order->meja->nomor ?? 'N/A' }}</td>
                                                    <td>{{ $order->menu->nama_menu }}</td>
                                                    <td>{{ $order->jumlah }}</td>
                                                    <td>{{ $order->tanggal->format('d M Y H:i') }}</td>
                                                    <td>
                                                        @if($order->status == 'baru')
                                                            <span class="badge bg-soft-warning text-warning">New</span>
                                                        @elseif($order->status == 'diproses')
                                                            <span class="badge bg-soft-primary text-primary">Processing</span>
                                                        @elseif($order->status == 'selesai')
                                                            <span class="badge bg-soft-success text-success">Completed</span>
                                                        @elseif($order->status == 'dibayar')
                                                            <span class="badge bg-soft-info text-info">Paid</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $order->waiter->name }}</td>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#reportTable').DataTable({
            order: [[0, 'desc']]
        });
    });
</script>
@endpush