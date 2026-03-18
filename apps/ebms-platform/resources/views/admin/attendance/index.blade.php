@extends('layouts.admin')
@section('title', 'Attendance Sheet')

@section('content')
<div class="max-w-lg">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Attendance Sheet</h1>
        <p class="text-sm text-slate-500 mt-0.5">Select a subject and exam to generate the attendance sheet.</p>
    </div>

    <form action="{{ route('admin.attendance.print') }}" method="GET" target="_blank"
          class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Subject (Paper Code)</label>
            <select name="subject_code" required
                    class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm bg-white
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-800">
                <option value="">— Select subject —</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->code }}">{{ $subject->code }} — {{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Exam</label>
            <select name="exam_id" required
                    class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm bg-white
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-800">
                <option value="">— Select exam —</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->id }} — {{ $exam->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg
                       text-sm font-medium transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Generate Attendance Sheet
        </button>
    </form>
</div>
@endsection
