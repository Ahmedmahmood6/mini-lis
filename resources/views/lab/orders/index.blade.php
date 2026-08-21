<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Work Queue - Mini LIS</title>
</head>
<body>
    <h1>Lab Work Queue (Technician)</h1>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <ul>
        @foreach($orders as $order)
            <li>{{ $order->order_number }} - {{ $order->patient->name }} ({{ $order->order_items_count }} tests)</li>
        @endforeach
    </ul>
</body>
</html>
