<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices List - Mini LIS</title>
</head>
<body>
    <h1>Invoices List</h1>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <ul>
        @foreach($invoices as $invoice)
            <li>{{ $invoice->invoice_number }} - Net: {{ $invoice->net_amount }} EGP - Status: {{ $invoice->payment_status }}</li>
        @endforeach
    </ul>
</body>
</html>
