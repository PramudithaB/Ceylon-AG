<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ceylon AG - Master Financial & Inventory Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; margin: 0; padding: 24px; background: #fff; }
        .header { border-bottom: 3px solid #059669; padding-bottom: 12px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-end; }
        .brand { font-size: 24px; font-weight: 900; color: #059669; text-transform: uppercase; letter-spacing: 1px; }
        .sub-title { font-size: 14px; font-weight: 700; color: #475569; }
        .date { font-size: 11px; color: #64748b; margin-top: 4px; }
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .card { border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px; background: #f8fafc; }
        .card-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #64748b; }
        .card-val { font-size: 16px; font-weight: 900; color: #059669; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px 12px; text-align: left; font-size: 12px; }
        th { background-color: #f1f5f9; font-weight: 800; text-transform: uppercase; font-size: 10px; color: #475569; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        .btn-print { background: #059669; color: #fff; padding: 10px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; float: right; }
        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print">Print / Save PDF</button>

    <div class="header">
        <div style="display: flex; align-items: center; gap: 14px;">
            <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Logo" style="height: 52px; width: 52px; object-fit: contain; border-radius: 10px;">
            <div>
                <div class="brand">Ceylon AG</div>
                <div class="sub-title">Master Financial & Inventory Report</div>
                <div class="date">Generated on {{ date('F d, Y \a\t H:i:s') }}</div>
            </div>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid">
        <div class="card">
            <div class="card-label">Total Retail Sales</div>
            <div class="card-val">LKR {{ number_format($summary['total_sales'], 2) }}</div>
        </div>
        <div class="card">
            <div class="card-label">Approved Payments</div>
            <div class="card-val" style="color: #0d9488;">LKR {{ number_format($summary['total_payments'], 2) }}</div>
        </div>
        <div class="card">
            <div class="card-label">Outstanding Balance</div>
            <div class="card-val" style="color: #d97706;">LKR {{ number_format($summary['outstanding_balance'], 2) }}</div>
        </div>
        <div class="card">
            <div class="card-label">Remaining Stock</div>
            <div class="card-val" style="color: #4f46e5;">{{ $summary['remaining_stock'] }} units</div>
        </div>
    </div>

    <!-- Top Clients -->
    <h3 style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Top Client Partners</h3>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Client Partner Name</th>
                <th>Business Name</th>
                <th>Units Sold</th>
                <th class="text-right">Total Sales Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topClients as $index => $c)
                <tr>
                    <td class="font-bold">#{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $c['name'] }}</td>
                    <td>{{ $c['business_name'] }}</td>
                    <td>{{ $c['total_units'] }} units</td>
                    <td class="text-right font-bold" style="color: #059669;">LKR {{ number_format($c['total_revenue'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Inventory Health -->
    <h3 style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Product Inventory Health Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Assigned Qty</th>
                <th>Sold Qty</th>
                <th>Remaining Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventoryBreakdown as $inv)
                <tr>
                    <td class="font-bold">{{ $inv['name'] }}</td>
                    <td style="font-family: monospace;">{{ $inv['sku'] }}</td>
                    <td>{{ $inv['assigned_qty'] }} units</td>
                    <td>{{ $inv['sold_qty'] }} units</td>
                    <td class="font-bold" style="color: #4f46e5;">{{ $inv['remaining_qty'] }} units</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
