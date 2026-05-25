@extends('layouts.app')

@section('title', 'FIFA World Cup 2026 Predictor – Home')

@section('content')

{{-- ── Hero Section ─────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[85vh] flex items-center">
    {{-- Background --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-fifa-dark via-[#0d0d18] to-[#1a0008]"></div>
        {{-- Decorative pitch lines --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255,255,255,0.5) 40px, rgba(255,255,255,0.5) 41px), repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(255,255,255,0.5) 40px, rgba(255,255,255,0.5) 41px);"></div>
        {{-- Red glow --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-fifa-red/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-900/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 bg-fifa-red/10 border border-fifa-red/30 text-fifa-red text-xs font-heading uppercase tracking-widest px-4 py-2 rounded-full mb-8">
            <span class="w-2 h-2 bg-fifa-red rounded-full pulse-red"></span>
            FIFA World Cup 2026 · USA · Canada · Mexico
        </div>

        {{-- Main Headline --}}
        <h1 class="font-display text-6xl sm:text-8xl lg:text-[9rem] text-white leading-none tracking-wide mb-4">
            PREDICT
            <br>
            <span class="text-gradient">& WIN</span>
        </h1>

        <p class="text-gray-400 text-lg sm:text-xl max-w-2xl mx-auto mb-10 font-light leading-relaxed">
            Predict scores for every FIFA World Cup 2026 game. Earn points, climb the leaderboard, and prove you know football best.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            @guest
                <a href="{{ route('register') }}"
                   class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider text-lg px-8 py-4 rounded-xl transition-all hover:scale-105 hover:shadow-lg hover:shadow-red-900/50">
                    Start Predicting Now
                </a>
                <a href="{{ route('games.index') }}"
                   class="border border-white/20 text-white hover:border-white/50 font-heading uppercase tracking-wider text-lg px-8 py-4 rounded-xl transition-all">
                    View Games
                </a>
            @else
                <a href="{{ route('games.index') }}"
                   class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider text-lg px-8 py-4 rounded-xl transition-all hover:scale-105 hover:shadow-lg hover:shadow-red-900/50">
                    ⚽ Predict Games
                </a>
                <a href="{{ route('dashboard') }}"
                   class="border border-white/20 text-white hover:border-white/50 font-heading uppercase tracking-wider text-lg px-8 py-4 rounded-xl transition-all">
                    📊 My Dashboard
                </a>
            @endguest
        </div>

        {{-- Stats Bar --}}
        <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-3xl mx-auto">
            @foreach([
                ['number' => $stats['total_games'],      'label' => 'Total Games'],
                ['number' => $stats['total_users'],        'label' => 'Predictors'],
                ['number' => $stats['total_predictions'],  'label' => 'Predictions Made'],
                ['number' => $stats['completed_games'],  'label' => 'Results In'],
            ] as $stat)
            <div class="glass-card rounded-xl p-4">
                <div class="font-display text-3xl text-white">{{ number_format($stat['number']) }}</div>
                <div class="text-gray-500 text-xs font-heading uppercase tracking-wider mt-1">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Live Games ──────────────────────────────────────────────────────── --}}
@if($liveGames->count() > 0)
<section class="py-12 bg-red-950/20 border-y border-red-900/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="w-3 h-3 bg-red-500 rounded-full pulse-red"></span>
            <h2 class="font-heading text-red-400 uppercase tracking-widest text-sm">Live Now</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($liveGames as $game)
                @include('partials.game-card', ['game' => $game, 'highlight' => 'live'])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Upcoming Games ──────────────────────────────────────────────────── --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="font-display text-4xl text-white tracking-wide">Upcoming Games</h2>
                <p class="text-gray-500 text-sm mt-1">Predict before kick-off to earn points</p>
            </div>
            <a href="{{ route('games.index') }}" class="text-fifa-red hover:text-red-400 text-sm font-medium transition-colors">
                View All →
            </a>
        </div>

        @if($upcomingGames->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($upcomingGames as $game)
                    @include('partials.game-card', ['game' => $game])
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-gray-600">
                <div class="text-5xl mb-4">⚽</div>
                <p class="font-heading uppercase tracking-wide">No upcoming games right now</p>
            </div>
        @endif
    </div>
</section>

{{-- ── Recent Results ────────────────────────────────────────────────────── --}}
@if($recentResults->count() > 0)
<section class="py-12 bg-fifa-card/30 border-y border-fifa-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-4xl text-white tracking-wide mb-8">Recent Results</h2>
        <div class="space-y-3">
            @foreach($recentResults as $game)
            <div class="glass-card rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1">
                    <span class="text-xl">{{ $game->homeTeam->flag_display }}</span>
                    <span class="font-heading text-white text-sm uppercase">{{ $game->homeTeam->short_name }}</span>
                </div>
                <div class="text-center px-6">
                    <div class="font-display text-2xl text-white">{{ $game->home_score }} – {{ $game->away_score }}</div>
                    <div class="text-gray-600 text-xs">{{ $game->game_date->format('d M') }}</div>
                </div>
                <div class="flex items-center gap-3 flex-1 justify-end">
                    <span class="font-heading text-white text-sm uppercase">{{ $game->awayTeam->short_name }}</span>
                    <span class="text-xl">{{ $game->awayTeam->flag_display }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Leaderboard Preview ───────────────────────────────────────────────── --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="font-display text-4xl text-white tracking-wide">🏆 Top Predictors</h2>
                <p class="text-gray-500 text-sm mt-1">Global leaderboard – updated after every game</p>
            </div>
            <a href="{{ route('leaderboard.index') }}" class="text-fifa-red hover:text-red-400 text-sm font-medium transition-colors">
                Full Leaderboard →
            </a>
        </div>

        <div class="glass-card rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-fifa-border">
                        <th class="text-left px-4 py-3 text-gray-500 font-heading uppercase text-xs tracking-wider">Rank</th>
                        <th class="text-left px-4 py-3 text-gray-500 font-heading uppercase text-xs tracking-wider">Player</th>
                        <th class="text-center px-4 py-3 text-gray-500 font-heading uppercase text-xs tracking-wider hidden sm:table-cell">Predictions</th>
                        <th class="text-center px-4 py-3 text-gray-500 font-heading uppercase text-xs tracking-wider hidden md:table-cell">Accuracy</th>
                        <th class="text-right px-4 py-3 text-gray-500 font-heading uppercase text-xs tracking-wider">Points</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPredictors as $index => $user)
                    <tr class="border-b border-fifa-border/50 hover:bg-white/2 transition-colors {{ auth()->id() === $user->id ? 'bg-fifa-red/5 border-l-2 border-l-fifa-red' : '' }}">
                        <td class="px-4 py-3">
                            @if($index === 0)
                                <span class="text-xl">🥇</span>
                            @elseif($index === 1)
                                <span class="text-xl">🥈</span>
                            @elseif($index === 2)
                                <span class="text-xl">🥉</span>
                            @else
                                <span class="text-gray-500 font-heading">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover" alt="{{ $user->name }}">
                                <span class="font-medium text-white">
                                    {{ $user->name }}
                                    @if(auth()->id() === $user->id)
                                        <span class="text-fifa-red text-xs ml-1">(you)</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-400 hidden sm:table-cell">{{ $user->total_predictions_count }}</td>
                        <td class="px-4 py-3 text-center hidden md:table-cell">
                            <span class="text-green-400 font-medium">{{ $user->accuracy }}%</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-display text-xl text-fifa-gold">{{ $user->total_points ?? 0 }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-600">
                            No predictions yet – be the first to predict!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @guest
        <div class="mt-6 text-center">
            <p class="text-gray-500 mb-4">Join to appear on the leaderboard</p>
            <a href="{{ route('register') }}" class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-6 py-3 rounded-xl transition-all">
                Join Now – It's Free
            </a>
        </div>
        @endguest
    </div>
</section>

{{-- ── How It Works ──────────────────────────────────────────────────────── --}}
<section class="py-16 bg-fifa-card/30 border-y border-fifa-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-4xl text-white tracking-wide mb-12">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['icon' => '📝', 'step' => '01', 'title' => 'Register', 'desc' => 'Create your free account and verify your email to unlock predictions.'],
                ['icon' => '🎯', 'step' => '02', 'title' => 'Predict', 'desc' => 'Enter your score prediction for each game before kick-off.'],
                ['icon' => '🏆', 'step' => '03', 'title' => 'Win', 'desc' => 'Exact score = 3pts. Correct result = 1pt. Climb the global leaderboard!'],
            ] as $step)
            <div class="glass-card rounded-2xl p-8 hover:border-fifa-red/30 transition-colors group">
                <div class="text-5xl mb-4">{{ $step['icon'] }}</div>
                <div class="font-display text-fifa-red text-5xl mb-2 group-hover:scale-110 transition-transform">{{ $step['step'] }}</div>
                <h3 class="font-heading text-white text-xl uppercase tracking-wider mb-3">{{ $step['title'] }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection