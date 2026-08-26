@extends('layouts.app')

@section('title', 'Add Staff Member')
@section('page-title', 'Add Staff Member')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Create New Staff Account</h1>
                <p class="text-sm text-slate-500 mt-0.5">Add a new receptionist or technician to the laboratory system.</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-200/70 hover:bg-slate-200 px-3 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Staff List
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="e.g. John Doe"
                        class="w-full px-4 py-2.5 rounded-xl border @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-200 focus:ring-blue-500 focus:border-blue-500 @enderror text-sm focus:outline-none focus:ring-2">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        placeholder="staff@minilis.com"
                        class="w-full px-4 py-2.5 rounded-xl border @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-200 focus:ring-blue-500 focus:border-blue-500 @enderror text-sm focus:outline-none focus:ring-2">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password & Password Confirmation -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required minlength="8"
                            placeholder="Minimum 8 characters"
                            class="w-full px-4 py-2.5 rounded-xl border @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-200 focus:ring-blue-500 focus:border-blue-500 @enderror text-sm focus:outline-none focus:ring-2">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                            placeholder="Re-enter password"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 rounded-xl border @error('role') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-200 focus:ring-blue-500 focus:border-blue-500 @enderror text-sm focus:outline-none focus:ring-2">
                        <option value="" disabled @selected(!old('role'))>Select a role...</option>
                        <option value="receptionist" @selected(old('role') === 'receptionist')>Receptionist</option>
                        <option value="technician" @selected(old('role') === 'technician')>Technician</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-colors">
                        Create Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
