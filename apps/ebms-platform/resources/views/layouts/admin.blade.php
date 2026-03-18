<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') — UASC Exams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,600;1,400&family=Figtree:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen font-sans">

    {{-- Top Navigation --}}
    <header class="bg-slate-900 text-white px-5 h-12 flex items-center justify-between sticky top-0 z-10">
        <div class="flex items-center gap-2.5">
            <div class="w-5 h-5 rounded bg-blue-500 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <span class="font-semibold text-sm tracking-tight">UASC Examinations</span>
            <span class="text-slate-500 text-xs mx-0.5">|</span>
            <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Admin Portal</span>
        </div>
        <div class="flex items-center gap-3 text-xs text-slate-300">
            <span class="font-medium text-white">{{ auth('admin')->user()?->name }}</span>
            <span class="bg-slate-700 text-slate-300 px-2 py-0.5 rounded font-medium uppercase tracking-wider text-[10px]">
                {{ auth('admin')->user()?->role?->value }}
            </span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-white hover:bg-slate-700 px-2.5 py-1 rounded transition-colors">
                    Sign out
                </button>
            </form>
        </div>
    </header>

    <div class="flex min-h-[calc(100vh-48px)]">

        <aside class="w-56 bg-white border-r border-slate-200 pt-5 pb-4 shrink-0 flex flex-col">
            <nav class="flex flex-col gap-0.5 px-3 flex-1">
                @php
                    $links = [
                        [
                            'route' => 'admin.dashboard',
                            'label' => 'Dashboard',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                        ],
                        [
                            'route' => 'admin.students.search',
                            'label' => 'Student Lookup',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                        ],
                        [
                            'route' => 'admin.enrollments.index',
                            'label' => 'Enrollments',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
                        ],
                        [
                            'route' => 'admin.exams.index',
                            'label' => 'Exams',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                        ],
                    ];

                    $preExamLinks = [
                        [
                            'route' => 'admin.dform.index',
                            'label' => 'D-Form',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>',
                        ],
                        [
                            'route' => 'admin.attendance.index',
                            'label' => 'Attendance',
                            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                        ],
                    ];
                @endphp
                @foreach($links as $link)
                    @php $active = request()->routeIs($link['route'].'*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                              {{ $active
                                  ? 'bg-blue-50 text-blue-800 font-semibold'
                                  : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 font-medium' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 shrink-0 {{ $active ? 'text-blue-600' : 'text-slate-400' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            {!! $link['icon'] !!}
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach

                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Pre Exam</p>
                @foreach($preExamLinks as $link)
                    @php $active = request()->routeIs($link['route'].'*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                              {{ $active
                                  ? 'bg-blue-50 text-blue-800 font-semibold'
                                  : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800 font-medium' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 shrink-0 {{ $active ? 'text-blue-600' : 'text-slate-400' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            {!! $link['icon'] !!}
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-4 pt-3 border-t border-slate-100 mt-4">
                <p class="text-xs text-slate-400">Examination Branch</p>
            </div>
        </aside>

        <main class="flex-1 p-7 overflow-auto ebms-page">
            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif
            @if($errors->any())
                <x-alert type="error">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </x-alert>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
