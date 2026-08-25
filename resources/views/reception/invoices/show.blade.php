@extends('layouts.app')

@section('title', 'Invoice Details')
@section('page-title', 'Invoice Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('reception.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Back to Invoices
    </a>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @php
        $statusStyles = [
            'unpaid' => 'bg-red-50 text-red-700',
            'partially_paid' => 'bg-amber-50 text-amber-700',
            'paid' => 'bg-emerald-50 text-emerald-700',
        ];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Invoice {{ $invoice->invoice_number }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Order {{ $invoice->order->order_number ?? '—' }}</p>
        </div>
        <span class="inline-flex items-center gap-1.5 {{ $statusStyles[$invoice->payment_status] ?? 'bg-slate-100 text-slate-600' }} text-xs font-bold uppercase tracking-wide px-3 py-1.5 rounded-full w-fit">
            {{ str_replace('_', ' ', $invoice->payment_status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient & Order Info -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">Patient & Order</h2>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-400">Patient</dt>
                        <dd class="font-medium text-slate-800 mt-0.5">{{ $invoice->order->patient->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Phone</dt>
                        <dd class="font-medium text-slate-800 mt-0.5">{{ $invoice->order->patient->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Order Date</dt>
                        <dd class="font-medium text-slate-800 mt-0.5">{{ $invoice->order->created_at?->format('d M Y, h:i A') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Created By</dt>
                        <dd class="font-medium text-slate-800 mt-0.5">{{ $invoice->order->creator->name ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Tests / Line Items -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-900">Tests</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-3">Test</th>
                                <th class="px-6 py-3 text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($invoice->order->orderItems ?? [] as $item)
                                <tr>
                                    <td class="px-6 py-3.5 text-slate-800">{{ $item->test->name ?? '—' }}</td>
                                    <td class="px-6 py-3.5 text-right text-slate-600">{{ number_format($item->price, 2) }} EGP</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-slate-400 text-sm">No test items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Payment Summary -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">Payment Summary</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Total Amount</dt>
                        <dd class="font-medium text-slate-800">{{ number_format($invoice->total_amount, 2) }} EGP</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Discount</dt>
                        <dd class="font-medium text-slate-800">- {{ number_format($invoice->discount, 2) }} EGP</dd>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <dt class="text-slate-500 font-semibold">Net Amount</dt>
                        <dd class="font-bold text-slate-900">{{ number_format($invoice->net_amount, 2) }} EGP</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Paid Amount</dt>
                        <dd class="font-medium text-emerald-700">{{ number_format($invoice->paid_amount, 2) }} EGP</dd>
                    </div>
                    @if($invoice->payment_method)
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Payment Method</dt>
                        <dd class="font-medium text-slate-800">{{ ucfirst($invoice->payment_method) }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            @if($invoice->payment_status !== 'paid')
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">Record Payment</h2>
                <form method="POST" action="{{ route('reception.invoices.pay', $invoice) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Payment Method</label>
                        <select name="payment_method" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500/20">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Discount (EGP)</label>
                        <input type="number" step="0.01" min="0" name="discount" value="{{ $invoice->discount }}"
                            class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border border-slate-300 outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500/20">
                    </div>
                    <x-button type="submit" variant="primary" size="md" class="w-full justify-center">
                        Mark as Paid
                    </x-button>
                </form>
            </div>
            @endif

            @if($invoice->order)
            <a href="{{ route('reception.orders.show', $invoice->order) }}"
                class="block text-center bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                View Related Order
            </a>
            @endif
        </div>
    </div>
</div>
@endsection