@extends('layouts.app')

@section('title', 'Dashboard – FIFA 2026 Predictor')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Welcome --}}
    <div class="mb-10 flex items-center gap-4">
        <img src="{{ auth()->user()?->avatar_url }}" class="w-16 h-16 rounded-full border-2 border-fifa-red" alt="Avatar">
        <div>
            <h1 class="font-display text-4xl text-white tracking-wide">
                {{ auth()->user()?->name }}
            </h1>
            <p class="text-gray-500 text-sm">
                Rank: <span class="text-fifa-gold font-heading font-bold">{{ is_numeric($userRank) ? '#' . $userRank : $userRank }}</span>
                @auth
                    @if(auth()->user()->country)
                        · {{ auth()->user()->country }}
                    @endif
                @endauth
            </p>
        </div>
    </div>

    {{-- ── Stats Cards ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        @foreach([
            ['label' => 'Total Points',     'value' => $stats['total_points'],      'icon' => '⭐', 'color' => 'text-fifa-gold'],
            ['label' => 'Predictions',      'value' => $stats['total_predictions'], 'icon' => '🎯', 'color' => 'text-blue-400'],
            ['label' => 'Correct Results',  'value' => $stats['correct_predictions'],'icon' => '✅', 'color' => 'text-green-400'],
            ['label' => 'Accuracy',         'value' => $stats['accuracy'] . '%',    'icon' => '📊', 'color' => 'text-purple-400'],
        ] as $card)
        <div class="glass-card rounded-2xl p-5 text-center hover:border-white/20 transition-colors">
            <div class="text-3xl mb-2">{{ $card['icon'] }}</div>
            <div class="font-display text-3xl {{ $card['color'] }}">{{ $card['value'] }}</div>
            <div class="text-gray-600 text-xs font-heading uppercase tracking-wider mt-1">{{ $card['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Left Column ──────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Accuracy Chart --}}
            @if($chartData->count() > 0)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="font-heading text-white text-lg uppercase tracking-wider mb-4">
                    📈 Points Per Game
                </h2>

                <div class="flex items-end gap-2 h-32">
                    @foreach($chartData as $item)

                        @php
                            $height = match ($item['points']) {
                                3 => 100,
                                1 => 45,
                                default => 12,
                            };
                        @endphp

                        <div class="flex-1 flex flex-col items-center gap-1"
                            title="{{ $item['label'] }}: {{ $item['points'] }}pts">

                            <span class="text-xs text-gray-600 font-bold">
                                {{ $item['points'] }}
                            </span>

                            <div class="w-full rounded-t-sm transition-all
                                {{ $item['points'] === 3
                                    ? 'bg-green-500'
                                    : ($item['points'] === 1
                                        ? 'bg-yellow-500'
                                        : 'bg-red-800') }}"
                                style="height: {{ $height }}%">
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class="flex gap-4 mt-4 text-xs text-gray-600">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                        Exact (3pts)
                    </span>

                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full inline-block"></span>
                        Correct (1pt)
                    </span>

                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-red-800 rounded-full inline-block"></span>
                        Wrong (0pts)
                    </span>
                </div>
            </div>
            @endif

            {{-- Recent Predictions --}}
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-fifa-border">
                    <h2 class="font-heading text-white text-lg uppercase tracking-wider">🎯 Recent Predictions</h2>
                    <a href="{{ route('predictions.my') }}" class="text-fifa-red text-sm hover:text-red-400 transition-colors">View All</a>
                </div>
                @forelse($recentPredictions as $pred)
                <div class="flex items-center gap-4 px-6 py-4 border-b border-fifa-border/40 hover:bg-white/2">
                    {{-- Teams --}}
                    <div class="flex-1 text-sm">
                        <div class="flex items-center gap-2">
                            <span>{{ $pred->game->homeTeam->flag_display }}</span>
                            <span class="text-gray-300 font-medium">{{ $pred->game->homeTeam->short_name }}</span>
                            <span class="text-gray-600">vs</span>
                            <span class="text-gray-300 font-medium">{{ $pred->game->awayTeam->short_name }}</span>
                            <span>{{ $pred->game->awayTeam->flag_display }}</span>
                        </div>
                        <div class="text-gray-600 text-xs mt-0.5">{{ $pred->game->game_date->format('d M Y') }}</div>
                    </div>
                    {{-- Predicted score --}}
                    <div class="text-center">
                        <div class="font-heading text-white text-sm">{{ $pred->home_score }} – {{ $pred->away_score }}</div>
                        <div class="text-gray-600 text-xs">Predicted</div>
                    </div>
                    {{-- Actual & Points --}}
                    @if($pred->is_calculated)
                    <div class="text-center">
                        <div class="font-heading text-white text-sm">{{ $pred->game->home_score }} – {{ $pred->game->away_score }}</div>
                        <div class="text-gray-600 text-xs">Actual</div>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $pred->points_badge_class }}">
                        +{{ $pred->points }}
                    </span>
                    @elseif($pred->game->status === 'upcoming')
                    <span class="text-gray-600 text-xs font-heading uppercase tracking-wider">Pending</span>
                    @endif
                </div>
                @empty
                <div class="px-6 py-10 text-center text-gray-600">
                    <div class="text-4xl mb-3">🎯</div>
                    <p>No predictions yet. <a href="{{ route('games.index') }}" class="text-fifa-red hover:underline">Start predicting!</a></p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── Right Column ─────────────────────────────────────────────── --}}
        <div class="space-y-6">

            {{-- Predict Now --}}
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-fifa-border">
                    <h2 class="font-heading text-white uppercase tracking-wider">⚽ Predict Now</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Games you haven't predicted</p>
                </div>
                @forelse($unpredictedGames as $game)
                <a href="{{ route('games.show', $game) }}" class="flex items-center gap-3 px-5 py-4 border-b border-fifa-border/40 hover:bg-white/3 transition-colors group">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <span class="text-lg">{{ $game->homeTeam->flag_display }}</span>
                        <span class="text-gray-300 text-sm font-medium truncate">{{ $game->homeTeam->short_name }}</span>
                        <span class="text-gray-700 text-xs">vs</span>
                        <span class="text-gray-300 text-sm font-medium truncate">{{ $game->awayTeam->short_name }}</span>
                        <span class="text-lg">{{ $game->awayTeam->flag_display }}</span>
                    </div>
                    <span class="text-fifa-red text-sm group-hover:translate-x-1 transition-transform">→</span>
                </a>
                @empty
                <div class="px-5 py-8 text-center text-gray-600 text-sm">
                    🎉 You've predicted all upcoming games!
                </div>
                @endforelse
                <div class="p-4">
                    <a href="{{ route('games.index') }}"
                       class="block text-center text-fifa-red hover:text-red-400 text-sm font-medium transition-colors">
                        View All games →
                    </a>
                </div>
            </div>

            {{-- Breakdown --}}
            <div class="glass-card rounded-2xl p-5">
                <h3 class="font-heading text-white uppercase tracking-wider mb-4">📋 Breakdown</h3>
                <div class="space-y-3">
                    @foreach([
                        ['label' => '🎯 Exact Scores', 'value' => $stats['exact_predictions'],   'color' => 'bg-green-500'],
                        ['label' => '✅ Correct Results', 'value' => $stats['correct_predictions'], 'color' => 'bg-yellow-500'],
                        ['label' => '❌ Wrong', 'value' => $stats['total_predictions'] - $stats['correct_predictions'], 'color' => 'bg-red-700'],
                    ] as $row)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">{{ $row['label'] }}</span>
                        <span class="font-heading text-white">{{ $row['value'] }}</span>
                    </div>
                    @endforeach
                </div>

                @if($stats['total_predictions'] > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Accuracy</span>
                        <span>{{ $stats['accuracy'] }}%</span>
                    </div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-fifa-red to-fifa-gold rounded-full transition-all duration-700"
                             style="width: {{ $stats['accuracy'] }}%"></div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection