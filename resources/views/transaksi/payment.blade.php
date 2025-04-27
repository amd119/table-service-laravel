@extends('layouts.app')

@section('title', 'Process Payment')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Process Payment</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item">Payment</li>
                    </ul>
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
                                                <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
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
                                <h5>Payment Information</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('transaksi.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="total" class="form-label">Total Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control" id="total" name="total" value="{{ $total }}" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bayar" class="form-label">Payment Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="bayar" name="bayar" min="{{ $total }}" value="{{ $total }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kembalian" class="form-label">Change</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control" id="kembalian" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="metode_pembayaran" class="form-label">Payment Method</label>
                                        <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" data-select2-selector="tag" required>
                                            <option value="tunai">Cash</option>
                                            <option value="kartu">Card</option>
                                            <option value="e-wallet">E-Wallet</option>
                                        </select>
                                    </div>
                                    
                                    @foreach($orderIds as $orderId)
                                        <input type="hidden" name="idpesanan[]" value="{{ $orderId }}">
                                    @endforeach
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="feather-check me-2"></i>
                                            <span>Complete Transaction</span>
                                        </button>
                                        <a href="{{ route('transaksi.create') }}" class="btn btn-light">
                                            <i class="feather-arrow-left me-2"></i>
                                            <span>Back</span>
                                        </a>
                                    </div>
                                </form>
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
        // Calculate change
        $('#bayar').on('input', function() {
            const total = parseFloat($('#total').val());
            const paid = parseFloat($(this).val());
            const change = paid - total;
            
            $('#kembalian').val(change >= 0 ? change : 0);
        });
        
        // Format thousand separator for payment amount
        $('#bayar').on('blur', function() {
            const value = $(this).val();
            if (value) {
                $(this).val(parseFloat(value).toFixed(0));
            }
        });
    });
</script>
@endpush