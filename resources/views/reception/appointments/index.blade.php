<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointments Management - Mini LIS</title>
</head>
<body>
    <h1>Appointments List</h1>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
</body>
</html>
