@extends('layouts.admin')
@section('title', 'Student Lookup')

@section('content')
<div class="max-w-xl">
    <h1 class="text-xl font-bold text-gray-800 mb-6">Student Lookup</h1>

    <form method="GET" action="" id="search-form">
        <div class="flex gap-3">
            <input type="text" name="ht" value="{{ request('ht') }}"
                   placeholder="Enter Hall Ticket Number"
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-500 focus:outline-none">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                Search
            </button>
        </div>
    </form>

    <script>
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const ht = this.querySelector('input[name="ht"]').value.trim();
            if (ht) window.location.href = '{{ route("admin.students.show", ":ht") }}'.replace(':ht', encodeURIComponent(ht));
        });
    </script>
</div>
@endsection
