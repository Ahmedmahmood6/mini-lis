@extends('layouts.app')

@section('title', 'Register Patient')
@section('page-title', 'Register Patient')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('reception.patients.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-700 mb-2">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Patients
        </a>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Register New Patient</h2>
        <p class="text-sm text-slate-500 mt-0.5">Add a new patient record to the system.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('reception.patients.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <x-input name="name" label="Full Name" placeholder="e.g. Ahmed Mohamed" required />
                </div>

                <x-input name="phone" type="tel" label="Phone Number" placeholder="e.g. 01012345678" required />

                <div class="space-y-1.5">
                    <label for="gender" class="block text-sm font-medium text-slate-700">
                        Gender <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <select id="gender" name="gender" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border transition-colors duration-150 outline-none focus:ring-2 {{ $errors->has('gender') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }}">
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                        <option value="male" @selected(old('gender') == 'male')>Male</option>
                        <option value="female" @selected(old('gender') == 'female')>Female</option>
                    </select>
                    @error('gender')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <x-input name="age" type="number" label="Age" placeholder="e.g. 30" min="0" max="150" required />

                <x-input name="national_id" label="National ID" placeholder="e.g. 29001011234567" hint="Optional" />

                <div class="space-y-1.5 md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                    <textarea id="address" name="address" rows="3" placeholder="Street, city, area..."
                              class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border transition-colors duration-150 outline-none focus:ring-2 {{ $errors->has('address') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }}">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row-reverse gap-3 pt-4 border-t border-slate-100">
                <x-button type="submit" variant="primary" size="md">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Save Patient
                </x-button>
                <x-button href="{{ route('reception.patients.index') }}" variant="secondary" size="md">
                    Cancel
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection
