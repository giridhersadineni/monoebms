<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — UASC Exams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,600;1,400&family=Figtree:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen flex font-sans">

    {{-- Left branded panel --}}
    <div class="hidden lg:flex lg:w-2/5 xl:w-1/3 bg-slate-900 flex-col justify-between p-10 relative overflow-hidden">
        {{-- Subtle grid pattern --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image: linear-gradient(rgba(255,255,255,0.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.15) 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="relative z-10">
            {{-- University logo --}}
            <img src="https://uasckuexams.in/wp-content/uploads/2021/11/cropped-cropped-cropped-uascku-header-png-1-1.png"
                 alt="UASC KU"
                 class="h-16 object-contain object-left mb-8 opacity-90 brightness-0 invert">

            <div class="flex items-center gap-2 mb-4">
                <div class="w-4 h-4 rounded bg-blue-500 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-widest">Admin Portal</span>
            </div>
            <h2 class="font-serif text-white text-3xl leading-snug font-semibold">
                Examination<br>Management System
            </h2>
            <p class="text-slate-400 text-sm mt-4 leading-relaxed max-w-xs">
                Administrative access for enrollment processing, result entry, and examination management.
            </p>
        </div>

        <div class="relative z-10">
            <div class="border-t border-slate-700 pt-5">
                <p class="text-slate-500 text-xs">University of Arts, Science &amp; Commerce</p>
                <p class="text-slate-500 text-xs mt-0.5">Internal Administrative System</p>
            </div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-sm">

            {{-- Mobile header --}}
            <div class="lg:hidden text-center mb-8">
                <img src="https://uasckuexams.in/wp-content/uploads/2021/11/cropped-cropped-cropped-uascku-header-png-1-1.png"
                     alt="UASC KU"
                     class="h-14 object-contain mx-auto mb-4">
                <p class="text-sm text-slate-500">Admin Portal</p>
            </div>

            <div class="mb-7">
                <h2 class="text-xl font-semibold text-slate-800">Admin Sign in</h2>
                <p class="text-sm text-slate-500 mt-1">Enter your credentials to access the portal</p>
            </div>

            @if($errors->any())
                <div class="border-l-4 bg-red-50 border-red-400 text-red-800 px-4 py-3 mb-5 rounded-r-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" novalidate class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Login ID</label>
                    <input type="text" name="login_id" value="{{ old('login_id') }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm bg-white
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                  @error('login_id') border-red-400 focus:ring-red-400 @enderror"
                           autocomplete="username" required>
                    <x-form-error field="login_id" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm bg-white
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                           autocomplete="current-password" required>
                </div>

                <button type="submit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white py-2.5 rounded-lg text-sm font-semibold transition-colors mt-1">
                    Sign In
                </button>
            </form>

            <p class="text-center text-xs text-slate-400 mt-6">
                Student?
                <a href="{{ route('student.login') }}" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">
                    Student login &rarr;
                </a>
            </p>
        </div>
    </div>
</body>
</html>
