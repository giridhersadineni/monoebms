@extends('layouts.admin')
@section('title', 'Results — ' . $enrollment->hall_ticket)

@section('content')
<div class="max-w-4xl">
    <h1 class="text-xl font-bold text-gray-800 mb-1">Results</h1>
    <p class="text-sm text-gray-500 mb-6">{{ $enrollment->student?->name }} — {{ $enrollment->exam?->name }}</p>

    @if($enrollment->gpa)
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $enrollment->gpa->sgpa }}</p>
            <p class="text-xs text-gray-400">SGPA</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $enrollment->gpa->cgpa }}</p>
            <p class="text-xs text-gray-400">CGPA</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $enrollment->gpa->total_marks }}</p>
            <p class="text-xs text-gray-400">Total Marks</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <x-status-badge :status="$enrollment->gpa->result" />
            <p class="text-xs text-gray-400 mt-1">Result</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-center">Ext</th>
                    <th class="px-4 py-3 text-center">Int</th>
                    <th class="px-4 py-3 text-center">Total</th>
                    <th class="px-4 py-3 text-center">Grade</th>
                    <th class="px-4 py-3 text-center">Credits</th>
                    <th class="px-4 py-3 text-center">Result</th>
                    @if(auth('admin')->user()?->canAccess('results.edit'))
                    <th class="px-4 py-3 text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($enrollment->results as $result)
                <tr id="view-{{ $result->id }}" class="{{ $result->result === 'F' ? 'bg-red-50' : '' }}">
                    <td class="px-4 py-2 font-mono text-xs">{{ $result->subject?->code }}</td>
                    <td class="px-4 py-2">{{ $result->subject?->name }}</td>
                    <td class="px-4 py-2 text-center">{{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}</td>
                    <td class="px-4 py-2 text-center">{{ $result->is_absent_int ? 'AB' : $result->int_marks }}</td>
                    <td class="px-4 py-2 text-center font-semibold">{{ $result->total_marks }}</td>
                    <td class="px-4 py-2 text-center font-bold">{{ $result->grade }}</td>
                    <td class="px-4 py-2 text-center">{{ $result->credits }}</td>
                    <td class="px-4 py-2 text-center"><x-status-badge :status="$result->result" /></td>
                    @if(auth('admin')->user()?->canAccess('results.edit'))
                    <td class="px-4 py-2 text-center">
                        <button type="button" data-edit="{{ $result->id }}" style="color:#2563eb;font-size:12px;text-decoration:underline;background:none;border:none;padding:0;cursor:pointer;">Edit</button>
                    </td>
                    @endif
                </tr>
                @if(auth('admin')->user()?->canAccess('results.edit'))
                <tr id="edit-{{ $result->id }}" style="display:none;background:#eff6ff;">
                    <td colspan="9" style="padding:12px 16px;">
                        <form method="POST" action="{{ route('admin.results.update-result', [$enrollment->id, $result->id]) }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                            @csrf
                            <span style="font-family:monospace;font-size:12px;color:#4b5563;">{{ $result->subject?->code }}</span>
                            <label style="font-size:12px;color:#4b5563;">Ext
                                <input name="ext" value="{{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}" autocomplete="off" style="width:72px;border:1px solid #d1d5db;border-radius:4px;padding:4px 8px;font-size:14px;margin-left:4px;">
                            </label>
                            <label style="font-size:12px;color:#4b5563;">Int
                                <input name="int" value="{{ $result->is_absent_int ? 'AB' : $result->int_marks }}" autocomplete="off" style="width:72px;border:1px solid #d1d5db;border-radius:4px;padding:4px 8px;font-size:14px;margin-left:4px;">
                            </label>
                            <button type="submit" style="background:#2563eb;color:#fff;font-size:12px;font-weight:600;border:none;border-radius:4px;padding:6px 14px;cursor:pointer;">Save</button>
                            <button type="button" data-cancel="{{ $result->id }}" style="color:#6b7280;font-size:12px;background:none;border:none;cursor:pointer;">Cancel</button>
                            <span style="font-size:12px;color:#9ca3af;">Enter marks or "AB" for absent · Ext ≤ 100, Int ≤ 20</span>
                        </form>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @if(auth('admin')->user()?->canAccess('results.edit'))
    <p style="font-size:12px;color:#9ca3af;margin-top:12px;">Editing marks recomputes the grade for that paper. Re-process GPA for this exam to refresh SGPA/CGPA and division.</p>
    <script nonce="{{ $csp_nonce ?? '' }}">
    (function () {
        document.querySelectorAll('[data-edit]').forEach(function (b) {
            b.addEventListener('click', function () {
                var id = b.getAttribute('data-edit');
                document.getElementById('view-' + id).style.display = 'none';
                document.getElementById('edit-' + id).style.display = 'table-row';
            });
        });
        document.querySelectorAll('[data-cancel]').forEach(function (b) {
            b.addEventListener('click', function () {
                var id = b.getAttribute('data-cancel');
                document.getElementById('edit-' + id).style.display = 'none';
                document.getElementById('view-' + id).style.display = 'table-row';
            });
        });
    })();
    </script>
    @endif
</div>
@endsection
