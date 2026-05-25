{{--
    Reusable game card partial.
    Variables: $game (FootballGame), $highlight (optional: 'live')
    $userPredictions: array of [game_id => prediction_id]
--}}
<div class="game-card glass-card rounded-2xl overflow-hidden {{ ($highlight ?? '') === 'live' ? 'border-red-500/40' : '' }}">
    {{-- Stage / Status Badge --}}
    <div class="flex items-center justify-between px-4 pt-3 pb-2 border-b border-fifa-border">
        <span class="text-xs font-heading uppercase tracking-wider px-2 py-0.5 rounded {{ $game->stage_badge_class }}">
            {{ $game->stage }}
            @if($game->group) · {{ $game->group->name }} @endif
        </span>
        <div class="flex items-center gap-2">
            @if($game->status === 'live')
                <span class="w-2 h-2 bg-red-500 rounded-full pulse-red"></span>
                <span class="text-red-400 text-xs font-heading uppercase tracking-wider">Live</span>
            @elseif($game->status === 'completed')
                <span class="text-green-400 text-xs font-heading uppercase tracking-wider">FT</span>
            @else
                <span class="text-gray-500 text-xs">{{ $game->game_date->format('d M · H:i') }}</span>
            @endif
        </div>
    </div>

    {{-- Teams & Score --}}
    <a href="{{ route('games.show', $game) }}" class="block px-4 py-5">
        <div class="flex items-center justify-between gap-4">
            {{-- Home Team --}}
            <div class="flex flex-col items-center gap-2 flex-1">
                <span class="text-4xl">{{ $game->homeTeam->flag_display }}</span>
                <span class="font-heading text-white text-sm uppercase tracking-wider text-center">{{ $game->homeTeam->short_name }}</span>
                <span class="text-gray-600 text-xs text-center hidden sm:block">{{ $game->homeTeam->name }}</span>
            </div>

            {{-- Score / VS --}}
            <div class="text-center min-w-20">
                @if($game->status === 'upcoming')
                    <div class="font-display text-gray-600 text-3xl">VS</div>
                    @if($game->status === 'upcoming' && $game->is_predictable)
                        <div class="text-xs font-heading text-gray-600 uppercase tracking-widest mt-1">Predict</div>
                    @endif
                @else
                    <div class="font-display text-3xl text-white">
                        {{ $game->home_score }} – {{ $game->away_score }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        @if($game->status === 'live') 🔴 In Progress
                        @else ✅ Full Time @endif
                    </div>
                @endif
            </div>

            {{-- Away Team --}}
            <div class="flex flex-col items-center gap-2 flex-1">
                <span class="text-4xl">{{ $game->awayTeam->flag_display }}</span>
                <span class="font-heading text-white text-sm uppercase tracking-wider text-center">{{ $game->awayTeam->short_name }}</span>
                <span class="text-gray-600 text-xs text-center hidden sm:block">{{ $game->awayTeam->name }}</span>
            </div>
        </div>
    </a>

    {{-- Countdown (upcoming only) --}}
    @if($game->status === 'upcoming' && $game->seconds_until_game > 0)
    <div class="px-4 pb-3 text-center">
        <div class="text-xs text-gray-600 mb-1 font-heading uppercase tracking-wider">Starts in</div>
        <div class="font-heading text-fifa-red text-sm flex items-center justify-center gap-1"
             data-countdown="{{ $game->game_date->toIso8601String() }}">
            Loading...
        </div>
    </div>
    @endif

    {{-- Prediction status / Quick predict button --}}
    @auth
        @php
            $predicted = isset($userPredictions[$game->id]);
        @endphp
        <div class="px-4 pb-4">
            @if($predicted)
                <div class="text-center text-xs text-green-400 font-medium bg-green-900/20 rounded-lg py-2">
                    ✅ Predicted
                    <a href="{{ route('games.show', $game) }}" class="ml-2 text-gray-500 hover:text-gray-300 underline">Edit</a>
                </div>
            @elseif($game->is_predictable)
                <a href="{{ route('games.show', $game) }}"
                   class="block text-center bg-fifa-red/20 hover:bg-fifa-red/30 text-fifa-red border border-fifa-red/30 text-xs font-heading uppercase tracking-wider py-2 rounded-lg transition-colors">
                    🎯 Predict Score
                </a>
            @endif
        </div>
    @endauth

    {{-- Stadium --}}
    @if($game->stadium)
    <div class="px-4 pb-3 text-center text-gray-700 text-xs">
        📍 {{ $game->stadium }}
    </div>
    @endif
</div>