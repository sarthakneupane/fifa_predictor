<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} – {{ $title }} | FIFA 2026 Predictor</title>
    <meta name="robots" content="noindex">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'fifa-red':    '#C8102E',
                        'fifa-blue':   '#003087',
                        'fifa-gold':   '#F5A623',
                        'fifa-dark':   '#0A0A0F',
                        'fifa-card':   '#12121A',
                        'fifa-border': '#1E1E2E',
                    },
                    fontFamily: {
                        'display': ['Bebas Neue', 'sans-serif'],
                        'heading':  ['Oswald', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Animated pitch lines background */
        .pitch-bg {
            background-image:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 60px,
                    rgba(255,255,255,0.025) 60px,
                    rgba(255,255,255,0.025) 61px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 60px,
                    rgba(255,255,255,0.025) 60px,
                    rgba(255,255,255,0.025) 61px
                );
        }

        /* Floating ball animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33%       { transform: translateY(-18px) rotate(120deg); }
            66%       { transform: translateY(-8px) rotate(240deg); }
        }
        .ball-float { animation: float 6s ease-in-out infinite; }

        /* Glitch effect on error code */
        @keyframes glitch {
            0%, 90%, 100% { text-shadow: none; transform: skewX(0deg); }
            91%  { text-shadow: -3px 0 #C8102E; transform: skewX(-1deg); }
            93%  { text-shadow: 3px 0 #003087;  transform: skewX(1deg); }
            95%  { text-shadow: -3px 0 #C8102E; transform: skewX(0deg); }
            97%  { text-shadow: 3px 0 #003087;  transform: skewX(-0.5deg); }
        }
        .glitch { animation: glitch 5s ease-in-out infinite; }

        /* Pulse glow */
        @keyframes glow-pulse {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50%       { opacity: 0.7; transform: scale(1.05); }
        }
        .glow-pulse { animation: glow-pulse 3s ease-in-out infinite; }

        .glass-card {
            background: rgba(18,18,26,0.85);
            border: 1px solid rgba(200,16,46,0.15);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="min-h-screen bg-fifa-dark pitch-bg flex flex-col items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- Radial glows --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="glow-pulse absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full"
             style="background: radial-gradient(circle, {{ $glowColor ?? 'rgba(200,16,46,0.08)' }} 0%, transparent 70%);"></div>
    </div>

    {{-- ── Main error card ─────────────────────────────────────────── --}}
    <div class="relative z-10 w-full max-w-lg text-center">

        {{-- Floating ball --}}
        <div class="ball-float text-7xl mb-6 select-none" role="img" aria-label="Football">
            {{ $icon ?? '⚽' }}
        </div>

        {{-- Error code --}}
        <div class="glitch font-display text-[8rem] sm:text-[10rem] leading-none text-white mb-0 select-none tracking-tight"
             style="text-shadow: 0 0 80px {{ $codeGlow ?? 'rgba(200,16,46,0.4)' }};">
            {{ $code }}
        </div>

        {{-- Title --}}
        <h1 class="font-heading text-white text-2xl sm:text-3xl uppercase tracking-widest mb-3 -mt-2">
            {{ $title }}
        </h1>

        {{-- Divider --}}
        <div class="flex items-center gap-4 my-5 max-w-xs mx-auto">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent to-fifa-border"></div>
            <span class="text-gray-700 text-xs font-heading uppercase tracking-widest">FIFA 2026 Predictor</span>
            <div class="flex-1 h-px bg-gradient-to-l from-transparent to-fifa-border"></div>
        </div>

        {{-- Description --}}
        <p class="text-gray-400 text-base leading-relaxed mb-8 max-w-sm mx-auto">
            {{ $description }}
        </p>

        {{-- Extra content slot (optional) --}}
        @isset($extra)
            <div class="glass-card rounded-xl p-4 mb-6 text-left text-sm max-w-sm mx-auto">
                {!! $extra !!}
            </div>
        @endisset

        {{-- Action buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-7 py-3.5 rounded-xl transition-all hover:scale-[1.03] hover:shadow-lg hover:shadow-red-900/40 text-sm">
                🏠 Go to Homepage
            </a>
            @if(isset($secondaryHref))
            <a href="{{ $secondaryHref }}"
               class="inline-flex items-center justify-center gap-2 border border-white/15 hover:border-white/35 text-gray-300 hover:text-white font-heading uppercase tracking-wider px-7 py-3.5 rounded-xl transition-all text-sm">
                {{ $secondaryLabel ?? 'Back' }}
            </a>
            @else
            <button onclick="history.back()"
                    class="inline-flex items-center justify-center gap-2 border border-white/15 hover:border-white/35 text-gray-300 hover:text-white font-heading uppercase tracking-wider px-7 py-3.5 rounded-xl transition-all text-sm">
                ← Go Back
            </button>
            @endif
        </div>

        {{-- Quick links --}}
        <div class="mt-10 flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
            @foreach([
                ['href' => '/matches',     'label' => 'Matches'],
                ['href' => '/leaderboard', 'label' => 'Leaderboard'],
                ['href' => '/dashboard',   'label' => 'Dashboard'],
            ] as $link)
            <a href="{{ $link['href'] }}"
               class="text-gray-700 hover:text-gray-400 text-xs font-heading uppercase tracking-wider transition-colors">
                {{ $link['label'] }}
            </a>
            @endforeach
        </div>

        {{-- Footer note --}}
        <p class="mt-12 text-gray-800 text-xs">
            Error {{ $code }} &middot; {{ now()->format('d M Y, H:i') }} UTC
        </p>
    </div>

</body>
</html>
