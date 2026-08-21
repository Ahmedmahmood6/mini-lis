<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patients List - Mini LIS</title>
</head>
<body>
    <h1>Patients List</h1>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <ul>
        @foreach($patients as $patient)
            <li>{{ $patient->name }} - {{ $patient->phone }}</li>
        @endforeach
    </ul>
</body>
</html>
