@extends('layouts.app')

@section('title', 'Tests Catalog')
@section('page-title', 'Lab Tests Catalog')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Lab Tests Catalog</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage available laboratory tests, prices and reference ranges.</p>
        </div>
        <a href="{{ route('admin.tests.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Test
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.tests.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by test name or code..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <select name="category" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                Filter
            </button>

            @if($search || $category)
                <a href="{{ route('admin.tests.index') }}"
                    class="inline-flex items-center justify-center text-sm font-medium text-slate-500 hover:text-slate-700 px-3 py-2.5">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Tests Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Code</th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Reference Range</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3">Price</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tests as $test)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-blue-700">{{ $test->code }}</td>
                            <td class="px-5 py-3.5 font-medium text-slate-800">{{ $test->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $test->category }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $test->reference_range ?: '—' }}</td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $test->unit ?: '—' }}</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">{{ number_format($test->price, 2) }} EGP</td>
                            <td class="px-5 py-3.5">
                                @if($test->is_active)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tests.edit', $test) }}"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.tests.destroy', $test) }}" method="POST"
                                            onsubmit="return confirm('Delete or deactivate this test?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <p class="text-sm">No tests found in the catalog.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tests->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $tests->links() }}
            </div>
        @endif
    </div>
@endsection