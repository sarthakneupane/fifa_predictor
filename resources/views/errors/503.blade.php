@php
    $code        = 503;
    $title       = 'Under Maintenance';
    $icon        = '🏗️';
    $glowColor   = 'rgba(59,130,246,0.08)';
    $codeGlow    = 'rgba(59,130,246,0.35)';
    $description = 'FIFA 2026 Predictor is currently down for scheduled maintenance. We\'re making improvements so you can predict better than ever. We\'ll be back shortly!';

    // Show retry-after header if present (set in .env APP_DOWN_FOR_MAINTENANCE_MESSAGE or similar)
    $retryAfter = request()->header('Retry-After');
    $extra = '<div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse flex-shrink-0"></div>
                    <p class="text-gray-300 text-sm font-medium">Maintenance in progress</p>
                </div>
                ' . ($retryAfter
                    ? '<p class="text-gray-500 text-xs ml-5">Estimated completion: <span class="text-blue-400 font-mono">' . $retryAfter . '</span></p>'
                    : '<p class="text-gray-500 text-xs ml-5">Expected downtime: a few minutes</p>'
                  ) . '
                <div class="pt-2 border-t border-white/5">
                    <p class="text-gray-600 text-xs">Your account, predictions, and points are all safe. Nothing has been lost.</p>
                </div>
              </div>';

    // Override buttons — no "back" on maintenance, just retry
    $secondaryHref  = null;
@endphp

{{-- Custom maintenance page — override layout buttons slightly --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="60"> {{-- Auto-retry every 60s --}}
    <title>503 – Under Maintenance | FIFA 2026 Predictor</title>
    <meta name="robots" content="noindex">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { 'fifa-red': '#C8102E', 'fifa-dark': '#0A0A0F', 'fifa-card': '#12121A', 'fifa-border': '#1E1E2E' },
                fontFamily: { 'display': ['Bebas Neue', 'sans-serif'], 'heading': ['Oswald', 'sans-serif'] }
            }}
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .pitch-bg {
            background-image:
                repeating-linear-gradient(0deg,transparent,transparent 60px,rgba(255,255,255,0.025) 60px,rgba(255,255,255,0.025) 61px),
                repeating-linear-gradient(90deg,transparent,transparent 60px,rgba(255,255,255,0.025) 60px,rgba(255,255,255,0.025) 61px);
        }
        @keyframes spin-slow { to { transform: rotate(360deg); } }
        .spin-slow { animation: spin-slow 8s linear infinite; }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-16px); }
        }
        .ball-float { animation: float 4s ease-in-out infinite; }
        .glass-card { background: rgba(18,18,26,0.85); border: 1px solid rgba(59,130,246,0.15); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="min-h-screen bg-fifa-dark pitch-bg flex flex-col items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- Blue glow --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background: radial-gradient(ellipse at 50% 40%, rgba(59,130,246,0.07) 0%, transparent 60%);"></div>

    <div class="relative z-10 w-full max-w-lg text-center">

        {{-- Animated maintenance icon --}}
        <div class="relative inline-block mb-8">
            {{-- Spinning ring --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-32 h-32 rounded-full border-4 border-dashed border-blue-900/60 spin-slow"></div>
            </div>
            <div class="ball-float text-7xl relative z-10 leading-none py-4 px-4">🏗️</div>
        </div>

        {{-- Code --}}
        <div class="font-display text-[9rem] leading-none text-white tracking-tight select-none mb-0"
             style="text-shadow: 0 0 80px rgba(59,130,246,0.4);">503</div>

        <h1 class="font-heading text-2xl sm:text-3xl uppercase tracking-widest text-white mb-3 -mt-2">
            Under Maintenance
        </h1>

        <div class="flex items-center gap-4 my-5 max-w-xs mx-auto">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent to-fifa-border"></div>
            <span class="text-gray-700 text-xs font-heading uppercase tracking-widest">FIFA 2026 Predictor</span>
            <div class="flex-1 h-px bg-gradient-to-l from-transparent to-fifa-border"></div>
        </div>

        <p class="text-gray-400 text-base leading-relaxed mb-8 max-w-sm mx-auto">
            We're making improvements to the platform so your predictions are faster and more accurate than ever. Back shortly!
        </p>

        {{-- Info card --}}
        <div class="glass-card rounded-xl p-5 mb-8 max-w-sm mx-auto text-left space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-pulse flex-shrink-0"></div>
                <p class="text-gray-300 text-sm font-medium">Maintenance in progress</p>
            </div>
            <p class="text-gray-500 text-xs ml-5">
                The page will automatically refresh every 60 seconds.
                Page last checked: <span class="text-gray-400 font-mono">{{ now()->format('H:i:s') }} UTC</span>
            </p>
            <div class="pt-3 border-t border-white/5">
                <p class="text-gray-600 text-xs">
                    ✅ Your account, predictions, and points are completely safe.
                </p>
            </div>
        </div>

        {{-- Manual retry --}}
        <button onclick="window.location.reload()"
                class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-600 text-white font-heading uppercase tracking-wider px-8 py-3.5 rounded-xl transition-all hover:scale-[1.03] text-sm">
            🔄 Check Again Now
        </button>

        {{-- Auto-reload countdown --}}
        <p class="mt-6 text-gray-700 text-xs">
            Auto-refreshing in <span id="auto-countdown" class="text-gray-500 font-mono">60</span>s
        </p>

        <p class="mt-10 text-gray-800 text-xs">
            Error 503 &middot; {{ now()->format('d M Y, H:i') }} UTC
        </p>
    </div>

    <script>
        let secs = 60;
        const el = document.getElementById('auto-countdown');
        setInterval(() => {
            secs--;
            if (el) el.textContent = secs;
        }, 1000);
    </script>

</body>
</html>
