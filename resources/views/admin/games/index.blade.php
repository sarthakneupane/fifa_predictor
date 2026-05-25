@extends('layouts.app')
@section('title', 'Manage Games – Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-4xl text-white tracking-wide">Manage Games</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $games->total() }} total games</p>
        </div>
        <a href="{{ route('admin.games.create') }}"
           class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-5 py-2.5 rounded-xl text-sm transition-colors">
            + New Game
        </a>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-fifa-border bg-white/[0.02]">
                        <th class="text-left px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">#</th>
                        <th class="text-left px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Game</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden md:table-cell">Date</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden sm:table-cell">Stage</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Score</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Status</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden lg:table-cell">Predictions</th>
                        <th class="text-right px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-fifa-border/30">
                    @foreach($games as $game)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-3 text-gray-600 text-xs">{{ $game->game_number ?? $game->id }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span>{{ $game->homeTeam->flag_display }}</span>
                                <span class="font-medium text-white">{{ $game->homeTeam->short_name }}</span>
                                <span class="text-gray-700 text-xs">vs</span>
                                <span class="font-medium text-white">{{ $game->awayTeam->short_name }}</span>
                                <span>{{ $game->awayTeam->flag_display }}</span>
                            </div>
                            @if($game->stadium)
                                <div class="text-gray-700 text-xs mt-0.5">📍 {{ $game->stadium }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-400 text-xs hidden md:table-cell tabular-nums">
                            {{ $game->game_date->format('d M Y') }}<br>
                            <span class="text-gray-700">{{ $game->game_date->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3 text-center hidden sm:table-cell">
                            <span class="text-xs px-2 py-0.5 rounded {{ $game->stage_badge_class }}">
                                {{ $game->stage }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-heading text-white tabular-nums">
                            @if($game->status !== 'upcoming')
                                {{ $game->home_score }}–{{ $game->away_score }}
                            @else
                                <span class="text-gray-700">–</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2.5 py-1 rounded-full font-heading uppercase tracking-wider
                                {{ $game->status === 'live'      ? 'bg-red-900/50 text-red-400 border border-red-800' :
                                   ($game->status === 'completed' ? 'bg-green-900/40 text-green-400 border border-green-800' :
                                                                      'bg-blue-900/30 text-blue-400 border border-blue-800') }}">
                                {{ ucfirst($game->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-400 hidden lg:table-cell">
                            {{ $game->predictions_count }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.games.edit', $game) }}"
                                   class="text-xs text-blue-400 hover:text-blue-200 border border-blue-900/50 hover:border-blue-700 px-2.5 py-1 rounded-lg transition-colors font-heading uppercase tracking-wider">
                                    Edit
                                </a>
                                @if($game->status === 'completed')
                                <form method="POST" action="{{ route('admin.games.calculate', $game) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs text-green-400 hover:text-green-200 border border-green-900/50 hover:border-green-700 px-2.5 py-1 rounded-lg transition-colors font-heading uppercase tracking-wider"
                                            title="Recalculate predictions">
                                        Calc
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.games.destroy', $game) }}" class="inline"
                                      onsubmit="return confirm('Delete this game and all its predictions?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-600 hover:text-red-400 border border-red-900/30 hover:border-red-700 px-2.5 py-1 rounded-lg transition-colors font-heading uppercase tracking-wider">
                                        Del
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($games->hasPages())
        <div class="px-6 py-5 border-t border-fifa-border">{{ $games->links() }}</div>
        @endif
    </div>

</div>
@endsection
