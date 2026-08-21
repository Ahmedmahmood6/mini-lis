<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders List - Mini LIS</title>
</head>
<body>
    <h1>Lab Orders List</h1>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <ul>
        @foreach($orders as $order)
            <li>{{ $order->order_number }} - {{ $order->patient->name }} - Status: {{ $order->status }}</li>
        @endforeach
    </ul>
</body>
</html>
