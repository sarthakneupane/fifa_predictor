<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FIFA World Cup 2026 Predictor')</title>
    <meta name="description" content="@yield('description', 'Predict FIFA World Cup 2026 match scores and compete on the leaderboard!')">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN (replace with compiled asset in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'fifa-red':   '#C8102E',
                        'fifa-blue':  '#003087',
                        'fifa-gold':  '#F5A623',
                        'fifa-dark':  '#0A0A0F',
                        'fifa-card':  '#12121A',
                        'fifa-border':'#1E1E2E',
                    },
                    fontFamily: {
                        'display': ['Bebas Neue', 'sans-serif'],
                        'heading': ['Oswald', 'sans-serif'],
                        'body':    ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Custom styles --}}
    <style>
        body { font-family: 'Inter', sans-serif; background: #0A0A0F; color: #e2e8f0; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading  { font-family: 'Oswald', sans-serif; }

        /* FIFA-style gradient text */
        .text-gradient {
            background: linear-gradient(135deg, #C8102E 0%, #F5A623 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass-morphism card */
        .glass-card {
            background: rgba(18,18,26,0.85);
            border: 1px solid rgba(200,16,46,0.15);
            backdrop-filter: blur(10px);
        }

        /* Navigation active state */
        .nav-link.active { color: #C8102E; border-bottom: 2px solid #C8102E; }

        /* Match card hover */
        .game-card { transition: transform 0.2s, box-shadow 0.2s; }
        .game-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(200,16,46,0.2); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0A0A0F; }
        ::-webkit-scrollbar-thumb { background: #C8102E; border-radius: 3px; }

        /* Toast notification */
        #toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #toast.show { transform: translateX(0); }

        /* Loading spinner */
        .spinner { border: 3px solid rgba(200,16,46,0.2); border-top-color: #C8102E; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Pulse for live indicator */
        @keyframes pulse-red { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .pulse-red { animation: pulse-red 1.5s ease-in-out infinite; }
    </style>

    @stack('head')
</head>
<body class="min-h-screen flex flex-col">

    {{-- ── Navigation ──────────────────────────────────────────────────── --}}
    <nav class="sticky top-0 z-50 bg-fifa-dark/95 border-b border-fifa-border backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 bg-fifa-red rounded-lg flex items-center justify-center font-display text-white text-xl group-hover:scale-110 transition-transform">
                        ⚽
                    </div>
                    <div class="hidden sm:block">
                        <span class="font-display text-white text-xl tracking-wider">FIFA</span>
                        <span class="font-display text-fifa-red text-xl tracking-wider ml-1">2026</span>
                        <p class="text-gray-500 text-xs -mt-1 font-heading tracking-widest uppercase">Predictor</p>
                    </div>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="nav-link text-gray-300 hover:text-white text-sm font-medium transition-colors pb-1 {{ request()->routeIs('home') ? 'text-fifa-red border-b-2 border-fifa-red' : '' }}">Home</a>
                    <a href="{{ route('games.index') }}" class="nav-link text-gray-300 hover:text-white text-sm font-medium transition-colors pb-1 {{ request()->routeIs('games.*') ? 'text-fifa-red border-b-2 border-fifa-red' : '' }}">Matches</a>
                    <a href="{{ route('leaderboard.index') }}" class="nav-link text-gray-300 hover:text-white text-sm font-medium transition-colors pb-1 {{ request()->routeIs('leaderboard.*') ? 'text-fifa-red border-b-2 border-fifa-red' : '' }}">Leaderboard</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-link text-gray-300 hover:text-white text-sm font-medium transition-colors pb-1 {{ request()->routeIs('dashboard') ? 'text-fifa-red border-b-2 border-fifa-red' : '' }}">Dashboard</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-fifa-gold text-sm font-medium hover:text-yellow-300 transition-colors">⚙️ Admin</a>
                        @endif
                    @endauth
                </div>

                {{-- Auth Buttons / User Menu --}}
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Log In</a>
                        <a href="{{ route('register') }}" class="bg-fifa-red hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            Sign Up Free
                        </a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 bg-fifa-card border border-fifa-border rounded-lg px-3 py-2 hover:border-fifa-red/50 transition-colors">
                                <img src="{{ auth()->user()->avatar_url }}" class="w-7 h-7 rounded-full object-cover" alt="Avatar">
                                <span class="text-sm text-gray-300 max-w-24 truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-48 bg-fifa-card border border-fifa-border rounded-xl shadow-xl py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">📊 Dashboard</a>
                                <a href="{{ route('predictions.my') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">🎯 My Predictions</a>
                                <hr class="border-fifa-border my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-white/5">🚪 Logout</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                {{-- Mobile Hamburger --}}
                <button class="md:hidden text-gray-300 hover:text-white" id="mobile-menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-fifa-card border-t border-fifa-border">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white font-medium">🏠 Home</a>
                <a href="{{ route('games.index') }}" class="block text-gray-300 hover:text-white font-medium">⚽ Matches</a>
                <a href="{{ route('leaderboard.index') }}" class="block text-gray-300 hover:text-white font-medium">🏆 Leaderboard</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-gray-300 hover:text-white font-medium">📊 Dashboard</a>
                    <a href="{{ route('predictions.my') }}" class="block text-gray-300 hover:text-white font-medium">🎯 My Predictions</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block text-fifa-gold font-medium">⚙️ Admin Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-400 font-medium">🚪 Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white font-medium">Log In</a>
                    <a href="{{ route('register') }}" class="inline-block bg-fifa-red text-white font-semibold px-4 py-2 rounded-lg">Sign Up</a>
                @endguest
            </div>
        </div>
    </nav>

    {{-- ── Flash Messages ───────────────────────────────────────────────── --}}
    @if(session('success') || session('error'))
        <div id="flash-msg" class="fixed top-20 left-1/2 -translate-x-1/2 z-50
            px-6 py-3 rounded-xl font-medium text-sm shadow-lg
            {{ session('success') ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
            {{ session('success') ?? session('error') }}
        </div>
        <script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
    @endif

    {{-- ── Main Content ─────────────────────────────────────────────────── --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────────────────── --}}
    <footer class="bg-fifa-card border-t border-fifa-border mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-fifa-red rounded-lg flex items-center justify-center text-xl">⚽</div>
                        <div>
                            <span class="font-display text-white text-xl">FIFA 2026</span>
                            <p class="text-gray-500 text-xs font-heading tracking-widest uppercase">Predictor</p>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        The ultimate FIFA World Cup 2026 score prediction platform. Predict, compete, and climb the leaderboard!
                    </p>
                </div>
                <div>
                    <h4 class="font-heading text-white uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('games.index') }}" class="hover:text-fifa-red transition-colors">All Matches</a></li>
                        <li><a href="{{ route('leaderboard.index') }}" class="hover:text-fifa-red transition-colors">Leaderboard</a></li>
                        @guest
                            <li><a href="{{ route('register') }}" class="hover:text-fifa-red transition-colors">Join Now</a></li>
                        @endguest
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading text-white uppercase tracking-wider mb-4">Scoring</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-3">
                            <span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded font-bold">+3</span>
                            <span class="text-gray-400">Exact score prediction</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-yellow-500 text-black text-xs px-2 py-0.5 rounded font-bold">+1</span>
                            <span class="text-gray-400">Correct result (W/D/L)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded font-bold">+0</span>
                            <span class="text-gray-400">Wrong prediction</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-fifa-border mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-gray-600 text-sm">© {{ date('Y') }} FIFA World Cup 2026 Predictor. Fan project – not affiliated with FIFA.</p>
                <p class="text-gray-600 text-xs">Built by Sarthak Neupane ❤️</p>
            </div>
        </div>
    </footer>

    {{-- ── Toast Notification ───────────────────────────────────────────── --}}
    <div id="toast" class="bg-fifa-card border border-fifa-border rounded-xl px-5 py-4 shadow-2xl flex items-center gap-3 min-w-64 max-w-sm">
        <span id="toast-icon" class="text-xl">✅</span>
        <span id="toast-msg" class="text-sm font-medium text-white"></span>
    </div>

    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>

    <script>
        // ─── Mobile menu toggle ──────────────────────────────────────────
        $('#mobile-menu-btn').on('click', function() {
            $('#mobile-menu').toggleClass('hidden');
        });

        // ─── CSRF token for AJAX ─────────────────────────────────────────
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // ─── Toast helper ─────────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const icons = { success: '✅', error: '❌', info: '💡', warning: '⚠️' };
            $('#toast-icon').text(icons[type] || '💬');
            $('#toast-msg').text(message);
            const $toast = $('#toast');
            $toast.addClass('show');
            setTimeout(() => $toast.removeClass('show'), 4000);
        }

        // ─── Countdown Timers ─────────────────────────────────────────────
        function initCountdowns() {
            $('[data-countdown]').each(function() {
                const $el     = $(this);
                const endTime = new Date($el.data('countdown')).getTime();

                function update() {
                    const now  = Date.now();
                    const diff = endTime - now;

                    if (diff <= 0) {
                        $el.html('<span class="text-fifa-red font-heading uppercase tracking-wider text-xs">Match Started</span>');
                        return;
                    }

                    const d = Math.floor(diff / 86400000);
                    const h = Math.floor((diff % 86400000) / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);

                    let str = '';
                    if (d > 0) str += `<span>${d}d</span> `;
                    str += `<span>${String(h).padStart(2,'0')}h</span> `;
                    str += `<span>${String(m).padStart(2,'0')}m</span> `;
                    str += `<span>${String(s).padStart(2,'0')}s</span>`;

                    $el.html(str);
                }

                update();
                setInterval(update, 1000);
            });
        }

        $(document).ready(initCountdowns);
    </script>

    @stack('scripts')
</body>
</html>