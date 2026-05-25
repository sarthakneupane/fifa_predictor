<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'FIFA 2026 Predictor') }} – Log In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --red:    #C8102E;
            --gold:   #F5A623;
            --dark:   #0A0A0F;
            --card:   #12121A;
            --border: #1E1E2E;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            margin: 0;
        }

        /* ── Pitch grid ── */
        .pitch-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(0deg,  transparent, transparent 56px, rgba(255,255,255,.025) 56px, rgba(255,255,255,.025) 57px),
                repeating-linear-gradient(90deg, transparent, transparent 56px, rgba(255,255,255,.025) 56px, rgba(255,255,255,.025) 57px);
            pointer-events: none;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0);    }
        }
        @keyframes floatBall {
            0%,100% { transform:translateY(0)   rotate(0deg);   }
            33%     { transform:translateY(-14px) rotate(130deg); }
            66%     { transform:translateY(-6px)  rotate(260deg); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes glowPulse {
            0%,100% { opacity:.5; }
            50%     { opacity:.9; }
        }

        .fade-up   { animation: fadeUp .55s cubic-bezier(.22,1,.36,1) both; }
        .d1 { animation-delay: .05s; }
        .d2 { animation-delay: .12s; }
        .d3 { animation-delay: .19s; }
        .d4 { animation-delay: .26s; }
        .d5 { animation-delay: .33s; }

        .ball { animation: floatBall 7s ease-in-out infinite; display:inline-block; }

        /* ── Card ── */
        .auth-card {
            background: rgba(18,18,26,.92);
            border: 1px solid rgba(200,16,46,.18);
            backdrop-filter: blur(18px);
            border-radius: 20px;
            box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04) inset;
        }

        /* ── Input ── */
        .field {
            width: 100%;
            background: rgba(255,255,255,.05);
            border: 1.5px solid rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 13px 16px 13px 44px;
            color: #f1f5f9;
            font-size: .9rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .field::placeholder { color: rgba(148,163,184,.5); }
        .field:focus {
            border-color: var(--red);
            background: rgba(200,16,46,.06);
            box-shadow: 0 0 0 4px rgba(200,16,46,.12);
        }
        .field.error {
            border-color: #f87171;
            box-shadow: 0 0 0 4px rgba(248,113,113,.1);
        }
        .field-wrap { position: relative; }
        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(148,163,184,.5);
            pointer-events: none;
            transition: color .2s;
        }
        .field-wrap:focus-within .field-icon { color: var(--red); }

        /* Password toggle */
        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(148,163,184,.5);
            padding: 2px;
            transition: color .2s;
        }
        .toggle-pw:hover { color: #94a3b8; }

        /* ── Submit ── */
        .btn-primary {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #C8102E 0%, #a00d25 100%);
            color: #fff;
            font-family: 'Oswald', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 6px 24px rgba(200,16,46,.35);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.12) 50%, transparent 100%);
            background-size: 200% auto;
            animation: shimmer 2.5s linear infinite;
        }
        .btn-primary:hover  { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(200,16,46,.45); }
        .btn-primary:active { transform: translateY(0);    box-shadow: 0 4px 16px rgba(200,16,46,.3);  }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .divider span {
            color: rgba(100,116,139,.6);
            font-size: .72rem;
            font-family: 'Oswald', sans-serif;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        /* ── Label ── */
        .field-label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
            letter-spacing: .05em;
            text-transform: uppercase;
            font-family: 'Oswald', sans-serif;
        }

        /* ── Glow blobs ── */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: glowPulse 4s ease-in-out infinite;
        }

        /* Checkbox */
        .check-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: #94a3b8;
            font-size: .85rem;
        }
        .check-label input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--red);
            cursor: pointer;
            flex-shrink: 0;
        }

        /* Scoring pill strip */
        .score-strip {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .score-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 9999px;
            padding: 5px 12px;
            font-size: .72rem;
            color: #64748b;
            font-family: 'Oswald', sans-serif;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .score-pill .pts { font-weight:700; font-family:'Bebas Neue',sans-serif; font-size:.95rem; }
    </style>
</head>

<body class="pitch-bg min-h-screen flex items-center justify-center px-4 py-10 relative overflow-hidden" style="background:#0A0A0F">

    {{-- Glow blobs --}}
    <div class="blob" style="width:520px;height:520px;top:-140px;left:-140px;background:rgba(200,16,46,.07);"></div>
    <div class="blob" style="width:480px;height:480px;bottom:-120px;right:-120px;background:rgba(0,48,135,.09);animation-delay:2s;"></div>
    <div class="blob" style="width:300px;height:300px;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(200,16,46,.04);animation-delay:1s;"></div>

    <div class="auth-card w-full max-w-md p-8 relative z-10">

        {{-- ── Logo / brand ── --}}
        <div class="text-center mb-8 fade-up d1">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg shadow-red-900/40 group-hover:scale-105 transition-transform"
                     style="background:linear-gradient(135deg,#C8102E,#8b0b20);">
                    <span class="ball text-3xl leading-none">⚽</span>
                </div>
                <div>
                    <p style="font-family:'Bebas Neue',sans-serif;font-size:1.35rem;letter-spacing:.18em;color:#fff;line-height:1;">
                        FIFA <span style="color:#C8102E;">2026</span>
                    </p>
                    <p style="font-family:'Oswald',sans-serif;font-size:.65rem;letter-spacing:.25em;color:#475569;text-transform:uppercase;margin-top:1px;">
                        Predictor
                    </p>
                </div>
            </a>
        </div>

        {{-- ── Heading ── --}}
        <div class="text-center mb-7 fade-up d2">
            <h1 style="font-family:'Bebas Neue',sans-serif;font-size:2.4rem;letter-spacing:.06em;color:#f1f5f9;line-height:1;">
                Welcome Back
            </h1>
            <p style="color:#64748b;font-size:.88rem;margin-top:4px;">
                Sign in and continue predicting
            </p>
        </div>

        {{-- Session / status messages --}}
        @if (session('status'))
            <div class="fade-up d2 mb-5 flex items-start gap-3 rounded-xl p-4" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);">
                <span style="color:#4ade80;font-size:1.1rem;margin-top:1px;">✅</span>
                <p style="color:#86efac;font-size:.85rem;">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="fade-up d2 mb-5 flex items-start gap-3 rounded-xl p-4" style="background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.25);">
                <span style="color:#f87171;font-size:1.1rem;margin-top:1px;">⚠️</span>
                <p style="color:#fca5a5;font-size:.85rem;">{{ $errors->first() }}</p>
            </div>
        @endif

        {{-- ── Form ── --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div class="fade-up d3">
                <label for="email" class="field-label">Email address</label>
                <div class="field-wrap">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="name@example.com"
                           class="field @error('email') error @enderror">
                </div>
                @error('email')
                    <p style="color:#f87171;font-size:.78rem;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="fade-up d4">
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="field-label" style="margin-bottom:0;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           style="color:#C8102E;font-size:.78rem;font-family:'Oswald',sans-serif;letter-spacing:.05em;text-transform:uppercase;text-decoration:none;transition:color .2s;"
                           onmouseover="this.style.color='#e53e3e'" onmouseout="this.style.color='#C8102E'">
                            Forgot?
                        </a>
                    @endif
                </div>
                <div class="field-wrap">
                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="field @error('password') error @enderror"
                           style="padding-right:44px;">
                    <button type="button" class="toggle-pw" onclick="togglePw('password','eyeIcon')" title="Show/hide password">
                        <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p style="color:#f87171;font-size:.78rem;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember me --}}
            <!-- <div class="fade-up d4">
                <label class="check-label">
                    <input type="checkbox" name="remember">
                    <span>Remember me for 30 days</span>
                </label>
            </div> -->

            {{-- Submit --}}
            <div class="fade-up d5" style="padding-top:4px;">
                <button type="submit" class="btn-primary">
                    Sign In &nbsp;→
                </button>
            </div>
        </form>

        {{-- ── Divider + register link ── --}}
        @if (Route::has('register'))
            <div class="divider fade-up d5"><span>New to the platform?</span></div>
            <div class="text-center fade-up d5">
                <a href="{{ route('register') }}"
                   style="display:inline-flex;align-items:center;gap:8px;width:100%;justify-content:center;padding:13px;border-radius:12px;border:1.5px solid rgba(200,16,46,.25);color:#f1f5f9;font-family:'Oswald',sans-serif;font-size:.9rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;transition:border-color .2s,background .2s;"
                   onmouseover="this.style.borderColor='rgba(200,16,46,.55)';this.style.background='rgba(200,16,46,.06)'"
                   onmouseout="this.style.borderColor='rgba(200,16,46,.25)';this.style.background='transparent'">
                    Create Free Account
                </a>
            </div>
        @endif

        {{-- ── Scoring strip ── --}}
        <!-- <div class="score-strip fade-up d5">
            <div class="score-pill"><span class="pts" style="color:#4ade80;">+3</span><span>Exact</span></div>
            <div class="score-pill"><span class="pts" style="color:#facc15;">+1</span><span>Result</span></div>
            <div class="score-pill"><span class="pts" style="color:#64748b;">+0</span><span>Wrong</span></div>
        </div> -->

    </div>{{-- /card --}}

    <script>
        function togglePw(inputId, iconId) {
            const inp  = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const show = inp.type === 'password';
            inp.type   = show ? 'text' : 'password';
            icon.innerHTML = show
                ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    </script>

</body>
</html>
