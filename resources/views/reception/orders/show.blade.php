@extends('layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reception.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Orders
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-3">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Created {{ $order->created_at->format('d M Y, h:i A') }} by {{ $order->creator?->name ?? '—' }}</p>
                </div>

                @php
                    $statusStyles = [
                        'pending' => 'bg-amber-50 text-amber-700',
                        'in_progress' => 'bg-blue-50 text-blue-700',
                        'completed' => 'bg-emerald-50 text-emerald-700',
                        'cancelled' => 'bg-red-50 text-red-700',
                    ];
                @endphp
                <span class="inline-flex items-center gap-1.5 {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600' }} text-xs font-bold uppercase tracking-wide px-3 py-1.5 rounded-full w-fit">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Patient + Tests + Results -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Patient Info -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                    <h2 class="text-sm font-bold text-slate-800 mb-4">Patient Information</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Name</p>
                            <p class="font-semibold text-slate-800">{{ $order->patient->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Phone</p>
                            <p class="font-semibold text-slate-800">{{ $order->patient->phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Gender</p>
                            <p class="font-semibold text-slate-800">{{ ucfirst($order->patient->gender ?? '—') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Age</p>
                            <p class="font-semibold text-slate-800">{{ $order->patient->age ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tests & Results -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h2 class="text-sm font-bold text-slate-800">Tests & Results</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    <th class="px-6 py-3">Test</th>
                                    <th class="px-6 py-3">Result</th>
                                    <th class="px-6 py-3">Reference Range</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="px-6 py-3.5">
                                            <p class="font-medium text-slate-800">{{ $item->test->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $item->test->code }}</p>
                                        </td>
                                        <td class="px-6 py-3.5 text-slate-700">
                                            {{ $item->result?->result_text ?? '—' }}
                                            @if($item->result?->notes)
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $item->result->notes }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3.5 text-slate-500">{{ $item->result?->reference_range ?? $item->test->reference_range ?? '—' }}</td>
                                        <td class="px-6 py-3.5">
                                            @if(($item->result?->status ?? 'pending') === 'entered')
                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">Entered</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($order->status === 'completed')
                    <!-- Report Actions -->
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-sm font-bold text-slate-800 mb-4">Report Actions</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('reports.pdf', $order) }}" target="_blank"
                                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                View PDF Report
                            </a>
                            <a href="{{ route('reports.download', $order) }}"
                                class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Download PDF
                            </a>
                            <a href="{{ route('reports.whatsapp', $order) }}" target="_blank"
                                class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6.994 3.031 2.484 3.599a14.9 14.9 0 0 0 5.176.828 14.9 14.9 0 0 0 5.29-1.075c1.19-.542 2.062-1.652 2.301-2.965.276-1.515.276-3.076 0-4.591-.24-1.313-1.112-2.423-2.301-2.965A14.9 14.9 0 0 0 9.91 4.5a14.9 14.9 0 0 0-5.176.828C3.244 5.896 2.25 7.327 2.25 8.925v3.585Z" />
                                </svg>
                                Send via WhatsApp
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Invoice -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                    <h2 class="text-sm font-bold text-slate-800 mb-4">Invoice {{ $order->invoice?->invoice_number }}</h2>

                    @if($order->invoice)
                        @php
                            $paymentStyles = [
                                'paid' => 'bg-emerald-50 text-emerald-700',
                                'partially_paid' => 'bg-amber-50 text-amber-700',
                                'unpaid' => 'bg-red-50 text-red-700',
                            ];
                            $remaining = max(0, $order->invoice->net_amount - $order->invoice->paid_amount);
                        @endphp

                        <span class="inline-block {{ $paymentStyles[$order->invoice->payment_status] ?? 'bg-slate-100 text-slate-600' }} text-xs font-bold uppercase tracking-wide px-3 py-1 rounded-full mb-4">
                            {{ str_replace('_', ' ', $order->invoice->payment_status) }}
                        </span>

                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total</span>
                                <span class="font-medium text-slate-800">{{ number_format($order->invoice->total_amount, 2) }} EGP</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Discount</span>
                                <span class="font-medium text-slate-800">- {{ number_format($order->invoice->discount, 2) }} EGP</span>
                            </div>
                            <div class="flex justify-between pt-2.5 border-t border-slate-100">
                                <span class="text-slate-600 font-semibold">Net Amount</span>
                                <span class="font-bold text-slate-900">{{ number_format($order->invoice->net_amount, 2) }} EGP</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Paid</span>
                                <span class="font-medium text-emerald-600">{{ number_format($order->invoice->paid_amount, 2) }} EGP</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Remaining</span>
                                <span class="font-semibold text-red-600">{{ number_format($remaining, 2) }} EGP</span>
                            </div>
                        </div>

                        @if($order->invoice->payment_status !== 'paid')
                            <form method="POST" action="{{ route('reception.invoices.pay', $order->invoice) }}" class="mt-5">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-colors">
                                    Mark as Paid
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="text-sm text-slate-400">No invoice found for this order.</p>
                    @endif
                </div>

                @if(in_array($order->status, ['pending', 'in_progress']))
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                        <h2 class="text-sm font-bold text-slate-800 mb-4">Order Status</h2>
                        <form method="POST" action="{{ route('reception.orders.update-status', $order) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            @if($order->status === 'pending')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                                    Start Processing
                                </button>
                            @elseif($order->status === 'in_progress')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                                    Mark as Completed
                                </button>
                            @endif
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection