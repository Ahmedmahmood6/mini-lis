<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tests Catalog - Mini LIS</title>
</head>
<body>
    <h1>Lab Tests Catalog</h1>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div>{{ session('warning') }}</div>
    @endif
    <ul>
        @foreach($tests as $test)
            <li>{{ $test->code }} - {{ $test->name }} - {{ $test->price }} EGP</li>
        @endforeach
    </ul>
</body>
</html>
