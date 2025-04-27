<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Transaction Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        h3 {
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        p {
            margin: 2px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-box {
            width: 30%;
            float: left;
            text-align: center;
            margin-right: 3%;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .payment-methods {
            clear: both;
            margin-bottom: 20px;
            padding-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-primary {
            background-color: #007bff;
        }
        .badge-info {
            background-color: #17a2b8;
        }
        .page-break {
            page-break-after: always;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Report</h1>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="summary clearfix">
        <div class="summary-box">
            <h3>Total Transactions</h3>
            <p style="font-size: 18px; font-weight: bold;">{{ $transactions->count() }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Revenue</h3>
            <p style="font-size: 18px; font-weight: bold;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Average Transaction</h3>
            <p style="font-size: 18px; font-weight: bold;">Rp {{ $transactions->count() > 0 ? number_format($totalRevenue / $transactions->count(), 0, ',', '.') : 0 }}</p>
        </div>
    </div>

    <div class="payment-methods">
        <h2>Payment Methods</h2>
        <table>
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
                                <span class="badge badge-success">Cash</span>
                            @elseif($method == 'kartu')
                                <span class="badge badge-primary">Card</span>
                            @elseif($method == 'e-wallet')
                                <span class="badge badge-info">E-Wallet</span>
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

    <h2>Transaction List</h2>
    <table>
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
                            <span class="badge badge-success">Cash</span>
                        @elseif($transaction->metode_pembayaran == 'kartu')
                            <span class="badge badge-primary">Card</span>
                        @elseif($transaction->metode_pembayaran == 'e-wallet')
                            <span class="badge badge-info">E-Wallet</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>