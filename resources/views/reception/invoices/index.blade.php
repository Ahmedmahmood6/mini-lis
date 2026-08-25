@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Invoices & Payments</h2>
        <p class="text-sm text-slate-500 mt-0.5">Track payment status for all lab order invoices.</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5">
        <form method="GET" action="{{ route('reception.invoices.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ old('search', request('search')) }}"
                    placeholder="Search by invoice number, order number, or patient..."
                    class="w-full pl-10 pr-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border border-slate-300 transition-colors duration-150 outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500/20 placeholder-slate-400">
            </div>
            <select name="status" onchange="this.form.submit()"
                class="px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500/20">
                <option value="">All Statuses</option>
                <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
                <option value="partially_paid" @selected(request('status') === 'partially_paid')>Partially Paid</option>
                <option value="paid" @selected(request('status') === 'paid')>Paid</option>
            </select>
            <div class="flex gap-2">
                <x-button type="submit" variant="primary" size="md">
                    Search
                </x-button>
                @if (request('search') || request('status'))
                <x-button href="{{ route('reception.invoices.index') }}" variant="outline" size="md">
                    Clear
                </x-button>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Invoices Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Invoice #</th>
                        <th class="px-5 py-3">Order</th>
                        <th class="px-5 py-3">Patient</th>
                        <th class="px-5 py-3">Net Amount</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $invoice)
                        @php
                            $statusStyles = [
                                'unpaid' => 'bg-red-50 text-red-700',
                                'partially_paid' => 'bg-amber-50 text-amber-700',
                                'paid' => 'bg-emerald-50 text-emerald-700',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-blue-700">{{ $invoice->invoice_number }}</td>
                            <td class="px-5 py-3.5 text-slate-600 font-mono text-xs">{{ $invoice->order->order_number ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-800">{{ $invoice->order->patient->name ?? '—' }}</p>
                                <p class="text-xs text-slate-400">{{ $invoice->order->patient->phone ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">{{ number_format($invoice->net_amount, 2) }} EGP</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 {{ $statusStyles[$invoice->payment_status] ?? 'bg-slate-100 text-slate-600' }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ str_replace('_', ' ', ucfirst($invoice->payment_status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('reception.invoices.show', $invoice) }}"
                                    class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <p class="text-sm">{{ request('search') || request('status') ? 'No invoices match your filters.' : 'No invoices found yet.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection