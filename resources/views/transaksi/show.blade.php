@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Transaction Details</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item">Details</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('transaksi.receipt', $transaction->idtransaksi) }}" class="btn btn-primary">
                                <i class="feather-file-text me-2"></i>
                                <span>View Receipt</span>
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
                    <div class="col-lg-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5>Order Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Menu</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>{{ $order->menu->nama_menu }}</td>
                                                    <td>{{ $order->jumlah }}</td>
                                                    <td>Rp {{ number_format($order->menu->harga, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($order->menu->harga * $order->jumlah, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total:</th>
                                                <th>Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5>Transaction Information</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Transaction ID:</span>
                                        <span>{{ $transaction->idtransaksi }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Date:</span>
                                        <span>{{ $transaction->tanggal->format('d M Y H:i') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Customer:</span>
                                        <span>{{ $orders->first()->pelanggan->nama_pelanggan }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Table:</span>
                                        <span>Table #{{ $orders->first()->meja->nomor }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Total Amount:</span>
                                        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Payment Amount:</span>
                                        <span>Rp {{ number_format($transaction->bayar, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Change:</span>
                                        <span>Rp {{ number_format($transaction->bayar - $transaction->total, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Payment Method:</span>
                                        <span>
                                            @if($transaction->metode_pembayaran == 'tunai')
                                                <span class="badge bg-soft-success text-success">Cash</span>
                                            @elseif($transaction->metode_pembayaran == 'kartu')
                                                <span class="badge bg-soft-primary text-primary">Card</span>
                                            @elseif($transaction->metode_pembayaran == 'e-wallet')
                                                <span class="badge bg-soft-info text-info">E-Wallet</span>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="fw-bold">Cashier:</span>
                                        <span>{{ $transaction->kasir->username }}</span>
                                    </li>
                                </ul>
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