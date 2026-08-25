@extends('layouts.app')

@section('title', 'Edit Test')
@section('page-title', 'Edit Test')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.tests.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Tests Catalog
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-3">Edit Lab Test</h1>
            <p class="text-sm text-slate-500 mt-0.5">Update details for <span class="font-semibold text-slate-700">{{ $test->name }}</span>.</p>
        </div>

        <form method="POST" action="{{ route('admin.tests.update', $test) }}" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="code" class="block text-sm font-semibold text-slate-700 mb-1.5">Test Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $test->code) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-400 @enderror">
                    @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-semibold text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <input type="text" name="category" id="category" value="{{ old('category', $test->category) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-400 @enderror">
                    @error('category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Test Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $test->name) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="reference_range" class="block text-sm font-semibold text-slate-700 mb-1.5">Reference Range</label>
                    <input type="text" name="reference_range" id="reference_range" value="{{ old('reference_range', $test->reference_range) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reference_range') border-red-400 @enderror">
                    @error('reference_range') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="unit" class="block text-sm font-semibold text-slate-700 mb-1.5">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $test->unit) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit') border-red-400 @enderror">
                    @error('unit') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="price" class="block text-sm font-semibold text-slate-700 mb-1.5">Price (EGP) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', $test->price) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-400 @enderror">
                @error('price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $test->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-slate-700">Active (available for ordering)</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.tests.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-colors">
                    Update Test
                </button>
            </div>
        </form>
    </div>
@endsection