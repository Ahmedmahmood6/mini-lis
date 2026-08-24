@props([
    'status' => null,
    'role' => null,
    'variant' => null,
    'size' => 'md',
    'dot' => false,
])

@php
    $key = strtolower((string) ($status ?? $role ?? $variant ?? 'default'));

    $styles = [
        // Statuses
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20',
        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20',
        'in_progress' => 'bg-purple-50 text-purple-700 border-purple-200 ring-purple-500/20',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-500/20',
        'cancelled' => 'bg-red-50 text-red-700 border-red-200 ring-red-500/20',
        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-500/20',
        'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200 ring-rose-500/20',
        'partial' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20',

        // Roles
        'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200 ring-indigo-500/20',
        'receptionist' => 'bg-sky-50 text-sky-700 border-sky-200 ring-sky-500/20',
        'technician' => 'bg-teal-50 text-teal-700 border-teal-200 ring-teal-500/20',

        // Generic Variants
        'primary' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-500/20',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20',
        'danger' => 'bg-red-50 text-red-700 border-red-200 ring-red-500/20',
        'info' => 'bg-cyan-50 text-cyan-700 border-cyan-200 ring-cyan-500/20',
        'slate' => 'bg-slate-100 text-slate-700 border-slate-200 ring-slate-500/20',
        'default' => 'bg-slate-100 text-slate-700 border-slate-200 ring-slate-500/20',
    ];

    $dotColors = [
        'pending' => 'bg-amber-500',
        'confirmed' => 'bg-blue-500',
        'in_progress' => 'bg-purple-500',
        'completed' => 'bg-emerald-500',
        'cancelled' => 'bg-red-500',
        'paid' => 'bg-emerald-500',
        'unpaid' => 'bg-rose-500',
        'partial' => 'bg-amber-500',
        'admin' => 'bg-indigo-500',
        'receptionist' => 'bg-sky-500',
        'technician' => 'bg-teal-500',
        'default' => 'bg-slate-400',
    ];

    $sizeClasses = [
        'sm' => 'text-[11px] px-2 py-0.5 rounded-md gap-1 font-medium',
        'md' => 'text-xs px-2.5 py-1 rounded-lg gap-1.5 font-semibold',
        'lg' => 'text-sm px-3 py-1.5 rounded-xl gap-2 font-semibold',
    ];

    $styleClass = $styles[$key] ?? $styles['default'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $dotColor = $dotColors[$key] ?? $dotColors['default'];

    $defaultLabel = str_replace('_', ' ', Str::title($key));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center border shadow-xs transition-colors {$styleClass} {$sizeClass}"]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
    @endif
    {{ $slot->isNotEmpty() ? $slot : $defaultLabel }}
</span>
