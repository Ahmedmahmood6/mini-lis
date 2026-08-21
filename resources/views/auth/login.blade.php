<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini LIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Mini LIS</h1>
        <p class="text-slate-500 text-sm mt-1">Laboratory Information System</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 p-4 rounded-lg text-sm mb-6 border border-red-200">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-slate-800">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-slate-800">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2 text-sm text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-500">
                <span>Remember me</span>
            </label>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 shadow">
            Sign In
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-200 text-xs text-slate-500 text-center space-y-1">
        <p>Demo Admin: <span class="font-semibold text-slate-700">admin@minilis.com</span> | password</p>
        <p>Demo Receptionist: <span class="font-semibold text-slate-700">receptionist@minilis.com</span> | password</p>
        <p>Demo Technician: <span class="font-semibold text-slate-700">technician@minilis.com</span> | password</p>
    </div>
</div>

</body>
</html>
