@extends('layouts.app')

@section('title', 'Order Reports')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Order Reports</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Order Reports</li>
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
                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="feather-shopping-cart text-primary fs-1 me-3"></i>
                                    <div>
                                        <p class="mb-0 text-muted">Total Orders</p>
                                        <h4 class="mb-0">{{ $totalOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="feather-check-circle text-success fs-1 me-3"></i>
                                    <div>
                                        <p class="mb-0 text-muted">Completed Orders</p>
                                        <h4 class="mb-0">{{ $completedOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="feather-credit-card text-info fs-1 me-3"></i>
                                    <div>
                                        <p class="mb-0 text-muted">Paid Orders</p>
                                        <h4 class="mb-0">{{ $paidOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="feather-clock text-warning fs-1 me-3"></i>
                                    <div>
                                        <p class="mb-0 text-muted">New/Processing</p>
                                        <h4 class="mb-0">{{ $newOrders + $processingOrders }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Daily Orders Chart -->
                    <div class="col-md-8">
                        <div class="card stretch stretch-full mb-4">
                            <div class="card-header">
                                <h5>Daily Orders (Last 7 Days)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="orderChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Menu Items -->
                    <div class="col-md-4">
                        <div class="card stretch stretch-full mb-4">
                            <div class="card-header">
                                <h5>Popular Menu Items</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @foreach($popularMenus as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $item->menu->nama_menu }}</h6>
                                                <small class="text-muted">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $item->total_quantity }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="row">
                    <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5>Recent Orders</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
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
                                        @forelse($recentOrders as $idpelanggan => $customerOrders)
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
                                                        <a href="{{ route('order.show', $idpelanggan) }}" class="btn btn-sm btn-primary">
                                                            <i class="feather-eye"></i>
                                                        </a>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Daily Orders Chart
        const ctx = document.getElementById('orderChart').getContext('2d');
        
        const dates = @json($dailyOrders->pluck('date'));
        const counts = @json($dailyOrders->pluck('count'));
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Number of Orders',
                    data: counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection