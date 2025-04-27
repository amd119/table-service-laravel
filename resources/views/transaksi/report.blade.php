@extends('layouts.app')

@section('title', 'Transaction Report')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Transaction Report</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item">Report</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('transaksi.report', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'print' => 'pdf']) }}" target="_blank" class="btn btn-primary">
                                <i class="feather-printer me-2"></i>
                                <span>Print PDF</span>
                            </a>
                            <a href="{{ route('transaksi.report', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'download' => 'pdf']) }}" class="btn btn-primary">
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
                                <h5>Transaction Summary ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-light mb-0">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Total Transactions</h6>
                                                <h2 class="mb-0">{{ $transactions->count() }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light mb-0">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Total Revenue</h6>
                                                <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light mb-0">
                                            <div class="card-body text-center">
                                                <h6 class="mb-3">Average Transaction</h6>
                                                <h2 class="mb-0">Rp {{ $transactions->count() > 0 ? number_format($totalRevenue / $transactions->count(), 0, ',', '.') : 0 }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card stretch stretch-full mb-4">
                            <div class="card-header">
                                <h5>Payment Methods</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Payment Method</th>
                                                <th>Number of Transactions</th>
                                                <th>Total Amount</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paymentMethods as $method => $data)
                                                <tr>
                                                    <td>
                                                        @if($method == 'tunai')
                                                            <span class="badge bg-soft-success text-success">Cash</span>
                                                        @elseif($method == 'kartu')
                                                            <span class="badge bg-soft-primary text-primary">Card</span>
                                                        @elseif($method == 'e-wallet')
                                                            <span class="badge bg-soft-info text-info">E-Wallet</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $data['count'] }}</td>
                                                    <td>Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                                                    <td>{{ number_format(($data['total'] / $totalRevenue) * 100, 2) }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5>Transaction List</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="reportTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Payment</th>
                                                <th>Method</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $transaction)
                                                @php
                                                    $orderIds = explode(',', $transaction->idpesanan);
                                                    $orders = \App\Models\Pesanan::whereIn('idpesanan', $orderIds)->get();
                                                    $customer = $orders->first() ? $orders->first()->pelanggan : null;
                                                    $itemCount = $orders->sum('jumlah');
                                                @endphp
                                                <tr>
                                                    <td>{{ $transaction->idtransaksi }}</td>
                                                    <td>{{ $transaction->tanggal->format('d M Y H:i') }}</td>
                                                    <td>{{ $customer ? $customer->nama_pelanggan : 'N/A' }}</td>
                                                    <td>{{ $itemCount }} items</td>
                                                    <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($transaction->bayar, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($transaction->metode_pembayaran == 'tunai')
                                                            <span class="badge bg-soft-success text-success">Cash</span>
                                                        @elseif($transaction->metode_pembayaran == 'kartu')
                                                            <span class="badge bg-soft-primary text-primary">Card</span>
                                                        @elseif($transaction->metode_pembayaran == 'e-wallet')
                                                            <span class="badge bg-soft-info text-info">E-Wallet</span>
                                                        @endif
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