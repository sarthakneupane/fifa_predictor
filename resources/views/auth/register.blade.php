@extends('layouts.app')

@section('content')

<style>
    :root { --red:#C8102E; --gold:#F5A623; --dark:#0A0A0F; --card:#12121A; --border:#1E1E2E; }

    /* ── Pitch bg (scoped to this section) ── */
    .reg-bg {
        background:
            radial-gradient(ellipse at 15% 50%, rgba(200,16,46,.09) 0%, transparent 55%),
            radial-gradient(ellipse at 85% 50%, rgba(0,48,135,.09) 0%, transparent 55%),
            #0A0A0F;
        min-height: 100vh;
    }
    .reg-bg::before {
        content:'';
        position:fixed;
        inset:0;
        background-image:
            repeating-linear-gradient(0deg,  transparent, transparent 56px, rgba(255,255,255,.018) 56px, rgba(255,255,255,.018) 57px),
            repeating-linear-gradient(90deg, transparent, transparent 56px, rgba(255,255,255,.018) 56px, rgba(255,255,255,.018) 57px);
        pointer-events:none;
        z-index:0;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(20px); }
        to   { opacity:1; transform:translateY(0);    }
    }
    @keyframes floatBall {
        0%,100% { transform:translateY(0) rotate(0deg);    }
        50%     { transform:translateY(-10px) rotate(180deg); }
    }
    @keyframes shimmer {
        0%   { background-position:-200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes glowPulse {
        0%,100% { box-shadow:0 0 40px rgba(200,16,46,.15); }
        50%     { box-shadow:0 0 70px rgba(200,16,46,.30); }
    }

    .reg-card {
        background:rgba(18,18,26,.95);
        border:1px solid rgba(200,16,46,.18);
        border-radius:22px;
        backdrop-filter:blur(20px);
        box-shadow:0 40px 100px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04) inset;
        animation: glowPulse 5s ease-in-out infinite;
    }

    .fu { animation:fadeUp .5s cubic-bezier(.22,1,.36,1) both; }
    .d1{animation-delay:.05s} .d2{animation-delay:.10s} .d3{animation-delay:.15s}
    .d4{animation-delay:.20s} .d5{animation-delay:.25s} .d6{animation-delay:.30s}
    .d7{animation-delay:.35s} .d8{animation-delay:.40s} .d9{animation-delay:.45s}

    /* ── Field ── */
    .rfield {
        width:100%;
        background:rgba(255,255,255,.05);
        border:1.5px solid rgba(255,255,255,.1);
        border-radius:12px;
        padding:12px 16px 12px 44px;
        color:#f1f5f9;
        font-size:.88rem;
        font-family:'Inter',sans-serif;
        outline:none;
        transition:border-color .2s, box-shadow .2s, background .2s;
    }
    .rfield::placeholder { color:rgba(148,163,184,.45); }
    .rfield:focus {
        border-color:var(--red);
        background:rgba(200,16,46,.05);
        box-shadow:0 0 0 4px rgba(200,16,46,.12);
    }
    .rfield.err { border-color:#f87171; box-shadow:0 0 0 3px rgba(248,113,113,.1); }
    .rfield-wrap { position:relative; }
    .rfield-icon {
        position:absolute; left:14px; top:50%;
        transform:translateY(-50%);
        color:rgba(148,163,184,.45);
        pointer-events:none;
        transition:color .2s;
    }
    .rfield-wrap:focus-within .rfield-icon { color:var(--red); }

    /* File input */
    .file-field {
        width:100%;
        background:rgba(255,255,255,.05);
        border:1.5px dashed rgba(255,255,255,.12);
        border-radius:12px;
        padding:11px 16px;
        color:#94a3b8;
        font-size:.85rem;
        font-family:'Inter',sans-serif;
        cursor:pointer;
        transition:border-color .2s, background .2s;
    }
    .file-field:hover { border-color:rgba(200,16,46,.4); background:rgba(200,16,46,.04); }
    .file-field::file-selector-button {
        background:var(--red);
        border:none;
        border-radius:8px;
        padding:5px 14px;
        color:#fff;
        font-size:.8rem;
        font-family:'Oswald',sans-serif;
        letter-spacing:.06em;
        text-transform:uppercase;
        cursor:pointer;
        margin-right:12px;
        transition:background .2s;
    }
    .file-field::file-selector-button:hover { background:#a00d25; }

    /* Password toggle */
    .toggle-pw {
        position:absolute; right:13px; top:50%; transform:translateY(-50%);
        background:none; border:none; cursor:pointer;
        color:rgba(148,163,184,.45); padding:2px; transition:color .2s;
    }
    .toggle-pw:hover { color:#94a3b8; }

    /* Label */
    .rlabel {
        display:block;
        font-size:.75rem; font-weight:600;
        font-family:'Oswald',sans-serif;
        letter-spacing:.07em; text-transform:uppercase;
        color:#94a3b8; margin-bottom:7px;
    }

    /* Submit */
    .reg-btn {
        width:100%; padding:14px;
        border:none; border-radius:12px;
        background:linear-gradient(135deg,#C8102E,#8b0b20);
        color:#fff;
        font-family:'Oswald',sans-serif;
        font-size:1rem; font-weight:600;
        letter-spacing:.12em; text-transform:uppercase;
        cursor:pointer; position:relative; overflow:hidden;
        box-shadow:0 6px 28px rgba(200,16,46,.4);
        transition:transform .15s, box-shadow .15s;
    }
    .reg-btn::after {
        content:'';
        position:absolute; inset:0;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);
        background-size:200% auto;
        animation:shimmer 2.8s linear infinite;
    }
    .reg-btn:hover  { transform:translateY(-2px); box-shadow:0 12px 36px rgba(200,16,46,.5); }
    .reg-btn:active { transform:translateY(0);    box-shadow:0 4px 16px rgba(200,16,46,.3);  }

    /* Two-col grid */
    .field-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    @media(max-width:520px) { .field-grid { grid-template-columns:1fr; } }

    /* Progress steps */
    .steps { display:flex; align-items:center; justify-content:center; gap:0; margin-bottom:28px; }
    .step  {
        display:flex; flex-direction:column; align-items:center; gap:5px;
        flex:1; position:relative;
    }
    .step-dot {
        width:30px; height:30px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:.75rem; font-family:'Oswald',sans-serif; font-weight:600;
        border:2px solid var(--border);
        color:#64748b; background:var(--card);
        transition:all .3s; position:relative; z-index:1;
    }
    .step.active .step-dot  { border-color:var(--red);   color:#fff; background:var(--red);  box-shadow:0 0 16px rgba(200,16,46,.4); }
    .step.done  .step-dot  { border-color:#4ade80; color:#4ade80; background:rgba(74,222,128,.1); }
    .step-label { font-size:.65rem; font-family:'Oswald',sans-serif; letter-spacing:.08em; text-transform:uppercase; color:#475569; }
    .step.active .step-label { color:#94a3b8; }
    .step-line {
        flex:1; height:2px; background:var(--border);
        margin-top:-18px; position:relative; z-index:0;
    }

    /* Strength bar */
    .strength-bar { height:3px; border-radius:9999px; background:var(--border); margin-top:8px; overflow:hidden; }
    .strength-fill { height:100%; border-radius:9999px; transition:width .4s, background .4s; width:0%; }

    /* Divider */
    .divider { display:flex;align-items:center;gap:12px;margin:22px 0; }
    .divider::before,.divider::after { content:'';flex:1;height:1px;background:var(--border); }
    .divider span { color:rgba(100,116,139,.55);font-size:.72rem;font-family:'Oswald',sans-serif;letter-spacing:.12em;text-transform:uppercase; }
</style>

<div class="reg-bg px-4 py-10 flex items-center justify-center relative">
<div class="w-full max-w-lg reg-card p-8 relative z-10">

    {{-- ── Brand header ── --}}
    <div class="text-center mb-6 fu d1">
        <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-lg shadow-red-900/40"
                 style="background:linear-gradient(135deg,#C8102E,#8b0b20);animation:floatBall 6s ease-in-out infinite;">
                <span style="font-size:1.6rem;line-height:1;">⚽</span>
            </div>
            <p style="font-family:'Bebas Neue',sans-serif;font-size:1.2rem;letter-spacing:.2em;color:#f1f5f9;line-height:1;">
                FIFA <span style="color:#C8102E;">2026</span>
                <span style="color:#475569;font-size:.9rem;"> · PREDICTOR</span>
            </p>
        </a>
    </div>

    {{-- ── Title ── --}}
    <div class="text-center mb-6 fu d2">
        <h1 style="font-family:'Bebas Neue',sans-serif;font-size:2.6rem;letter-spacing:.05em;color:#f1f5f9;line-height:1.05;">
            Create Account
        </h1>
        <p style="color:#64748b;font-size:.88rem;margin-top:4px;">
            Join the FIFA 2026 prediction competition — it's free
        </p>
    </div>

    {{-- ── Progress steps ── --}}
    <!-- <div class="steps fu d2">
        <div class="step active">
            <div class="step-dot">1</div>
            <span class="step-label">Profile</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-dot">2</div>
            <span class="step-label">Security</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-dot">3</div>
            <span class="step-label">Verify</span>
        </div>
    </div> -->

    {{-- ── Errors ── --}}
    @if ($errors->any())
    <div class="fu d2 mb-5 rounded-xl p-4" style="background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.25);">
        <p style="color:#f87171;font-size:.78rem;font-family:'Oswald',sans-serif;letter-spacing:.07em;text-transform:uppercase;margin-bottom:8px;">
            ⚠️ Please fix the following
        </p>
        <ul style="color:#fca5a5;font-size:.83rem;space-y:4px;">
            @foreach ($errors->all() as $error)
                <li style="margin-bottom:3px;">• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ── Form ── --}}
    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display:flex;flex-direction:column;gap:18px;">

            {{-- Name --}}
            <div class="fu d3">
                <label for="name" class="rlabel">Full Name *</label>
                <div class="rfield-wrap">
                    <svg class="rfield-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           required placeholder="Your full name"
                           class="rfield @error('name') err @enderror">
                </div>
                @error('name')
                    <p style="color:#f87171;font-size:.78rem;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="fu d4">
                <label for="email" class="rlabel">Email Address *</label>
                <div class="rfield-wrap">
                    <svg class="rfield-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           required placeholder="you@example.com"
                           class="rfield @error('email') err @enderror">
                </div>
                @error('email')
                    <p style="color:#f87171;font-size:.78rem;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

           

            {{-- Password --}}
            <div class="fu d6">
                <label for="password" class="rlabel">Password *</label>
                <div class="rfield-wrap">
                    <svg class="rfield-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input type="password" id="password" name="password"
                           required placeholder="Min. 8 characters"
                           style="padding-right:44px;"
                           class="rfield @error('password') err @enderror"
                           oninput="checkStrength(this.value)">
                    <button type="button" class="toggle-pw" onclick="togglePw2('password','eyeReg1')" title="Show/hide">
                        <svg id="eyeReg1" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                <p id="strength-label" style="font-size:.72rem;color:#475569;margin-top:4px;font-family:'Oswald',sans-serif;letter-spacing:.05em;text-transform:uppercase;min-height:16px;"></p>
                @error('password')
                    <p style="color:#f87171;font-size:.78rem;margin-top:2px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="fu d7">
                <label for="password_confirmation" class="rlabel">Confirm Password *</label>
                <div class="rfield-wrap">
                    <svg class="rfield-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           required placeholder="Repeat your password"
                           style="padding-right:44px;"
                           class="rfield"
                           oninput="checkMatch()">
                    <button type="button" class="toggle-pw" onclick="togglePw2('password_confirmation','eyeReg2')" title="Show/hide">
                        <svg id="eyeReg2" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                <p id="match-msg" style="font-size:.72rem;margin-top:4px;font-family:'Oswald',sans-serif;letter-spacing:.05em;text-transform:uppercase;min-height:16px;"></p>
            </div>

            {{-- Submit --}}
            <div class="fu d9" style="padding-top:4px;">
                <button type="submit" class="reg-btn">
                    Create Account &nbsp;→
                </button>
            </div>

        </div>{{-- /flex col --}}
    </form>

    {{-- ── Login link ── --}}
    <div class="divider fu d9"><span>Already registered?</span></div>
    <div class="text-center fu d9">
        <a href="{{ route('login') }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:100%;padding:13px;border-radius:12px;border:1.5px solid rgba(255,255,255,.08);color:#94a3b8;font-family:'Oswald',sans-serif;font-size:.9rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;transition:border-color .2s,color .2s,background .2s;"
           onmouseover="this.style.borderColor='rgba(255,255,255,.2)';this.style.color='#f1f5f9';this.style.background='rgba(255,255,255,.04)'"
           onmouseout="this.style.borderColor='rgba(255,255,255,.08)';this.style.color='#94a3b8';this.style.background='transparent'">
            Sign In Instead
        </a>
    </div>

</div>{{-- /card --}}
</div>{{-- /reg-bg --}}

<script>
    // ── Password show/hide ──────────────────────────────────────────────
    function togglePw2(id, iconId) {
        const inp  = document.getElementById(id);
        const icon = document.getElementById(iconId);
        const show = inp.type === 'password';
        inp.type   = show ? 'text' : 'password';
        icon.innerHTML = show
            ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }

    // ── Password strength ───────────────────────────────────────────────
    function checkStrength(val) {
        const fill  = document.getElementById('strength-fill');
        const label = document.getElementById('strength-label');
        if (!val) { fill.style.width='0%'; label.textContent=''; return; }

        let score = 0;
        if (val.length >= 8)              score++;
        if (/[A-Z]/.test(val))            score++;
        if (/[0-9]/.test(val))            score++;
        if (/[^A-Za-z0-9]/.test(val))     score++;

        const levels = [
            { w:'20%', bg:'#ef4444', text:'Weak'      },
            { w:'45%', bg:'#f97316', text:'Fair'      },
            { w:'70%', bg:'#facc15', text:'Good'      },
            { w:'100%',bg:'#4ade80', text:'Strong'    },
        ];
        const l = levels[score - 1] || levels[0];
        fill.style.width      = l.w;
        fill.style.background = l.bg;
        label.textContent     = l.text;
        label.style.color     = l.bg;

        checkMatch();
    }

    // ── Password match ──────────────────────────────────────────────────
    function checkMatch() {
        const pw    = document.getElementById('password').value;
        const conf  = document.getElementById('password_confirmation').value;
        const msg   = document.getElementById('match-msg');
        if (!conf) { msg.textContent = ''; return; }
        if (pw === conf) {
            msg.textContent   = '✓ Passwords match';
            msg.style.color   = '#4ade80';
        } else {
            msg.textContent   = '✗ Passwords do not match';
            msg.style.color   = '#f87171';
        }
    }

    // ── Avatar preview ──────────────────────────────────────────────────
    function previewAvatar(input) {
        const file = input.files[0];
        if (!file) return;
        window._avatarSelected = true;

        const label = document.getElementById('avatar-label');
        label.style.borderColor = 'rgba(200,16,46,.5)';
        label.style.background  = 'rgba(200,16,46,.06)';

        const reader = new FileReader();
        reader.onload = (e) => {
            const prev = document.getElementById('avatar-preview');
            prev.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            document.getElementById('avatar-text').innerHTML =
                `<span style="color:#94a3b8;">${file.name}</span><br><span style="font-size:.7rem;color:#C8102E;">Click to change</span>`;
        };
        reader.readAsDataURL(file);
    }
</script>

@endsection
