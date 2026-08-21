<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mini LIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">

<nav class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center space-x-3">
        <span class="text-xl font-bold text-blue-600">Mini LIS</span>
        <span class="text-xs bg-blue-100 text-blue-800 font-semibold px-2.5 py-0.5 rounded-full capitalize">
            {{ $user->role }}
        </span>
    </div>

    <div class="flex items-center space-x-4">
        <span class="text-sm font-medium text-slate-700">Welcome, {{ $user->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium transition">
                Logout
            </button>
        </form>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Dashboard Overview</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm font-medium text-slate-500">Total Patients</p>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $stats['total_patients'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm font-medium text-slate-500">Pending Appointments</p>
            <p class="text-3xl font-bold text-amber-600 mt-2">{{ $stats['pending_appointments'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm font-medium text-slate-500">Pending Orders</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['pending_orders'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm font-medium text-slate-500">Pending Results</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['pending_results'] }}</p>
        </div>
    </div>
</main>

</body>
</html>
