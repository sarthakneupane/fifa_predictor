@extends('layouts.app')

@section('title', 'My Predictions – FIFA 2026 Predictor')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-5xl text-white tracking-wide">My Predictions</h1>
            <p class="text-gray-500 text-sm mt-1">Your full prediction history</p>
        </div>
        <a href="{{ route('games.index') }}"
           class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-5 py-2.5 rounded-xl text-sm transition-colors">
            + Predict More
        </a>
    </div>

    {{-- Summary bar --}}
    @php
        $calcPredictions = $predictions->getCollection()->where('is_calculated', true);
        $totalPoints     = $calcPredictions->sum('points');
        $correct         = $calcPredictions->where('is_correct', true)->count();
        $exact           = $calcPredictions->where('is_exact', true)->count();
        $accuracy        = $calcPredictions->count() > 0 ? round(($correct / $calcPredictions->count()) * 100, 1) : 0;
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        @foreach([
            ['label' => 'Total Points',    'value' => $totalPoints, 'color' => 'text-fifa-gold',  'icon' => '⭐'],
            ['label' => 'Predictions',     'value' => $predictions->total(), 'color' => 'text-white',   'icon' => '🎯'],
            ['label' => 'Correct Results', 'value' => $correct,     'color' => 'text-green-400', 'icon' => '✅'],
            ['label' => 'Accuracy',        'value' => $accuracy.'%','color' => 'text-blue-400',  'icon' => '📊'],
        ] as $s)
        <div class="glass-card rounded-xl p-4 text-center">
            <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
            <div class="font-display text-2xl {{ $s['color'] }}">{{ $s['value'] }}</div>
            <div class="text-gray-600 text-xs font-heading uppercase tracking-wider mt-1">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Predictions table --}}
    @if($predictions->count() > 0)
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-fifa-border bg-white/[0.02]">
                        <th class="text-left px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Match</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Date</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Your Pick</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden sm:table-cell">Result</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-fifa-border/30">
                    @foreach($predictions as $pred)
                    @php $game = $pred->game; @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors">

                        {{-- game --}}
                        <td class="px-5 py-4">
                            <a href="{{ route('games.show', $game) }}" class="group">
                            
                                <div class="flex items-center gap-2">
                                    <span class="text-base">{{ $game->homeTeam->flag_display }}</span>
                                    <span class="text-gray-300 font-medium text-xs sm:text-sm group-hover:text-white transition-colors">
                                        {{ $game->homeTeam->short_name }}
                                    </span>
                                    <span class="text-gray-700 text-xs">vs</span>
                                    <span class="text-gray-300 font-medium text-xs sm:text-sm group-hover:text-white transition-colors">
                                        {{ $game->awayTeam->short_name }}
                                    </span>
                                    <span class="text-base">{{ $game->awayTeam->flag_display }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-gray-700 text-xs">{{ $game->stage }}</span>
                                    @if($game->group)
                                        <span class="text-gray-800 text-xs">· {{ $game->group->name }}</span>
                                    @endif
                                </div>
                            </a>
                        </td>

                        {{-- Date --}}
                        <td class="px-4 py-4 text-center text-gray-500 text-xs tabular-nums">
                            {{ $game->game_date->format('d M Y') }}
                            <br>
                            <span class="text-gray-700">{{ $game->game_date->format('H:i') }}</span>
                        </td>

                        {{-- Prediction --}}
                        <td class="px-4 py-4 text-center">
                            <span class="font-heading text-white text-base tabular-nums">
                                {{ $pred->home_score }}–{{ $pred->away_score }}
                            </span>
                        </td>

                        {{-- Actual result --}}
                        <td class="px-4 py-4 text-center hidden sm:table-cell">
                            @if($game->status === 'completed')
                                <span class="font-heading text-gray-300 text-base tabular-nums">
                                    {{ $game->home_score }}–{{ $game->away_score }}
                                </span>
                            @elseif($game->status === 'live')
                                <span class="text-red-400 text-xs font-heading uppercase tracking-wider flex items-center justify-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full pulse-red"></span> Live
                                </span>
                            @else
                                <span class="text-gray-700 text-xs">Upcoming</span>
                            @endif
                        </td>

                        {{-- Points --}}
                        <td class="px-4 py-4 text-center">
                            @if($pred->is_calculated)
                                <div class="inline-flex flex-col items-center gap-1">
                                    <span class="w-10 h-10 rounded-full flex items-center justify-center font-display text-lg
                                        {{ $pred->points === 3 ? 'bg-green-700 text-white' : ($pred->points === 1 ? 'bg-yellow-500 text-black' : 'bg-red-900/50 text-red-500') }}">
                                        +{{ $pred->points }}
                                    </span>
                                    <span class="text-xs text-gray-600 font-heading">
                                        @if($pred->is_exact) 🎯
                                        @elseif($pred->is_correct) ✅
                                        @else ❌
                                        @endif
                                    </span>
                                </div>
                            @elseif($game->status === 'upcoming')
                                <span class="text-gray-600 text-xs font-heading uppercase tracking-wider">Pending</span>
                            @elseif($game->status === 'live')
                                <span class="text-gray-600 text-xs font-heading uppercase tracking-wider">Live</span>
                            @else
                                <span class="text-yellow-700 text-xs font-heading uppercase tracking-wider">Calculating</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($predictions->hasPages())
        <div class="px-6 py-5 border-t border-fifa-border bg-white/[0.01]">
            {{ $predictions->links() }}
        </div>
        @endif
    </div>

    @else
    {{-- Empty state --}}
    <div class="glass-card rounded-2xl py-20 text-center">
        <div class="text-6xl mb-5">🎯</div>
        <h3 class="font-heading text-gray-400 uppercase tracking-wider text-lg">No predictions yet</h3>
        <p class="text-gray-600 text-sm mt-3 max-w-xs mx-auto">
            Start predicting game scores to earn points and climb the leaderboard!
        </p>
        <a href="{{ route('games.index') }}"
           class="mt-8 inline-block bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-7 py-3 rounded-xl text-sm transition-all hover:scale-[1.02]">
            ⚽ Browse games
        </a>
    </div>
    @endif

</div>

@endsection
