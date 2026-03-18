@props(['status'])
@php
    [$bg, $text, $ring] = match($status) {
        'paid'              => ['bg-emerald-50', 'text-emerald-800', 'ring-emerald-200'],
        'challan_submitted' => ['bg-amber-50',   'text-amber-800',   'ring-amber-200'],
        'pending'           => ['bg-stone-100',  'text-stone-600',   'ring-stone-200'],
        'open'              => ['bg-blue-50',    'text-blue-800',    'ring-blue-200'],
        'closed'            => ['bg-red-50',     'text-red-700',     'ring-red-200'],
        'P'                 => ['bg-emerald-50', 'text-emerald-800', 'ring-emerald-200'],
        'F'                 => ['bg-red-50',     'text-red-700',     'ring-red-200'],
        'AB'                => ['bg-orange-50',  'text-orange-700',  'ring-orange-200'],
        default             => ['bg-stone-100',  'text-stone-600',   'ring-stone-200'],
    };
    $label = match($status) {
        'paid'              => 'Fee Paid',
        'challan_submitted' => 'Challan Submitted',
        'pending'           => 'Pending',
        'P'                 => 'Pass',
        'F'                 => 'Fail',
        'AB'                => 'Absent',
        default             => strtoupper($status),
    };
@endphp
<span class="inline-flex items-center {{ $bg }} {{ $text }} text-xs font-medium px-2 py-0.5 rounded-md ring-1 {{ $ring }}">
    {{ $label }}
</span>
