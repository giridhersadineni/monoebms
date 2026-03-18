@props(['type' => 'success'])
@php
    [$bg, $border, $text, $icon] = match($type) {
        'success' => ['bg-emerald-50', 'border-emerald-400', 'text-emerald-800', '✓'],
        'error'   => ['bg-red-50',     'border-red-400',     'text-red-800',     '✕'],
        'info'    => ['bg-blue-50',    'border-blue-400',    'text-blue-800',    'ℹ'],
        'warning' => ['bg-amber-50',   'border-amber-400',   'text-amber-800',   '⚠'],
        default   => ['bg-stone-50',   'border-stone-400',   'text-stone-800',   '·'],
    };
@endphp
<div class="border-l-4 {{ $bg }} {{ $border }} {{ $text }} px-4 py-3 mb-4 rounded-r-lg text-sm flex gap-3 items-start" role="alert">
    <span class="font-bold text-base shrink-0 leading-tight">{{ $icon }}</span>
    <div>{{ $slot }}</div>
</div>
