<x-guest-layout>
<style>
    :root { --red:#C8102E; --gold:#F5A623; }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(18px); }
        to   { opacity:1; transform:translateY(0);    }
    }
    @keyframes spin {
        from { transform:rotate(0deg);   }
        to   { transform:rotate(360deg); }
    }
    @keyframes pulse-ring {
        0%   { transform:scale(.95); box-shadow:0 0 0 0 rgba(200,16,46,.5);   }
        70%  { transform:scale(1);   box-shadow:0 0 0 14px rgba(200,16,46,0); }
        100% { transform:scale(.95); box-shadow:0 0 0 0 rgba(200,16,46,0);    }
    }
    @keyframes shimmer {
        0%   { background-position:-200% center; }
        100% { background-position: 200% center; }
    }

    .fu  { animation:fadeUp .5s cubic-bezier(.22,1,.36,1) both; }
    .d1  { animation-delay:.06s; }
    .d2  { animation-delay:.13s; }
    .d3  { animation-delay:.20s; }
    .d4  { animation-delay:.27s; }
    .d5  { animation-delay:.34s; }

    /* ── Envelope icon ── */
    .env-wrap {
        width:80px; height:80px; border-radius:50%;
        background:linear-gradient(135deg,rgba(200,16,46,.15),rgba(200,16,46,.05));
        border:2px solid rgba(200,16,46,.25);
        display:flex; align-items:center; justify-content:center;
        margin:0 auto 20px;
        animation:pulse-ring 2.5s ease-out infinite;
    }

    /* ── Steps tracker ── */
    .vstep-list { list-style:none; padding:0; margin:0; }
    .vstep {
        display:flex; align-items:flex-start; gap:14px;
        padding:10px 0;
        border-bottom:1px solid rgba(255,255,255,.05);
    }
    .vstep:last-child { border-bottom:none; }
    .vstep-num {
        width:24px; height:24px; border-radius:50%; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:.72rem; font-weight:700;
        background:rgba(200,16,46,.15);
        border:1.5px solid rgba(200,16,46,.3);
        color:#C8102E;
        font-family:'Oswald',sans-serif;
    }
    .vstep-text { font-size:.83rem; color:#94a3b8; line-height:1.5; padding-top:2px; }
    .vstep-text strong { color:#cbd5e1; font-weight:600; }

    /* ── Buttons ── */
    .btn-resend {
        display:inline-flex; align-items:center; justify-content:center; gap:8px;
        width:100%; padding:13px;
        border:none; border-radius:12px;
        background:linear-gradient(135deg,#C8102E,#8b0b20);
        color:#fff;
        font-family:'Oswald',sans-serif;
        font-size:.9rem; font-weight:600;
        letter-spacing:.1em; text-transform:uppercase;
        cursor:pointer; position:relative; overflow:hidden;
        box-shadow:0 6px 24px rgba(200,16,46,.38);
        transition:transform .15s, box-shadow .15s;
    }
    .btn-resend::after {
        content:'';
        position:absolute; inset:0;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);
        background-size:200% auto;
        animation:shimmer 2.8s linear infinite;
    }
    .btn-resend:hover  { transform:translateY(-2px); box-shadow:0 10px 32px rgba(200,16,46,.48); }
    .btn-resend:active { transform:translateY(0);    box-shadow:0 4px 14px rgba(200,16,46,.28);  }
    .btn-resend:disabled {
        opacity:.55; cursor:not-allowed; transform:none;
        box-shadow:0 4px 14px rgba(200,16,46,.2);
    }
    .btn-resend:disabled::after { animation:none; }

    .btn-logout {
        display:inline-flex; align-items:center; justify-content:center; gap:6px;
        background:none; border:1.5px solid rgba(255,255,255,.08);
        border-radius:12px; padding:12px;
        color:#64748b; font-family:'Oswald',sans-serif;
        font-size:.82rem; font-weight:600;
        letter-spacing:.08em; text-transform:uppercase;
        cursor:pointer; width:100%;
        transition:border-color .2s, color .2s, background .2s;
    }
    .btn-logout:hover {
        border-color:rgba(248,113,113,.3);
        color:#f87171;
        background:rgba(248,113,113,.05);
    }

    /* ── Countdown ── */
    .countdown-ring {
        display:inline-flex; align-items:center; justify-content:center;
        width:36px; height:36px; border-radius:50%;
        border:2px solid rgba(200,16,46,.25);
        font-family:'Bebas Neue',sans-serif;
        font-size:1rem; color:#C8102E;
        flex-shrink:0;
    }

    /* ── Alert success ── */
    .alert-success {
        display:flex; align-items:flex-start; gap:12px;
        background:rgba(74,222,128,.08);
        border:1px solid rgba(74,222,128,.25);
        border-radius:12px; padding:14px;
        margin-bottom:20px;
    }
    .alert-success p { color:#86efac; font-size:.85rem; line-height:1.5; margin:0; }
</style>

{{-- ── Envelope icon ── --}}
<div class="fu d1">
    <div class="env-wrap">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#C8102E" stroke-width="1.8">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
    </div>
</div>

{{-- ── Heading ── --}}
<div class="text-center mb-6 fu d1">
    <h1 style="font-family:'Bebas Neue',sans-serif;font-size:2rem;letter-spacing:.06em;color:#f1f5f9;line-height:1.1;">
        Verify Your Email
    </h1>
    <p style="color:#64748b;font-size:.85rem;margin-top:6px;line-height:1.6;">
        {{ __("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.") }}
    </p>
</div>

{{-- ── Success alert ── --}}
@if (session('status') == 'verification-link-sent')
<div class="alert-success fu d2">
    <span style="font-size:1.2rem;flex-shrink:0;">📬</span>
    <p>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
</div>
@endif

{{-- ── Steps guide ── --}}
<div class="fu d3" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:14px;padding:16px 18px;margin-bottom:22px;">
    <p style="font-family:'Oswald',sans-serif;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:#475569;margin-bottom:12px;">
        What to do next
    </p>
    <ul class="vstep-list">
        <li class="vstep">
            <div class="vstep-num">1</div>
            <p class="vstep-text">Check your <strong>inbox</strong> for an email from FIFA 2026 Predictor</p>
        </li>
        <li class="vstep">
            <div class="vstep-num">2</div>
            <p class="vstep-text">Click the <strong>Verify Email Address</strong> button in the email</p>
        </li>
        <li class="vstep">
            <div class="vstep-num">3</div>
            <p class="vstep-text">You'll be redirected back and can <strong>start predicting</strong> immediately</p>
        </li>
    </ul>
</div>

{{-- ── Resend + logout actions ── --}}
<div class="fu d4" style="display:flex;flex-direction:column;gap:10px;">

    {{-- Resend form (original logic unchanged) --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" id="resend-btn" class="btn-resend" onclick="startCooldown(this)">
            <svg id="resend-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
            </svg>
            Resend Verification Email
        </button>
    </form>

    {{-- Logout form (original logic unchanged) --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            {{ __('Log Out') }}
        </button>
    </form>

</div>

{{-- ── Footer hint ── --}}
<div class="fu d5" style="text-align:center;margin-top:20px;">
    <p style="color:#334155;font-size:.78rem;line-height:1.6;">
        Can't find the email? Check your spam folder or
        <button onclick="document.querySelector('[action*=verification]').submit()"
                style="background:none;border:none;color:#C8102E;cursor:pointer;font-size:.78rem;text-decoration:underline;padding:0;">
            resend it
        </button>.
    </p>
</div>

<script>
    // ── Resend cooldown (60s) to prevent spam ──────────────────────────
    function startCooldown(btn) {
        let secs = 60;
        btn.disabled = true;

        const origHTML = btn.innerHTML;

        const tick = setInterval(() => {
            secs--;
            btn.innerHTML = `
                <span class="countdown-ring">${secs}</span>
                Resend available in ${secs}s
            `;
            if (secs <= 0) {
                clearInterval(tick);
                btn.disabled  = false;
                btn.innerHTML = origHTML;
            }
        }, 1000);
    }
</script>

</x-guest-layout>
