<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal') — UASC Exams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Nunito:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --navy:   #162B3E;
            --navy2:  #1E3A52;
            --amber:  #D4912E;
            --amber2: #B87B22;
            --teal:   #0D9488;
            --cream:  #FAFAF8;
            --card:   #FFFFFF;
            --border: #E8E6E0;
            --text:   #1C2B3A;
            --muted:  #6B7A8D;
        }
        body { font-family: 'Nunito', sans-serif; background: var(--cream); color: var(--text); }
        .font-display { font-family: 'Fraunces', serif; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }

        /* Sidebar nav active state */
        .nav-link { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; font-size:14px; font-weight:600; color:var(--muted); transition:all .15s; text-decoration:none; }
        .nav-link:hover { background:rgba(22,43,62,.06); color:var(--navy); }
        .nav-link.active { background:var(--navy); color:#fff; }
        .nav-link.active svg { color:#fff; }
        .nav-link svg { width:18px; height:18px; flex-shrink:0; }

        /* Mobile bottom nav */
        .mobile-nav-link { display:flex; flex-direction:column; align-items:center; gap:3px; padding:8px 4px; flex:1; color:var(--muted); font-size:10px; font-weight:600; text-decoration:none; letter-spacing:.4px; }
        .mobile-nav-link svg { width:22px; height:22px; }
        .mobile-nav-link.active { color:var(--navy); }
        .mobile-nav-link.active svg { stroke:var(--navy); stroke-width:2.5; }

        /* Cards */
        .card { background:var(--card); border:1px solid var(--border); border-radius:14px; box-shadow:0 1px 4px rgba(22,43,62,.06); }

        /* Badges */
        .badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:99px; font-size:11px; font-weight:700; letter-spacing:.3px; }
        .badge-paid { background:#D1FAE5; color:#065F46; }
        .badge-unpaid { background:#FEF3C7; color:#92400E; }
        .badge-pass { background:#D1FAE5; color:#065F46; }
        .badge-fail { background:#FEE2E2; color:#991B1B; }
        .badge-ab { background:#F3F4F6; color:#374151; }
        .badge-open { background:#D1FAE5; color:#065F46; }

        /* Buttons */
        .btn-primary { background:var(--navy); color:#fff; padding:11px 24px; border-radius:10px; font-size:14px; font-weight:700; border:none; cursor:pointer; transition:background .15s; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
        .btn-primary:hover { background:var(--navy2); }
        .btn-amber { background:var(--amber); color:#fff; padding:11px 24px; border-radius:10px; font-size:14px; font-weight:700; border:none; cursor:pointer; transition:background .15s; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
        .btn-amber:hover { background:var(--amber2); }
        .btn-ghost { background:transparent; color:var(--navy); padding:11px 20px; border-radius:10px; font-size:14px; font-weight:600; border:1.5px solid var(--border); cursor:pointer; transition:all .15s; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
        .btn-ghost:hover { background:rgba(22,43,62,.05); }
        .btn-sm { padding:7px 16px; font-size:12px; border-radius:8px; }

        /* Form inputs */
        .form-input { width:100%; border:1.5px solid var(--border); border-radius:10px; padding:12px 14px; font-size:14px; font-family:'Nunito',sans-serif; background:#fff; color:var(--text); outline:none; transition:border-color .15s; }
        .form-input:focus { border-color:var(--navy); box-shadow:0 0 0 3px rgba(22,43,62,.1); }
        .form-label { display:block; font-size:13px; font-weight:700; color:var(--navy); margin-bottom:6px; letter-spacing:.2px; }

        /* Alert */
        .alert-success { background:#ECFDF5; border:1px solid #A7F3D0; border-radius:10px; padding:12px 16px; color:#065F46; font-size:14px; margin-bottom:20px; }
        .alert-error   { background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:12px 16px; color:#991B1B; font-size:14px; margin-bottom:20px; }
        .alert-info    { background:#EFF6FF; border:1px solid #BFDBFE; border-radius:10px; padding:12px 16px; color:#1E40AF; font-size:14px; margin-bottom:20px; }

        /* Table → card stack on mobile */
        @media (max-width: 640px) {
            .table-card thead { display:none; }
            .table-card tbody tr { display:block; border:1px solid var(--border); border-radius:12px; margin-bottom:10px; padding:14px; background:#fff; }
            .table-card tbody td { display:flex; justify-content:space-between; align-items:center; padding:4px 0; font-size:13px; border:none; }
            .table-card tbody td::before { content:attr(data-label); font-weight:700; color:var(--muted); font-size:11px; letter-spacing:.3px; text-transform:uppercase; }
            .table-card tbody td:first-child { font-size:14px; font-weight:700; color:var(--text); }
            .table-card tbody td:first-child::before { display:none; }
        }

        /* Stagger animation */
        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .animate-in { animation: fadeUp .35s ease both; }
        .delay-1 { animation-delay:.05s; }
        .delay-2 { animation-delay:.10s; }
        .delay-3 { animation-delay:.15s; }
        .delay-4 { animation-delay:.20s; }
        .delay-5 { animation-delay:.25s; }

        /* Scrollbar */
        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:var(--border); border-radius:99px; }
    </style>
</head>
<body>

    {{-- ── TOP HEADER ─────────────────────────────────── --}}
    <header style="background:var(--navy); height:56px;" class="flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
        <div class="flex items-center gap-2.5">
            <div style="width:28px;height:28px;background:var(--amber);border-radius:8px;" class="flex items-center justify-center shrink-0">
                <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div>
                <p class="font-display text-white font-semibold text-sm leading-none">UASC Examinations</p>
                <p style="color:rgba(255,255,255,.45); font-size:10px; letter-spacing:.6px;" class="font-semibold uppercase mt-0.5">Student Portal</p>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="hidden sm:block text-right">
                <p class="text-white text-xs font-semibold leading-none">{{ auth('student')->user()?->name }}</p>
                <p style="font-family:'JetBrains Mono',monospace; color:rgba(255,255,255,.5); font-size:10px;" class="mt-0.5">{{ auth('student')->user()?->hall_ticket }}</p>
            </div>
            <form method="POST" action="{{ route('student.logout') }}">
                @csrf
                <button type="submit" style="color:rgba(255,255,255,.6); font-size:12px; font-weight:600; padding:6px 12px; border-radius:8px; border:1px solid rgba(255,255,255,.15); background:transparent; cursor:pointer; transition:all .15s;"
                        onmouseover="this.style.background='rgba(255,255,255,.1)'" onmouseout="this.style.background='transparent'">
                    Sign out
                </button>
            </form>
        </div>
    </header>

    <div class="flex" style="min-height:calc(100vh - 56px);">

        {{-- ── DESKTOP SIDEBAR ────────────────────────── --}}
        <aside class="hidden md:flex flex-col shrink-0" style="width:220px; background:#fff; border-right:1px solid var(--border); padding:20px 12px;">
            @php
                $navLinks = [
                    ['route'=>'student.dashboard',         'label'=>'Dashboard',      'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route'=>'student.enrollments.index', 'label'=>'My Enrollments', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['route'=>'student.results.index',     'label'=>'Results',        'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['route'=>'student.revaluation.index', 'label'=>'Revaluation',   'icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                ];
            @endphp
            <nav class="flex flex-col gap-1 flex-1">
                @foreach($navLinks as $link)
                    @php $active = request()->routeIs($link['route'].'*'); @endphp
                    <a href="{{ route($link['route']) }}" class="nav-link {{ $active ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                        </svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
            <div style="border-top:1px solid var(--border); padding-top:12px; margin-top:16px;">
                <p style="font-size:11px; color:var(--muted); font-weight:600;">Examination Branch</p>
            </div>
        </aside>

        {{-- ── MAIN CONTENT ────────────────────────────── --}}
        <main class="flex-1 overflow-auto" style="padding:24px 16px 100px; max-width:100%;">
            <div style="max-width:900px; margin:0 auto;">

                @if(session('success'))
                    <div class="alert-success animate-in">{{ session('success') }}</div>
                @endif
                @if(session('info'))
                    <div class="alert-info animate-in">{{ session('info') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert-error animate-in">
                        <ul style="margin:0; padding-left:18px;">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </div>
        </main>

    </div>

    {{-- ── MOBILE BOTTOM NAV ───────────────────────────── --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-30 flex"
         style="background:#fff; border-top:1px solid var(--border); box-shadow:0 -2px 12px rgba(22,43,62,.08); padding-bottom:env(safe-area-inset-bottom,0px);">
        @php
            $mobileLinks = [
                ['route'=>'student.dashboard',         'label'=>'Home',        'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route'=>'student.enrollments.index', 'label'=>'Enrollments', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['route'=>'student.results.index',     'label'=>'Results',     'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route'=>'student.revaluation.index', 'label'=>'Revaluation', 'icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
            ];
        @endphp
        @foreach($mobileLinks as $link)
            @php $active = request()->routeIs($link['route'].'*'); @endphp
            <a href="{{ route($link['route']) }}" class="mobile-nav-link {{ $active ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                </svg>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

</body>
</html>
