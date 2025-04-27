@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content-dashboard')
    @include('layouts.partials.sidebar')
    @include('layouts.partials.header')

    <main class="nxl-container" style="display:flex;flex-direction:column;min-height:97vh;">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Create Transaction</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transactions</a></li>
                        <li class="breadcrumb-item">Create</li>
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
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5>Pending Orders</h5>
                            </div>
                            <div class="card-body">
                                @if($pendingOrders->isEmpty())
                                    <div class="alert alert-info">
                                        No pending orders available for transaction.
                                    </div>
                                @else
                                    <form action="{{ route('transaksi.process-payment') }}" method="POST">
                                        @csrf
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 50px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                            </div>
                                                        </th>
                                                        <th>Customer</th>
                                                        <th>Table</th>
                                                        <th>Order Items</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingOrders as $customerId => $orders)
                                                        @php
                                                            $customer = $orders->first()->pelanggan;
                                                            $table = $orders->first()->meja;
                                                            $totalAmount = 0;
                                                            foreach($orders as $order) {
                                                                $totalAmount += $order->menu->harga * $order->jumlah;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input customer-checkbox" 
                                                                           type="checkbox" 
                                                                           name="customer_ids[]" 
                                                                           value="{{ $customerId }}"
                                                                           data-amount="{{ $totalAmount }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <strong>{{ $customer->nama_pelanggan }}</strong>
                                                                    <p class="mb-0 text-muted">{{ $customer->no_hp }}</p>
                                                                </div>
                                                            </td>
                                                            <td>Table #{{ $table->nomor }}</td>
                                                            <td>
                                                                <ul class="list-unstyled mb-0">
                                                                    @foreach($orders as $order)
                                                                        <li>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input order-checkbox customer-{{ $customerId }}" 
                                                                                       type="checkbox" 
                                                                                       name="order_ids[]" 
                                                                                       value="{{ $order->idpesanan }}"
                                                                                       data-customer="{{ $customerId }}"
                                                                                       data-price="{{ $order->menu->harga * $order->jumlah }}">
                                                                                <label class="form-check-label">
                                                                                    {{ $order->menu->nama_menu }} x {{ $order->jumlah }}
                                                                                    (Rp {{ number_format($order->menu->harga * $order->jumlah, 0, ',', '.') }})
                                                                                </label>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                            <td>Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary" id="proceedBtn" disabled>
                                                <i class="feather-arrow-right me-2"></i>
                                                <span>Proceed to Payment</span>
                                            </button>
                                        </div>
                                    </form>
                                @endif
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
        // Select all checkbox
        $('#selectAll').change(function() {
            $('.customer-checkbox').prop('checked', $(this).prop('checked'));
            $('.order-checkbox').prop('checked', $(this).prop('checked'));
            updateProceedButton();
        });

        // Customer checkbox
        $('.customer-checkbox').change(function() {
            var customerId = $(this).val();
            $('.customer-' + customerId).prop('checked', $(this).prop('checked'));
            updateProceedButton();
        });

        // Individual order checkbox
        $('.order-checkbox').change(function() {
            updateProceedButton();
            
            // Check if all orders for a customer are selected
            var customerId = $(this).data('customer');
            var totalOrders = $('.customer-' + customerId).length;
            var checkedOrders = $('.customer-' + customerId + ':checked').length;
            
            if (checkedOrders === totalOrders) {
                $('input[value="' + customerId + '"].customer-checkbox').prop('checked', true);
            } else {
                $('input[value="' + customerId + '"].customer-checkbox').prop('checked', false);
            }
        });

        function updateProceedButton() {
            var anyChecked = $('.order-checkbox:checked').length > 0;
            $('#proceedBtn').prop('disabled', !anyChecked);
        }
    });
</script>
@endpush