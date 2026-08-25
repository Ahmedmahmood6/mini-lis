@extends('layouts.app')

@section('title', 'Patient Profile')
@section('page-title', 'Patient Profile')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('reception.patients.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-700 mb-2">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Patients
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Patient Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 text-center">
                <div
                    class="w-16 h-16 rounded-full bg-blue-600 text-white font-bold text-xl flex items-center justify-center mx-auto shadow-sm">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>
                <h2 class="mt-4 text-lg font-bold text-slate-900">{{ $patient->name }}</h2>
                <p class="text-xs text-slate-400">Patient #{{ $patient->id }}</p>
                <div class="mt-2 flex items-center justify-center">
                    <x-badge :variant="$patient->gender === 'female' ? 'info' : 'primary'" size="sm">
                        {{ ucfirst($patient->gender) }} &middot; {{ $patient->age }} yrs
                    </x-badge>
                </div>

                <div class="mt-6 space-y-4 text-left">
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Phone</p>
                            <p class="text-sm text-slate-700 font-medium">{{ $patient->phone }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">National ID</p>
                            <p class="text-sm text-slate-700 font-medium">{{ $patient->national_id ?? 'Not provided' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Address</p>
                            <p class="text-sm text-slate-700 font-medium">{{ $patient->address ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                        </svg>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Registered</p>
                            <p class="text-sm text-slate-700 font-medium">{{ $patient->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col gap-2.5">
                    <x-button href="{{ route('reception.patients.edit', $patient) }}" variant="primary" size="sm"
                        class="w-full">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                        </svg>
                        Edit Patient
                    </x-button>
                    <x-button href="{{ route('reception.orders.create', ['patient_id' => $patient->id]) }}"
                        variant="secondary" size="sm" class="w-full">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create Order
                    </x-button>
                </div>
            </div>
        </div>

        <!-- History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment History -->
            <x-card title="Appointment History" subtitle="All past and upcoming appointments" no-padding>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead
                            class="bg-slate-50/80 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Date</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Time</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Test</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Status</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($patient->appointments as $appointment)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800">
                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $appointment->appointment_date->format('h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $appointment->test?->name ?? 'Not specified' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge :status="$appointment->status" size="sm" />
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">
                                    {{ $appointment->notes ?? '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-9 h-9 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                                        </svg>
                                        <span class="text-sm font-medium text-slate-500">No appointment history
                                            yet.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Previous Orders -->
            <x-card title="Previous Orders" subtitle="Laboratory orders placed for this patient" no-padding>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead
                            class="bg-slate-50/80 border-b border-slate-200/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Order #</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Date</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Status</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Total</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Payment</th>
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($patient->orders as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge :status="$order->status" size="sm" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $order->invoice ? number_format($order->invoice->net_amount, 2).' EGP' : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($order->invoice)
                                    <x-badge :status="$order->invoice->payment_status" size="sm" />
                                    @else
                                    <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('reception.orders.show', $order) }}"
                                        class="text-xs font-medium text-blue-600 hover:text-blue-700">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-9 h-9 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        <span class="text-sm font-medium text-slate-500">No previous orders
                                            found.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection