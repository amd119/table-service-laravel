<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order Report</title>
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
        .order-statuses {
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
        .badge-warning {
            background-color: #ffc107;
        }
        .badge-primary {
            background-color: #007bff;
        }
        .badge-success {
            background-color: #28a745;
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
        <h1>Order Report</h1>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="summary clearfix">
        <div class="summary-box">
            <h3>Total Orders</h3>
            <p style="font-size: 18px; font-weight: bold;">{{ $orders->count() }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Items</h3>
            <p style="font-size: 18px; font-weight: bold;">{{ $totalItems }}</p>
        </div>
        <div class="summary-box">
            <h3>Average Items Per Order</h3>
            <p style="font-size: 18px; font-weight: bold;">{{ $orders->count() > 0 ? number_format($totalItems / $orders->count(), 1) : 0 }}</p>
        </div>
    </div>

    <div class="order-statuses">
        <h2>Order Status</h2>
        <table>
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
                                <span class="badge badge-warning">New</span>
                            @elseif($status == 'diproses')
                                <span class="badge badge-primary">Processing</span>
                            @elseif($status == 'selesai')
                                <span class="badge badge-success">Completed</span>
                            @elseif($status == 'dibayar')
                                <span class="badge badge-info">Paid</span>
                            @endif
                        </td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ number_format(($data['count'] / $orders->count()) * 100, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2>Order List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Menu</th>
                <th>Qty</th>
                <th>Table</th>
                <th>Status</th>
                <th>Waiter</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->idpesanan }}</td>
                    <td>{{ $order->tanggal->format('d M Y H:i') }}</td>
                    <td>{{ $order->pelanggan->nama_pelanggan }}</td>
                    <td>{{ $order->menu->nama_menu }}</td>
                    <td>{{ $order->jumlah }}</td>
                    <td>{{ $order->meja->nomor }}</td>
                    <td>
                        @if($order->status == 'baru')
                            <span class="badge badge-warning">New</span>
                        @elseif($order->status == 'diproses')
                            <span class="badge badge-primary">Processing</span>
                        @elseif($order->status == 'selesai')
                            <span class="badge badge-success">Completed</span>
                        @elseif($order->status == 'dibayar')
                            <span class="badge badge-info">Paid</span>
                        @endif
                    </td>
                    <td>{{ $order->waiter->username }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>