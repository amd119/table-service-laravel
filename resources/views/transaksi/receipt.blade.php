@extends('layouts.app')

@section('title', 'Transaction Receipt')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Transaction Receipt</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item">Receipt</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <button class="btn btn-primary" id="printReceipt">
                                <i class="feather-printer me-2"></i>
                                <span>Print Receipt</span>
                            </button>
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
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-4" id="receipt">
                                <div class="text-center mb-4">
                                    <h4 class="mb-1">KASIR RESTORAN</h4>
                                    <p class="mb-0">Jl. Contoh No. 123, Kota Example</p>
                                    <p class="mb-0">Tel: (021) 123-4567</p>
                                </div>
                                
                                <div class="border-top border-bottom py-3 mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-0">
                                                <strong>Receipt #:</strong> {{ $transaction->idtransaksi }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>Date:</strong> {{ $transaction->tanggal->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="mb-0">
                                                <strong>Cashier:</strong> {{ $transaction->kasir->username }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>Table:</strong> #{{ $orders->first()->meja->nomor }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->menu->nama_menu }}</td>
                                                <td class="text-center">{{ $order->jumlah }}</td>
                                                <td class="text-end">{{ number_format($order->menu->harga, 0, ',', '.') }}</td>
                                                <td class="text-end">{{ number_format($order->menu->harga * $order->jumlah, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <div class="border-top pt-3">
                                    <div class="row mb-2">
                                        <div class="col-6 text-end">
                                            <strong>Total:</strong>
                                        </div>
                                        <div class="col-6 text-end">
                                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-end">
                                            <strong>Payment ({{ $transaction->metode_pembayaran == 'tunai' ? 'Cash' : ($transaction->metode_pembayaran == 'kartu' ? 'Card' : 'E-Wallet') }}):</strong>
                                        </div>
                                        <div class="col-6 text-end">
                                            Rp {{ number_format($transaction->bayar, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @if($transaction->metode_pembayaran == 'tunai')
                                        <div class="row mb-2">
                                            <div class="col-6 text-end">
                                                <strong>Change:</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                Rp {{ number_format($transaction->bayar - $transaction->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="text-center mt-4">
                                    <p class="mb-1">Thank you for your purchase!</p>
                                    <p class="mb-0 small">Please come again</p>
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
        $("#printReceipt").click(function() {
            const receiptContent = document.getElementById('receipt').innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div style="width: 80mm; margin: 0 auto;">
                    ${receiptContent}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        });
    });
</script>
@endpush