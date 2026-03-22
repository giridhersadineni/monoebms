<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login — UASC Exams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Nunito:wght@400;500;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        :root { --navy:#162B3E; --navy2:#1E3A52; --amber:#D4912E; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:'Nunito',sans-serif; background:#FAFAF8; min-height:100vh; display:flex; }
        .font-display { font-family:'Fraunces',serif; }

        /* Panel */
        .brand-panel {
            width:42%; background:var(--navy);
            display:flex; flex-direction:column; justify-content:space-between;
            padding:48px 44px; position:relative; overflow:hidden;
        }
        @media(max-width:768px){ .brand-panel{display:none;} }

        /* Geometric accent */
        .brand-panel::before {
            content:''; position:absolute; right:-80px; top:-80px;
            width:320px; height:320px; border-radius:50%;
            border:60px solid rgba(212,145,46,.15);
        }
        .brand-panel::after {
            content:''; position:absolute; left:-60px; bottom:-60px;
            width:240px; height:240px; border-radius:50%;
            border:40px solid rgba(255,255,255,.06);
        }

        .form-panel { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:32px 20px; }
        .form-panel-logo { text-align:center; margin-bottom:28px; width:100%; }
        .form-panel-logo img { height:72px; object-fit:contain; display:inline-block; }
        .form-panel-logo p { font-size:12px; color:#8A9AB0; font-weight:600; letter-spacing:.5px; text-transform:uppercase; margin:8px 0 0; }

        .form-input {
            width:100%; border:1.5px solid #E2DDD6; border-radius:10px;
            padding:13px 16px; font-size:15px; font-family:'Nunito',sans-serif;
            background:#fff; color:#1C2B3A; outline:none; transition:border-color .15s;
        }
        .form-input:focus { border-color:var(--navy); box-shadow:0 0 0 3px rgba(22,43,62,.1); }
        .form-input.mono { font-family:'JetBrains Mono',monospace; letter-spacing:.06em; }

        .btn-login {
            width:100%; background:var(--navy); color:#fff;
            padding:14px; border-radius:10px; font-size:15px; font-weight:700;
            border:none; cursor:pointer; font-family:'Nunito',sans-serif;
            transition:background .15s; letter-spacing:.2px;
        }
        .btn-login:hover { background:var(--navy2); }

        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .form-card { animation:fadeUp .4s ease both; }
    </style>
</head>
<body>

    {{-- Brand panel --}}
    <div class="brand-panel">
        <div style="position:relative;z-index:1;">
            <span style="display:inline-flex;align-items:center;gap:7px;background:rgba(212,145,46,.2);color:var(--amber);padding:5px 12px;border-radius:99px;font-size:11px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;margin-bottom:16px;">
                <span style="width:6px;height:6px;background:var(--amber);border-radius:50%;"></span>
                Student Portal
            </span>

            <h1 class="font-display" style="color:#fff;font-size:34px;line-height:1.2;font-weight:600;margin:0 0 16px;">
                Examination<br>Management<br><em style="color:var(--amber);font-style:italic;">System</em>
            </h1>
            <p style="color:rgba(255,255,255,.5);font-size:14px;line-height:1.7;max-width:280px;">
                View enrollment records, exam results, and apply for revaluation.
            </p>
        </div>

        <div style="position:relative;z-index:1;border-top:1px solid rgba(255,255,255,.1);padding-top:20px;">
            <p style="color:rgba(255,255,255,.35);font-size:12px;">University Arts, Science &amp; Commerce · Examination Branch · {{ date('Y') }}</p>
        </div>
    </div>

    {{-- Form panel --}}
    <div class="form-panel">

        {{-- University logo — centred across full right pane --}}
        <div class="form-panel-logo">
            <img src="https://uasckuexams.in/wp-content/uploads/2021/11/cropped-cropped-cropped-uascku-header-png-1-1.png"
                 alt="UASC KU">
            <p>Student Portal</p>
        </div>

        <div class="form-card" style="width:100%;max-width:380px;">

            <div style="margin-bottom:28px;">
                <h2 class="font-display" style="font-size:26px;font-weight:600;color:#162B3E;margin:0 0 6px;">Sign in</h2>
                <p style="font-size:14px;color:#8A9AB0;margin:0;">Enter your hall ticket number to continue</p>
            </div>

            @if($errors->any())
                <div style="background:#FEF2F2;border:1px solid #FECACA;border-left:4px solid #EF4444;border-radius:8px;padding:12px 16px;color:#991B1B;font-size:14px;margin-bottom:20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('student.login.submit') }}" novalidate>
                @csrf
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:#162B3E;margin-bottom:6px;letter-spacing:.2px;">Hall Ticket Number</label>
                    <input type="text" name="hall_ticket" value="{{ old('hall_ticket') }}"
                           class="form-input mono" placeholder="e.g. 1234567890"
                           autocomplete="username" required>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:#162B3E;margin-bottom:6px;">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" class="form-input">
                    <p style="font-size:12px;color:#A0AEC0;margin-top:5px;">2023 batch? Use your DOST ID instead ↓</p>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:#162B3E;margin-bottom:6px;">
                        DOST ID <span style="color:#A0AEC0;font-weight:400;font-size:12px;">(alternative)</span>
                    </label>
                    <input type="text" name="dost_id" value="{{ old('dost_id') }}"
                           class="form-input mono" placeholder="DOST / TS number">
                </div>

                <button type="submit" class="btn-login">Sign In →</button>
            </form>

        </div>
    </div>

</body>
</html>
