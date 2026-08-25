@extends('layouts.app')

@section('title', 'Lab Work Queue')
@section('page-title', 'Lab Work Queue')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Lab Work Queue</h1>
        <p class="text-sm text-slate-500 mt-0.5">Orders awaiting test results entry.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Order #</th>
                        <th class="px-5 py-3">Patient</th>
                        <th class="px-5 py-3">Tests</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Created</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-blue-700">{{ $order->order_number }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-800">{{ $order->patient->name }}</p>
                                <p class="text-xs text-slate-400">{{ $order->patient->phone }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $order->order_items_count }} test(s)</td>
                            <td class="px-5 py-3.5">
                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">Pending</span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">In Progress</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{{ $order->created_at->format('d M, h:i A') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('lab.orders.results.edit', $order) }}"
                                    class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                    Enter Results
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <p class="text-sm">No orders in the lab queue right now.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection