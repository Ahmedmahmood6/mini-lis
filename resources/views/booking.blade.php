<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book Online Lab Appointment - Mini LIS</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN + Configuration (matches app design system) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="min-h-full bg-slate-50 text-slate-900 antialiased selection:bg-blue-600 selection:text-white">

    <div class="relative min-h-screen">
        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-40 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-blue-400/20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-teal-400/20 blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Top Bar -->
            <header class="px-4 sm:px-6 lg:px-8 py-5">
                <div class="max-w-3xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-base font-bold text-slate-900 tracking-tight leading-none">Mini LIS</h1>
                            <span class="text-[11px] text-blue-600 font-medium">Laboratory System</span>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-xs transition-colors">
                        Staff Login
                    </a>
                </div>
            </header>

            <!-- Hero -->
            <div class="px-4 sm:px-6 lg:px-8 pt-4 pb-8 text-center">
                <div class="max-w-2xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Book Your Lab Appointment Online</h2>
                    <p class="mt-3 text-sm sm:text-base text-slate-600">
                        Schedule a laboratory visit in a few seconds. Our reception team will review your request and confirm your appointment shortly.
                    </p>

                    <div class="mt-5 flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
                        <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Accredited Laboratory
                        </div>
                        <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Fast Turnaround
                        </div>
                        <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            Confidential &amp; Secure
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 pb-12">
                <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        {{-- Flash Success Message --}}
                        @if (session('success'))
                            <x-alert type="success" class="mb-6" :dismissible="false">
                                {{ session('success') }}
                            </x-alert>
                        @endif

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <x-alert type="error" title="Please fix the following errors:" class="mb-6">
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </x-alert>
                        @endif

                        <form action="{{ route('booking.store') }}" method="POST" class="space-y-7" id="booking-form">
                            @csrf

                            <!-- Patient Information -->
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider mb-4">Patient Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <x-input name="name" label="Full Name" placeholder="e.g. Ahmed Mohamed" required />
                                    <x-input name="phone" type="tel" label="Phone Number" placeholder="e.g. 01012345678" required />

                                    <div class="space-y-1.5">
                                        <label for="gender" class="block text-sm font-medium text-slate-700">Gender</label>
                                        <select id="gender" name="gender"
                                                class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border transition-colors duration-150 outline-none focus:ring-2 {{ $errors->has('gender') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }}">
                                            <option value="male" @selected(old('gender') == 'male')>Male</option>
                                            <option value="female" @selected(old('gender') == 'female')>Female</option>
                                        </select>
                                        @error('gender')
                                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <x-input name="age" type="number" label="Age" placeholder="e.g. 30" min="0" max="150" value="{{ old('age') }}" />
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="pt-1 border-t border-slate-100">
                                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider mb-4 mt-6">Appointment Details</h3>
                                <div class="space-y-5">
                                    <div class="space-y-1.5">
                                        <label for="test_id" class="block text-sm font-medium text-slate-700">Requested Laboratory Test</label>
                                        <select id="test_id" name="test_id"
                                                class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border transition-colors duration-150 outline-none focus:ring-2 {{ $errors->has('test_id') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }}">
                                            <option value="">-- Optional: Choose a Test --</option>
                                            @foreach ($tests as $test)
                                                <option value="{{ $test->id }}" @selected(old('test_id') == $test->id)>
                                                    {{ $test->name }} ({{ $test->code }}) &mdash; {{ number_format($test->price, 2) }} EGP
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('test_id')
                                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-slate-500 mt-1">Not sure which test you need? Leave this blank and reception will assist you.</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <x-input name="preferred_date" type="date" label="Preferred Date" :value="old('preferred_date', now()->format('Y-m-d'))" min="{{ now()->format('Y-m-d') }}" required />
                                        <x-input name="preferred_time" type="time" label="Preferred Time" :value="old('preferred_time', '10:00')" required />
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="notes" class="block text-sm font-medium text-slate-700">Notes / Medical History</label>
                                        <textarea id="notes" name="notes" rows="3" placeholder="Any special notes or instructions..."
                                                  class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border transition-colors duration-150 outline-none focus:ring-2 {{ $errors->has('notes') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }}">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="pt-2">
                                <button type="submit" id="booking-submit"
                                        class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 transition duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                                    <svg id="booking-submit-spinner" class="hidden animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span id="booking-submit-label">Submit Appointment Booking</span>
                                </button>
                                <p class="text-center text-xs text-slate-400 mt-3">
                                    By submitting, our reception team may contact you by phone to confirm your appointment.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <p class="text-center text-xs text-slate-400 mt-6">
                    &copy; {{ date('Y') }} Mini LIS &mdash; Laboratory Information System. All rights reserved.
                </p>
            </main>
        </div>
    </div>

    <script>
        document.getElementById('booking-form')?.addEventListener('submit', function () {
            const button = document.getElementById('booking-submit');
            const spinner = document.getElementById('booking-submit-spinner');
            const label = document.getElementById('booking-submit-label');
            if (!button) return;
            button.disabled = true;
            spinner?.classList.remove('hidden');
            if (label) label.textContent = 'Submitting...';
        });
    </script>
</body>
</html>
