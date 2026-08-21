<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Online Lab Appointment - Mini LIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen py-10 px-4">

<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
    {{-- Header --}}
    <div class="bg-blue-600 px-8 py-6 text-white flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Book Online Lab Appointment</h1>
            <p class="text-blue-100 text-sm mt-1">Fill out the form below to schedule your laboratory test.</p>
        </div>
        <a href="{{ route('login') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium text-sm rounded-xl backdrop-blur transition border border-white/20 shrink-0">
            Staff Login
        </a>
    </div>

    <div class="p-8">
        {{-- Flash Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-start space-x-3">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <p class="font-semibold text-sm mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Booking Form --}}
        <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Ahmed Mohamed"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           placeholder="e.g. 01012345678"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gender</label>
                    <select name="gender"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                {{-- Age --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Age</label>
                    <input type="number" name="age" value="{{ old('age', 25) }}" min="0" max="150"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                </div>
            </div>

            {{-- Select Test --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Requested Laboratory Test</label>
                <select name="test_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                    <option value="">-- Optional: Choose a Test --</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}" {{ old('test_id') == $test->id ? 'selected' : '' }}>
                            {{ $test->name }} ({{ $test->code }}) — {{ number_format($test->price, 2) }} EGP
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Preferred Date --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Preferred Date *</label>
                    <input type="date" name="preferred_date" value="{{ old('preferred_date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                </div>

                {{-- Preferred Time --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Preferred Time *</label>
                    <input type="time" name="preferred_time" value="{{ old('preferred_time', '10:00') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Notes / Medical History</label>
                <textarea name="notes" rows="3" placeholder="Any special notes or instructions..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm transition">{{ old('notes') }}</textarea>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 transition duration-200">
                Submit Appointment Booking
            </button>
        </form>
    </div>
</div>

</body>
</html>
