@extends('layouts.app')

@section('title', 'Admin Dashboard – FIFA 2026 Predictor')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-5xl text-white tracking-wide">Admin Panel</h1>
            <p class="text-gray-500 text-sm mt-1">FIFA World Cup 2026 Predictor management</p>
        </div>
        <span class="text-fifa-gold text-xs font-heading uppercase tracking-widest border border-yellow-700 px-3 py-1 rounded-full">
            ⚙️ Admin
        </span>
    </div>

    {{-- ── Quick Nav ─────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-10">
        @foreach([
            ['label' => 'Manage Gameses', 'href' => route('admin.games.index'), 'icon' => '⚽', 'color' => 'border-blue-800 hover:border-blue-600'],
            ['label' => 'Manage Teams',   'href' => route('admin.teams.index'),   'icon' => '🏴', 'color' => 'border-green-800 hover:border-green-600'],
            ['label' => 'View Users',     'href' => route('admin.users.index'),   'icon' => '👥', 'color' => 'border-purple-800 hover:border-purple-600'],
            ['label' => 'Leaderboard',    'href' => route('leaderboard.index'),   'icon' => '🏆', 'color' => 'border-yellow-800 hover:border-yellow-600'],
        ] as $nav)
        <a href="{{ $nav['href'] }}"
           class="glass-card {{ $nav['color'] }} rounded-xl p-5 text-center transition-all hover:scale-[1.02] group">
            <div class="text-3xl mb-2">{{ $nav['icon'] }}</div>
            <div class="text-gray-300 group-hover:text-white text-sm font-heading uppercase tracking-wider transition-colors">
                {{ $nav['label'] }}
            </div>
        </a>
        @endforeach
    </div>

    {{-- ── Stats Cards ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 mb-10">
        @foreach([
            ['label' => 'Users',        'value' => $stats['total_users'],       'icon' => '👤', 'sub' => $stats['verified_users'] . ' verified'],
            ['label' => 'Teams',        'value' => $stats['total_teams'],       'icon' => '🏴', 'sub' => '48 total'],
            ['label' => 'Matches',      'value' => $stats['total_games'],     'icon' => '⚽', 'sub' => $stats['upcoming_games'] . ' upcoming'],
            ['label' => 'Completed',    'value' => $stats['completed_games'], 'icon' => '✅', 'sub' => 'results in'],
            ['label' => 'Predictions',  'value' => number_format($stats['total_predictions']), 'icon' => '🎯', 'sub' => 'all time'],
        ] as $card)
        <div class="glass-card rounded-xl p-4 text-center col-span-1">
            <div class="text-2xl mb-1">{{ $card['icon'] }}</div>
            <div class="font-display text-2xl text-white">{{ $card['value'] }}</div>
            <div class="text-gray-600 text-xs font-heading uppercase tracking-wider mt-0.5">{{ $card['label'] }}</div>
            <div class="text-gray-700 text-xs mt-0.5">{{ $card['sub'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ── Upcoming games (quick actions) ────────────────────────── --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-fifa-border">
                <h2 class="font-heading text-white uppercase tracking-wider">⚽ Upcoming games</h2>
                <a href="{{ route('admin.games.create') }}"
                   class="text-xs bg-fifa-red/20 hover:bg-fifa-red/30 text-fifa-red border border-fifa-red/30 px-3 py-1.5 rounded-lg font-heading uppercase tracking-wider transition-colors">
                    + New Game
                </a>
            </div>
            @forelse($upcomingGames as $game)
            <div class="flex items-center gap-3 px-6 py-4 border-b border-fifa-border/30 hover:bg-white/2 transition-colors">
                <div class="flex-1 text-sm">
                    <div class="text-white font-medium">
                        {{ $game->homeTeam->flag_display }} {{ $game->homeTeam->short_name }}
                        <span class="text-gray-600 mx-1">vs</span>
                        {{ $game->awayTeam->short_name }} {{ $game->awayTeam->flag_display }}
                    </div>
                    <div class="text-gray-600 text-xs mt-0.5">
                        {{ $game->game_date->format('d M Y · H:i') }} · {{ $game->stage }}
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.games.edit', $game) }}"
                       class="text-xs text-blue-400 hover:text-blue-300 border border-blue-900 hover:border-blue-700 px-2.5 py-1 rounded-lg transition-colors font-heading uppercase tracking-wider">
                        Edit
                    </a>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-600 text-sm">No upcoming games</div>
            @endforelse
            <div class="px-6 py-4">
                <a href="{{ route('admin.games.index') }}" class="text-fifa-red text-sm hover:text-red-400 transition-colors">
                    View all games →
                </a>
            </div>
        </div>

        {{-- ── Recent Predictions ────────────────────────────────────────── --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-fifa-border">
                <h2 class="font-heading text-white uppercase tracking-wider">🎯 Recent Predictions</h2>
            </div>
            @forelse($recentPredictions as $pred)
            <div class="flex items-center gap-3 px-6 py-3.5 border-b border-fifa-border/30 hover:bg-white/2 transition-colors">
                <img src="{{ $pred->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <div class="text-white text-xs font-medium truncate">{{ $pred->user->name }}</div>
                    <div class="text-gray-600 text-xs truncate">
                        {{ $pred->game->homeTeam->short_name }} vs {{ $pred->game->awayTeam->short_name }}
                    </div>
                </div>
                <div class="text-center flex-shrink-0">
                    <div class="font-heading text-gray-300 text-sm">{{ $pred->home_score }}–{{ $pred->away_score }}</div>
                    <div class="text-gray-700 text-xs">predicted</div>
                </div>
                @if($pred->is_calculated)
                <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-display
                    {{ $pred->points === 3 ? 'bg-green-700 text-white' : ($pred->points === 1 ? 'bg-yellow-500 text-black' : 'bg-red-900/50 text-red-500') }}">
                    +{{ $pred->points }}
                </span>
                @else
                <span class="text-gray-700 text-xs w-8 text-center">–</span>
                @endif
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-600 text-sm">No predictions yet</div>
            @endforelse
        </div>

    </div>

</div>

@endsection
