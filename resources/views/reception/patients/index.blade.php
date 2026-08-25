@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Patients</h2>
            <p class="text-sm text-slate-500 mt-0.5">Search, view, and manage registered patient records.</p>
        </div>

        <x-button href="{{ route('reception.patients.create') }}" variant="primary" size="md">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
            </svg>
            New Patient
        </x-button>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5">
        <form method="GET" action="{{ route('reception.patients.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ old('search', request('search')) }}"
                    placeholder="Search by name, phone, or national ID..."
                    class="w-full pl-10 pr-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border border-slate-300 transition-colors duration-150 outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500/20 placeholder-slate-400">
            </div>
            <div class="flex gap-2">
                <x-button type="submit" variant="primary" size="md">
                    Search
                </x-button>
                @if (request('search'))
                <x-button href="{{ route('reception.patients.index') }}" variant="outline" size="md">
                    Clear
                </x-button>
                @endif
            </div>
        </form>
    </div>

    <!-- Patients Table -->
    <x-table :headers="['Patient', 'Phone', 'Gender', 'Age', 'National ID', 'Address', 'Actions']"
        :empty="$patients->isEmpty()"
        :empty-message="request('search') ? 'No patients match your search.' : 'No patients found. Register your first patient to get started.'">
        @foreach ($patients as $patient)
        <tr class="hover:bg-slate-50/70 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-slate-800 block">{{ $patient->name }}</span>
                        <span class="text-[11px] text-slate-400">Patient #{{ $patient->id }}</span>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $patient->phone }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <x-badge :variant="$patient->gender === 'female' ? 'info' : 'primary'" size="sm">
                    {{ ucfirst($patient->gender) }}
                </x-badge>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $patient->age }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $patient->national_id ?? '—' }}</td>
            <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ $patient->address ?? '—' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('reception.patients.show', $patient) }}" title="View patient"
                        class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </a>
                    <a href="{{ route('reception.patients.edit', $patient) }}" title="Edit patient"
                        class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    @if (auth()->user()->isAdmin())
                    <button type="button" title="Delete patient"
                        onclick="window.openModal('delete-patient-{{ $patient->id }}')"
                        class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>

                    <x-modal id="delete-patient-{{ $patient->id }}" title="Delete Patient"
                        subtitle="This action cannot be undone.">
                        <p>
                            Are you sure you want to permanently delete
                            <span class="font-semibold text-slate-900">{{ $patient->name }}</span>'s record?
                            All associated history will remain linked but this patient profile will be removed.
                        </p>

                        <x-slot:footer>
                            <x-button type="button" variant="secondary"
                                onclick="window.closeModal('delete-patient-{{ $patient->id }}')">
                                Cancel
                            </x-button>
                            <form method="POST" action="{{ route('reception.patients.destroy', $patient) }}">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger">
                                    Delete Patient
                                </x-button>
                            </form>
                        </x-slot:footer>
                    </x-modal>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach

        <x-slot:pagination>
            {{ $patients->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection