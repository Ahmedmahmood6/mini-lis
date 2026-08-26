@extends('layouts.app')

@section('title', 'Create New Order')
@section('page-title', 'Create New Order')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reception.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Orders
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-3">Create New Lab Order</h1>
            <p class="text-sm text-slate-500 mt-0.5">Select a patient, choose tests, and generate an invoice.</p>
        </div>

        <form method="POST" action="{{ route('reception.orders.store') }}" class="space-y-6">
            @csrf

            @if($selectedAppointment)
                <input type="hidden" name="appointment_id" value="{{ $selectedAppointment->id }}">
            @endif

            <!-- Patient Selection -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-sm font-bold text-slate-800 mb-4">1. Select Patient</h2>

                <div>
                    <label for="patient_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="patient_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('patient_id') border-red-400 @enderror">
                        <option value="">-- Select a patient --</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}"
                                @selected(old('patient_id', $selectedPatient?->id) == $patient->id)>
                                {{ $patient->name }} — {{ $patient->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-400 mt-1.5">
                        Can't find the patient? <a href="{{ route('reception.patients.create') }}" class="text-blue-600 hover:underline">Add a new patient</a> first.
                    </p>
                </div>
            </div>

            <!-- Tests Selection -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-sm font-bold text-slate-800 mb-4">2. Select Tests <span class="text-red-500">*</span></h2>

                @error('test_ids') <p class="text-xs text-red-600 mb-3">{{ $message }}</p> @enderror

                <div class="space-y-4 max-h-96 overflow-y-auto pr-1">
                    @foreach($tests->groupBy('category') as $category => $categoryTests)
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">{{ $category }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($categoryTests as $test)
                                    <label class="flex items-center gap-2.5 border border-slate-200 rounded-xl px-3.5 py-2.5 cursor-pointer hover:bg-slate-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 transition-colors">
                                            <input type="checkbox" name="test_ids[]" value="{{ $test->id }}"
                                                {{ in_array($test->id, old('test_ids', $selectedAppointment && $selectedAppointment->test_id ? [$selectedAppointment->test_id] : [])) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 test-checkbox"
                                                data-price="{{ $test->price }}">
                                        <span class="flex-1 text-sm text-slate-700">{{ $test->name }}</span>
                                        <span class="text-xs font-semibold text-slate-500">{{ number_format($test->price, 2) }} EGP</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-500">Selected tests total:</span>
                    <span id="tests-total" class="text-base font-bold text-slate-900">0.00 EGP</span>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-sm font-bold text-slate-800 mb-4">3. Payment Details</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="discount" class="block text-sm font-semibold text-slate-700 mb-1.5">Discount (EGP)</label>
                        <input type="number" step="0.01" min="0" name="discount" id="discount" value="{{ old('discount', 0) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('discount') border-red-400 @enderror">
                        @error('discount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="paid_amount" class="block text-sm font-semibold text-slate-700 mb-1.5">Paid Amount (EGP)</label>
                        <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', 0) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('paid_amount') border-red-400 @enderror">
                        @error('paid_amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                    @error('payment_method') <p class="text-xs text-red-600 mb-2">{{ $message }}</p> @enderror
                    <div class="flex gap-3">
                        <label class="flex-1 flex items-center justify-center gap-2 border border-slate-200 rounded-xl px-4 py-2.5 cursor-pointer hover:bg-slate-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 transition-colors">
                            <input type="radio" name="payment_method" value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700">Cash</span>
                        </label>
                        <label class="flex-1 flex items-center justify-center gap-2 border border-slate-200 rounded-xl px-4 py-2.5 cursor-pointer hover:bg-slate-50 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 transition-colors">
                            <input type="radio" name="payment_method" value="card" {{ old('payment_method') === 'card' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700">Card</span>
                        </label>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1.5">Notes (optional)</label>
                    <textarea name="notes" id="notes" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('reception.orders.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-colors">
                    Create Order & Invoice
                </button>
            </div>
        </form>
    </div>

    <script>
        // Live-update the selected tests total whenever a checkbox is toggled
        document.querySelectorAll('.test-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', updateTestsTotal);
        });

        function updateTestsTotal() {
            let total = 0;
            document.querySelectorAll('.test-checkbox:checked').forEach(function (checkbox) {
                total += parseFloat(checkbox.dataset.price) || 0;
            });
            document.getElementById('tests-total').textContent = total.toFixed(2) + ' EGP';
        }

        updateTestsTotal();
    </script>
@endsection