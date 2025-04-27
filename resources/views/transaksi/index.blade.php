@extends('layouts.app')

@section('title', 'Transactions')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Transactions</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Transactions</li>
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
                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Transaction</span>
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
                                    <table class="table table-hover" id="transactionList">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Payment</th>
                                                <th>Method</th>
                                                <th>Change</th>
                                                <th>Cashier</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $transaction)
                                                @php
                                                    $orderIds = explode(',', $transaction->idpesanan);
                                                    $orders = \App\Models\Pesanan::whereIn('idpesanan', $orderIds)->get();
                                                    $customer = $orders->first() ? $orders->first()->pelanggan : null;
                                                @endphp
                                                <tr class="single-item">
                                                    <td>{{ $transaction->idtransaksi }}</td>
                                                    <td>
                                                        <div>
                                                            <span class="text-truncate-1-line">{{ $customer ? $customer->nama_pelanggan : 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $transaction->tanggal->format('d M Y H:i') }}</td>
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
                                                    <td>Rp {{ number_format($transaction->bayar - $transaction->total, 0, ',', '.') }}</td>
                                                    <td>{{ $transaction->kasir ? $transaction->kasir->username : 'N/A' }}</td>
                                                    <td>
                                                        <div class="hstack justify-content-end">
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0)" class="avatar-text avatar-md" data-bs-toggle="dropdown">
                                                                    <i class="feather feather-more-horizontal"></i>
                                                                </a>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a href="{{ route('transaksi.show', $transaction->idtransaksi) }}" class="dropdown-item">
                                                                            <i class="feather feather-eye me-3"></i>
                                                                            <span>View</span>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('transaksi.receipt', $transaction->idtransaksi) }}" class="dropdown-item">
                                                                            <i class="feather feather-file-text me-3"></i>
                                                                            <span>Receipt</span>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#transactionList').DataTable({
            order: [[0, 'desc']]
        });
    });
</script>
@endpush