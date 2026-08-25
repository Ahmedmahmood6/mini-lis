@extends('layouts.app')

@section('title', 'Enter Test Results')
@section('page-title', 'Enter Test Results')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('lab.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Lab Queue
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-3">Enter Test Results: {{ $order->order_number }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Patient: <span class="font-semibold text-slate-700">{{ $order->patient->name }}</span>
                — {{ $order->patient->phone }}
            </p>
        </div>

        <form method="POST" action="{{ route('lab.orders.results.update', $order) }}" class="space-y-5">
            @csrf
            @method('PUT')

            @foreach($order->orderItems as $index => $item)
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                    <input type="hidden" name="results[{{ $index }}][order_item_id]" value="{{ $item->id }}">

                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">{{ $item->test->name }}</h2>
                            <p class="text-xs text-slate-400">
                                Code: {{ $item->test->code }}
                                @if($item->test->reference_range)
                                    • Reference: {{ $item->test->reference_range }}
                                @endif
                                @if($item->test->unit)
                                    ({{ $item->test->unit }})
                                @endif
                            </p>
                        </div>
                        @if(($item->result?->status ?? 'pending') === 'entered')
                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full shrink-0">Entered</span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full shrink-0">Pending</span>
                        @endif
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Result Value <span class="text-red-500">*</span></label>
                            <textarea name="results[{{ $index }}][result_text]" rows="2" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('results.'.$index.'.result_text') border-red-400 @enderror"
                                        placeholder="Enter the test result value">{{ old('results.'.$index.'.result_text', $item->result?->result_text) }}</textarea>
                            @error('results.'.$index.'.result_text') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Notes (optional)</label>
                            <input type="text" name="results[{{ $index }}][notes]" value="{{ old('results.'.$index.'.notes', $item->result?->notes) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g. Slightly elevated, recommend re-test">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('lab.orders.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-colors">
                    Save Results
                </button>
            </div>
        </form>
    </div>
@endsection