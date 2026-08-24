@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'footer' => null,
    'header' => null,
    'padding' => 'p-6',
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md hover:border-slate-300/80']) }}>
    @if ($header || $title)
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/40">
            @if ($header)
                {{ $header }}
            @else
                <div>
                    <h3 class="text-base font-semibold text-slate-800 leading-snug">{{ $title }}</h3>
                    @if ($subtitle)
                        <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
                @if ($actions)
                    <div class="flex items-center gap-2">
                        {{ $actions }}
                    </div>
                @endif
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? '' : $padding }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-3.5 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between text-sm text-slate-600">
            {{ $footer }}
        </div>
    @endif
</div>
