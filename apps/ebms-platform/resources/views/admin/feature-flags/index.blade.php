@extends('layouts.admin')
@section('title', 'Feature Flags')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Feature Flags</h1>
        <p class="text-sm text-slate-500 mt-0.5">Enable or disable student-facing features globally.</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3 text-left">Feature</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Maintenance Message</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flags as $flag)
                <tr class="border-b border-slate-50 last:border-0">
                    <td class="px-5 py-3.5 font-medium text-slate-700">{{ $flag->label }}</td>
                    <td class="px-5 py-3.5">
                        <x-status-badge :status="$flag->enabled ? 'open' : 'closed'" />
                    </td>
                    <td class="px-5 py-3.5 text-slate-500 text-xs max-w-xs">
                        <span id="msg-text-{{ $flag->id }}">{{ $flag->message ? Str::limit($flag->message, 60) : '—' }}</span>
                        <form id="msg-form-{{ $flag->id }}" method="POST"
                              action="{{ route('admin.feature-flags.message', $flag) }}"
                              class="hidden mt-1 flex gap-1.5 items-center">
                            @csrf @method('PATCH')
                            <input type="text" name="message" value="{{ $flag->message }}"
                                   maxlength="500" placeholder="Maintenance message…"
                                   class="flex-1 border border-slate-300 rounded px-2 py-1 text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-400">
                            <button type="submit"
                                    class="px-2.5 py-1 bg-slate-900 text-white rounded text-xs font-medium hover:bg-slate-700">
                                Save
                            </button>
                            <button type="button" onclick="toggleMsgForm({{ $flag->id }})"
                                    class="px-2 py-1 text-slate-400 hover:text-slate-600 text-xs">
                                Cancel
                            </button>
                        </form>
                        <button onclick="toggleMsgForm({{ $flag->id }})"
                                id="msg-btn-{{ $flag->id }}"
                                class="text-blue-500 hover:underline text-xs mt-0.5 block">
                            Edit message
                        </button>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <form method="POST" action="{{ route('admin.feature-flags.toggle', $flag) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition-colors
                                           {{ $flag->enabled
                                               ? 'border-red-200 text-red-700 hover:bg-red-50'
                                               : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                {{ $flag->enabled ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
function toggleMsgForm(id) {
    document.getElementById('msg-form-' + id).classList.toggle('hidden');
    document.getElementById('msg-text-' + id).classList.toggle('hidden');
    document.getElementById('msg-btn-' + id).classList.toggle('hidden');
}
</script>
@endpush
@endsection
