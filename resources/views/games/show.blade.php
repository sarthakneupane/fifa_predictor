@extends('layouts.app')

@section('title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name . ' – FIFA 2026 Predictor')
@section('description', 'Predict the score for ' . $game->homeTeam->name . ' vs ' . $game->awayTeam->name . ' at the FIFA World Cup 2026.')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Breadcrumb ───────────────────────────────────────────────────── --}}
    <nav class="flex items-center gap-2 text-sm text-gray-600 mb-8">
        <a href="{{ route('home') }}" class="hover:text-gray-300 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('games.index') }}" class="hover:text-gray-300 transition-colors">Games</a>
        <span>/</span>
        <span class="text-gray-400">{{ $game->homeTeam->short_name }} vs {{ $game->awayTeam->short_name }}</span>
    </nav>

    {{-- ── Game Hero Card ──────────────────────────────────────────────── --}}
    <div class="glass-card rounded-2xl overflow-hidden mb-6">

        {{-- Top meta bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-fifa-border bg-white/[0.02]">
            <div class="flex items-center gap-3">
                <span class="text-xs font-heading uppercase tracking-wider px-3 py-1 rounded-full {{ $game->stage_badge_class }}">
                    {{ $game->stage }}
                </span>
                @if($game->group)
                    <span class="text-xs text-gray-500 font-heading uppercase tracking-wider">{{ $game->group->name }}</span>
                @endif
                @if($game->game_number)
                    <span class="text-xs text-gray-700">{{ $game->game_number }}</span>
                @endif
            </div>

            <div class="flex items-center gap-4 text-sm">
                @if($game->status === 'live')
                    <span class="flex items-center gap-2 text-red-400 font-heading uppercase tracking-widest text-xs">
                        <span class="w-2.5 h-2.5 bg-red-500 rounded-full pulse-red"></span> LIVE
                    </span>
                @elseif($game->status === 'completed')
                    <span class="text-green-400 font-heading uppercase tracking-widest text-xs">✅ Full Time</span>
                @else
                    <span class="text-gray-500 text-xs">Upcoming</span>
                @endif
                <span class="text-gray-500 text-xs">
                    📅 {{ $game->game_date->format('D, d M Y · H:i') }} UTC
                </span>
            </div>
        </div>

        {{-- Teams & Score panel --}}
        <div class="px-6 py-12">
            <div class="flex items-center justify-between gap-4">

                {{-- Home Team --}}
                <div class="flex flex-col items-center gap-4 flex-1 text-center">
                    <div class="text-7xl sm:text-8xl leading-none select-none">{{ $game->homeTeam->flag_display }}</div>
                    <div>
                        <div class="font-display text-white text-3xl sm:text-4xl tracking-wide">
                            {{ $game->homeTeam->short_name }}
                        </div>
                        <div class="text-gray-400 text-sm mt-1">{{ $game->homeTeam->name }}</div>
                        <div class="text-gray-700 text-xs mt-0.5">{{ $game->homeTeam->confederation ?? '' }}</div>
                    </div>
                    @if($game->status === 'completed' && $game->winner === 'home')
                        <span class="text-xs font-heading uppercase tracking-widest text-yellow-400 bg-yellow-400/10 px-3 py-1 rounded-full border border-yellow-400/20">
                            🏆 Winner
                        </span>
                    @endif
                </div>

                {{-- Score / VS --}}
                <div class="text-center flex-shrink-0 px-4">
                    @if($game->status === 'upcoming')
                        <div class="font-display text-gray-600 text-5xl sm:text-7xl leading-none mb-3">VS</div>
                        @if($game->is_predictable && $game->seconds_until_game > 0)
                            <div class="mt-2">
                                <div class="text-gray-600 text-xs font-heading uppercase tracking-widest mb-2">Kick-off in</div>
                                <div class="font-heading text-fifa-red text-base sm:text-xl tracking-wide"
                                     data-countdown="{{ $game->game_date->toIso8601String() }}">–</div>
                            </div>
                        @endif
                    @elseif($game->status === 'live')
                        <div class="font-display text-white text-6xl sm:text-8xl leading-none">
                            {{ $game->home_score }} <span class="text-red-500">:</span> {{ $game->away_score }}
                        </div>
                        <div class="mt-2 flex items-center justify-center gap-2">
                            <span class="w-2 h-2 bg-red-500 rounded-full pulse-red"></span>
                            <span class="text-red-400 text-xs font-heading uppercase tracking-widest">In Progress</span>
                        </div>
                    @else
                        <div class="font-display text-white text-6xl sm:text-8xl leading-none">
                            {{ $game->home_score }}<span class="text-gray-600 mx-1">–</span>{{ $game->away_score }}
                        </div>
                        <div class="mt-2 text-gray-500 text-xs font-heading uppercase tracking-widest">
                            @if($game->winner === 'draw') Draw
                            @elseif($game->winner === 'home') {{ $game->homeTeam->short_name }} Win
                            @elseif($game->winner === 'away') {{ $game->awayTeam->short_name }} Win
                            @endif
                        </div>
                    @endif

                    @if($game->stadium)
                        <div class="mt-4 text-gray-700 text-xs flex items-center justify-center gap-1">
                            <span>📍</span>
                            <span>{{ $game->stadium }}</span>
                        </div>
                    @endif
                    @if($game->city)
                        <div class="text-gray-700 text-xs mt-0.5">{{ $game->city }}</div>
                    @endif
                </div>

                {{-- Away Team --}}
                <div class="flex flex-col items-center gap-4 flex-1 text-center">
                    <div class="text-7xl sm:text-8xl leading-none select-none">{{ $game->awayTeam->flag_display }}</div>
                    <div>
                        <div class="font-display text-white text-3xl sm:text-4xl tracking-wide">
                            {{ $game->awayTeam->short_name }}
                        </div>
                        <div class="text-gray-400 text-sm mt-1">{{ $game->awayTeam->name }}</div>
                        <div class="text-gray-700 text-xs mt-0.5">{{ $game->awayTeam->confederation ?? '' }}</div>
                    </div>
                    @if($game->status === 'completed' && $game->winner === 'away')
                        <span class="text-xs font-heading uppercase tracking-widest text-yellow-400 bg-yellow-400/10 px-3 py-1 rounded-full border border-yellow-400/20">
                            🏆 Winner
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Two-column layout ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ── Left: Prediction + Community ────────────────────────────── --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- ── PREDICTION FORM (Auth + Upcoming) ──────────────────── --}}
            @auth
                @if($game->is_predictable)
                <div class="glass-card rounded-2xl p-6" id="prediction-section">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="font-heading text-white text-xl uppercase tracking-wider">
                            🎯 {{ $userPrediction ? 'Update Prediction' : 'Your Prediction' }}
                        </h2>
                        @if($userPrediction)
                            <span class="text-xs text-green-400 bg-green-900/30 border border-green-800/40 px-2.5 py-1 rounded-full font-heading uppercase tracking-wider">
                                ✅ Predicted
                            </span>
                        @endif
                    </div>

                    <form id="prediction-form" novalidate>
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->id }}">

                        {{-- Score inputs --}}
                        <div class="flex items-center justify-center gap-4 sm:gap-8">

                            {{-- Home Score --}}
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">{{ $game->homeTeam->flag_display }}</span>
                                    <span class="font-heading text-white text-sm uppercase tracking-wider">{{ $game->homeTeam->short_name }}</span>
                                </div>
                                <div class="relative">
                                    <button type="button" class="score-up absolute -top-3 left-1/2 -translate-x-1/2 w-7 h-7 flex items-center justify-center text-gray-500 hover:text-white transition-colors text-lg" data-target="home_score">▲</button>
                                    <input type="number" name="home_score" id="home_score" min="0" max="20"
                                           value="{{ $userPrediction?->home_score ?? '' }}"
                                           placeholder="0"
                                           class="w-24 h-24 text-center text-5xl font-display bg-white/5 border-2 border-white/10 focus:border-fifa-red rounded-2xl text-white outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" class="score-down absolute -bottom-3 left-1/2 -translate-x-1/2 w-7 h-7 flex items-center justify-center text-gray-500 hover:text-white transition-colors text-lg" data-target="home_score">▼</button>
                                </div>
                            </div>

                            <div class="font-display text-gray-600 text-5xl mt-4">–</div>

                            {{-- Away Score --}}
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-heading text-white text-sm uppercase tracking-wider">{{ $game->awayTeam->short_name }}</span>
                                    <span class="text-2xl">{{ $game->awayTeam->flag_display }}</span>
                                </div>
                                <div class="relative">
                                    <button type="button" class="score-up absolute -top-3 left-1/2 -translate-x-1/2 w-7 h-7 flex items-center justify-center text-gray-500 hover:text-white transition-colors text-lg" data-target="away_score">▲</button>
                                    <input type="number" name="away_score" id="away_score" min="0" max="20"
                                           value="{{ $userPrediction?->away_score ?? '' }}"
                                           placeholder="0"
                                           class="w-24 h-24 text-center text-5xl font-display bg-white/5 border-2 border-white/10 focus:border-fifa-red rounded-2xl text-white outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" class="score-down absolute -bottom-3 left-1/2 -translate-x-1/2 w-7 h-7 flex items-center justify-center text-gray-500 hover:text-white transition-colors text-lg" data-target="away_score">▼</button>
                                </div>
                            </div>
                        </div>

                        {{-- Quick picks --}}
                        <div class="mt-10">
                            <p class="text-gray-600 text-xs font-heading uppercase tracking-widest mb-3 text-center">Quick picks</p>
                            <div class="flex flex-wrap gap-2 justify-center">
                                @foreach([[0,0],[1,0],[1,1],[2,0],[2,1],[2,2],[3,0],[3,1],[3,2],[4,0],[0,1],[0,2],[0,3],[1,2],[1,3]] as $sc)
                                <button type="button"
                                        class="quick-score px-3 py-1.5 bg-white/5 hover:bg-fifa-red/20 border border-white/10 hover:border-fifa-red/40 text-gray-400 hover:text-white rounded-lg text-xs font-heading transition-all"
                                        data-home="{{ $sc[0] }}" data-away="{{ $sc[1] }}">
                                    {{ $sc[0] }}–{{ $sc[1] }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Deadline notice --}}
                        <p class="text-center text-gray-700 text-xs mt-4">
                            Deadline: {{ $game->prediction_deadline->format('d M Y · H:i') }} UTC
                        </p>

                        {{-- Error --}}
                        <div id="form-errors" class="mt-3 text-red-400 text-sm text-center hidden"></div>

                        {{-- Actions --}}
                        <div class="mt-5 flex items-center gap-3">
                            <button type="submit" id="submit-btn"
                                    class="flex-1 bg-fifa-red hover:bg-red-700 disabled:opacity-60 text-white font-heading uppercase tracking-wider py-3.5 rounded-xl transition-all hover:scale-[1.02] flex items-center justify-center gap-2 text-sm">
                                <span id="btn-text">{{ $userPrediction ? '✏️ Update Prediction' : '🎯 Submit Prediction' }}</span>
                                <div id="btn-spinner" class="spinner w-4 h-4 hidden"></div>
                            </button>

                            @if($userPrediction)
                            <button type="button" id="delete-btn"
                                    data-prediction-id="{{ $userPrediction->id }}"
                                    class="px-4 py-3.5 border border-red-900 text-red-600 hover:bg-red-900/20 hover:text-red-400 rounded-xl font-heading uppercase tracking-wider text-xs transition-colors"
                                    title="Remove prediction">
                                🗑️
                            </button>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- ── PREDICTION RESULT (Completed + Calculated) ───────── --}}
                @elseif($userPrediction && $game->status === 'completed' && $userPrediction->is_calculated)
                <div class="glass-card rounded-2xl p-6 border-l-4
                    {{ $userPrediction->points === 3 ? 'border-l-green-500' : ($userPrediction->points === 1 ? 'border-l-yellow-500' : 'border-l-red-700') }}">
                    <h2 class="font-heading text-white text-xl uppercase tracking-wider mb-5">Your Result</h2>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-gray-600 text-xs font-heading uppercase tracking-wider mb-2">Your Prediction</p>
                            <div class="font-display text-3xl text-white">{{ $userPrediction->home_score }} – {{ $userPrediction->away_score }}</div>
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-display
                                {{ $userPrediction->points === 3 ? 'bg-green-600 text-white' : ($userPrediction->points === 1 ? 'bg-yellow-500 text-black' : 'bg-red-900/50 text-red-400') }}">
                                +{{ $userPrediction->points }}
                            </div>
                            <p class="text-xs text-gray-500 mt-2 font-heading uppercase tracking-wider">
                                {{ $userPrediction->points_label }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-heading uppercase tracking-wider mb-2">Actual Score</p>
                            <div class="font-display text-3xl text-white">{{ $game->home_score }} – {{ $game->away_score }}</div>
                        </div>
                    </div>
                </div>

                {{-- ── PENDING result (completed but not calculated yet) ─── --}}
                @elseif($userPrediction && $game->status === 'completed' && !$userPrediction->is_calculated)
                <div class="glass-card rounded-2xl p-5 border border-yellow-800/30 text-center">
                    <p class="text-yellow-500 text-sm font-heading uppercase tracking-wider">
                        ⏳ You predicted {{ $userPrediction->home_score }}–{{ $userPrediction->away_score }} · Points being calculated…
                    </p>
                </div>

                {{-- ── DEADLINE PASSED ──────────────────────────────────── --}}
                @elseif($game->status === 'upcoming' && !$game->is_predictable)
                <div class="glass-card rounded-2xl p-5 border border-yellow-900/30 text-center">
                    <p class="text-yellow-600 text-sm">⏰ Prediction window closed for this game.</p>
                </div>
                @endif

            {{-- ── NOT LOGGED IN ────────────────────────────────────────── --}}
            @else
                @if($game->is_predictable)
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="text-5xl mb-4">🎯</div>
                    <h2 class="font-heading text-white text-xl uppercase tracking-wider mb-2">Make Your Prediction</h2>
                    <p class="text-gray-500 text-sm mb-6">Join free and predict every World Cup game to climb the leaderboard.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('register') }}"
                           class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-6 py-3 rounded-xl transition-all hover:scale-[1.02] text-sm">
                            Sign Up Free
                        </a>
                        <a href="{{ route('login') }}"
                           class="border border-white/20 text-white hover:border-white/40 font-heading uppercase tracking-wider px-6 py-3 rounded-xl transition-colors text-sm">
                            Log In
                        </a>
                    </div>
                </div>
                @endif
            @endauth

            {{-- ── Community Prediction Stats ───────────────────────────── --}}
            <div class="glass-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-heading text-white text-lg uppercase tracking-wider">🌍 Community Predictions</h2>
                    <span class="text-gray-600 text-xs font-heading uppercase tracking-wider">
                        {{ $communityStats['total'] }} {{ Str::plural('prediction', $communityStats['total']) }}
                    </span>
                </div>

                @if($communityStats['total'] > 0)
                    <div class="space-y-4">
                        @foreach([
                            ['label' => $game->homeTeam->name . ' Win', 'pct' => $communityStats['home'], 'color' => 'from-blue-700 to-blue-500',   'bg' => 'bg-blue-900/20'],
                            ['label' => 'Draw',                           'pct' => $communityStats['draw'], 'color' => 'from-gray-600 to-gray-500',   'bg' => 'bg-gray-800/40'],
                            ['label' => $game->awayTeam->name . ' Win', 'pct' => $communityStats['away'], 'color' => 'from-red-700 to-red-500',    'bg' => 'bg-red-900/20'],
                        ] as $stat)
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-300 font-medium">{{ $stat['label'] }}</span>
                                <span class="text-white font-heading font-bold text-base">{{ $stat['pct'] }}%</span>
                            </div>
                            <div class="h-3 {{ $stat['bg'] }} rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $stat['color'] }} rounded-full transition-all duration-1000 ease-out"
                                     style="width: {{ $stat['pct'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Visual bar split --}}
                    <div class="mt-6 flex rounded-full overflow-hidden h-5 gap-px">
                        @if($communityStats['home'] > 0)
                        <div class="bg-blue-600 transition-all duration-700" style="width:{{ $communityStats['home'] }}%" title="{{ $game->homeTeam->short_name }} {{ $communityStats['home'] }}%"></div>
                        @endif
                        @if($communityStats['draw'] > 0)
                        <div class="bg-gray-600 transition-all duration-700" style="width:{{ $communityStats['draw'] }}%" title="Draw {{ $communityStats['draw'] }}%"></div>
                        @endif
                        @if($communityStats['away'] > 0)
                        <div class="bg-red-600 transition-all duration-700" style="width:{{ $communityStats['away'] }}%" title="{{ $game->awayTeam->short_name }} {{ $communityStats['away'] }}%"></div>
                        @endif
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 mt-2 font-heading uppercase tracking-wider">
                        <span>{{ $game->homeTeam->short_name }}</span>
                        <span>Draw</span>
                        <span>{{ $game->awayTeam->short_name }}</span>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-3">🗳️</div>
                        <p class="text-gray-600 text-sm">No predictions yet – be the first!</p>
                    </div>
                @endif
            </div>

            {{-- ── Top Correct Predictors (completed games only) ──────── --}}
            @if($game->status === 'completed' && $correctPredictors->count() > 0)
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-fifa-border bg-green-950/20">
                    <h2 class="font-heading text-white text-lg uppercase tracking-wider flex items-center gap-2">
                        🏆 Correct Predictors
                        <span class="text-green-400 text-sm normal-case font-body font-normal ml-1">
                            — got it right!
                        </span>
                    </h2>
                </div>
                <div class="divide-y divide-fifa-border/40">
                    @foreach($correctPredictors as $i => $pred)
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-white/2 transition-colors">
                        {{-- Rank medal --}}
                        <div class="w-8 text-center flex-shrink-0">
                            @if($i === 0) <span class="text-xl">🥇</span>
                            @elseif($i === 1) <span class="text-xl">🥈</span>
                            @elseif($i === 2) <span class="text-xl">🥉</span>
                            @else <span class="text-gray-600 text-sm font-heading">{{ $i + 1 }}</span>
                            @endif
                        </div>

                        {{-- Avatar + Name --}}
                        <img src="{{ $pred->user->avatar_url }}" class="w-9 h-9 rounded-full object-cover border border-fifa-border flex-shrink-0" alt="">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-white text-sm truncate">
                                {{ $pred->user->name }}
                                @if(auth()->id() === $pred->user_id)
                                    <span class="text-fifa-red text-xs ml-1">(you)</span>
                                @endif
                            </div>
                        </div>

                        {{-- Predicted score --}}
                        <div class="text-center flex-shrink-0">
                            <div class="font-heading text-sm text-gray-300">{{ $pred->home_score }}–{{ $pred->away_score }}</div>
                            <div class="text-gray-700 text-xs">predicted</div>
                        </div>

                        {{-- Points badge --}}
                        <span class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-sm font-display font-bold
                            {{ $pred->points === 3 ? 'bg-green-600 text-white' : 'bg-yellow-500 text-black' }}">
                            +{{ $pred->points }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- /Left col --}}

        {{-- ── Right: Game Info Sidebar ────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Game Info Card --}}
            <div class="glass-card rounded-2xl p-5">
                <h3 class="font-heading text-white uppercase tracking-wider text-sm mb-4">📋 Game Info</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Date</dt>
                        <dd class="text-gray-300 text-right">{{ $game->game_date->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Kick-off</dt>
                        <dd class="text-gray-300">{{ $game->game_date->format('H:i') }} UTC</dd>
                    </div>
                    @if($game->stadium)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Stadium</dt>
                        <dd class="text-gray-300 text-right">{{ $game->stadium }}</dd>
                    </div>
                    @endif
                    @if($game->city)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">City</dt>
                        <dd class="text-gray-300">{{ $game->city }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Stage</dt>
                        <dd class="text-gray-300">{{ $game->stage }}</dd>
                    </div>
                    @if($game->group)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Group</dt>
                        <dd class="text-gray-300">{{ $game->group->name }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Status</dt>
                        <dd>
                            @if($game->status === 'upcoming')
                                <span class="text-blue-400">Upcoming</span>
                            @elseif($game->status === 'live')
                                <span class="text-red-400 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full pulse-red"></span> Live
                                </span>
                            @else
                                <span class="text-green-400">Completed</span>
                            @endif
                        </dd>
                    </div>
                    @if($game->is_predictable)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-600">Deadline</dt>
                        <dd class="text-yellow-500 text-right text-xs">{{ $game->prediction_deadline->format('d M · H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Home Team Card --}}
            <div class="glass-card rounded-2xl p-5">
                <h3 class="font-heading text-white uppercase tracking-wider text-sm mb-4">
                    {{ $game->homeTeam->flag_display }} {{ $game->homeTeam->name }}
                </h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Code</dt>
                        <dd class="text-gray-300 font-heading">{{ $game->homeTeam->short_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Confederation</dt>
                        <dd class="text-gray-300">{{ $game->homeTeam->confederation ?? '–' }}</dd>
                    </div>
                    @if($game->homeTeam->group)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Group</dt>
                        <dd class="text-gray-300">{{ $game->homeTeam->group->name }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Away Team Card --}}
            <div class="glass-card rounded-2xl p-5">
                <h3 class="font-heading text-white uppercase tracking-wider text-sm mb-4">
                    {{ $game->awayTeam->flag_display }} {{ $game->awayTeam->name }}
                </h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Code</dt>
                        <dd class="text-gray-300 font-heading">{{ $game->awayTeam->short_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Confederation</dt>
                        <dd class="text-gray-300">{{ $game->awayTeam->confederation ?? '–' }}</dd>
                    </div>
                    @if($game->awayTeam->group)
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Group</dt>
                        <dd class="text-gray-300">{{ $game->awayTeam->group->name }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Scoring guide --}}
            <div class="glass-card rounded-2xl p-5">
                <h3 class="font-heading text-white uppercase tracking-wider text-sm mb-4">🏅 Points Guide</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-7 flex-shrink-0 bg-green-700 text-white text-xs font-display flex items-center justify-center rounded">+3</span>
                        <div>
                            <div class="text-gray-300 text-xs font-medium">Exact Score</div>
                            <div class="text-gray-600 text-xs">e.g. predicted 2–1, actual 2–1</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-7 flex-shrink-0 bg-yellow-600 text-black text-xs font-display flex items-center justify-center rounded">+1</span>
                        <div>
                            <div class="text-gray-300 text-xs font-medium">Correct Result</div>
                            <div class="text-gray-600 text-xs">Right winner or draw, wrong score</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-7 flex-shrink-0 bg-red-900/60 text-red-400 text-xs font-display flex items-center justify-center rounded">+0</span>
                        <div>
                            <div class="text-gray-300 text-xs font-medium">Wrong Prediction</div>
                            <div class="text-gray-600 text-xs">Incorrect result</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation between games --}}
            <div class="flex gap-3">
                <a href="{{ route('games.index') }}"
                   class="flex-1 text-center border border-white/10 hover:border-white/30 text-gray-400 hover:text-white text-sm font-heading uppercase tracking-wider py-3 rounded-xl transition-colors">
                    ← All Games
                </a>
            </div>

        </div>{{-- /Right col --}}
    </div>{{-- /Grid --}}

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // ── Score stepper buttons ──────────────────────────────────────────────
    $(document).on('click', '.score-up, .score-down', function (e) {
        e.preventDefault();
        const target = $(this).data('target');
        const $input = $('#' + target);
        let val = parseInt($input.val()) || 0;

        if ($(this).hasClass('score-up')) {
            val = Math.min(val + 1, 20);
        } else {
            val = Math.max(val - 1, 0);
        }
        $input.val(val);
        $input.trigger('change');
    });

    // ── Quick score picker ─────────────────────────────────────────────────
    $('.quick-score').on('click', function () {
        $('#home_score').val($(this).data('home'));
        $('#away_score').val($(this).data('away'));
        $('.quick-score').removeClass('!border-fifa-red text-white !bg-red-900/30');
        $(this).addClass('!border-fifa-red text-white !bg-red-900/30');
    });

    // ── Highlight active quick pick on load (if prediction exists) ─────────
    const existingHome = parseInt($('#home_score').val());
    const existingAway = parseInt($('#away_score').val());
    if (!isNaN(existingHome) && !isNaN(existingAway)) {
        $('.quick-score').each(function () {
            if ($(this).data('home') == existingHome && $(this).data('away') == existingAway) {
                $(this).addClass('!border-fifa-red text-white !bg-red-900/30');
            }
        });
    }

    // ── Score input: keep quick-pick highlight in sync ─────────────────────
    $('#home_score, #away_score').on('change input', function () {
        const h = parseInt($('#home_score').val());
        const a = parseInt($('#away_score').val());
        $('.quick-score').removeClass('!border-fifa-red text-white !bg-red-900/30');
        $('.quick-score').each(function () {
            if ($(this).data('home') == h && $(this).data('away') == a) {
                $(this).addClass('!border-fifa-red text-white !bg-red-900/30');
            }
        });
    });

    // ── Prediction AJAX submit ─────────────────────────────────────────────
    $('#prediction-form').on('submit', function (e) {
        e.preventDefault();

        const homeScore = parseInt($('#home_score').val());
        const awayScore = parseInt($('#away_score').val());

        if (isNaN(homeScore) || isNaN(awayScore) || homeScore < 0 || awayScore < 0) {
            $('#form-errors').text('Please enter valid scores (0 or above) for both teams.').removeClass('hidden');
            return;
        }

        $('#form-errors').addClass('hidden');
        $('#btn-spinner').removeClass('hidden');
        $('#btn-text').text('Saving…');
        $('#submit-btn').prop('disabled', true);

        $.ajax({
            url:    '{{ route("predictions.store") }}',
            method: 'POST',
            data: {
                game_id:   $('input[name="game_id"]').val(),
                home_score: homeScore,
                away_score: awayScore,
            },
            success: function (res) {
                showToast(res.message, res.success ? 'success' : 'error');
                if (res.success) {
                    $('#btn-text').text('✏️ Update Prediction');
                    // Add/update delete button
                    if ($('#delete-btn').length === 0 && res.prediction) {
                        const deleteBtn = `<button type="button" id="delete-btn"
                            data-prediction-id="${res.prediction.id}"
                            class="px-4 py-3.5 border border-red-900 text-red-600 hover:bg-red-900/20 hover:text-red-400 rounded-xl font-heading uppercase tracking-wider text-xs transition-colors"
                            title="Remove prediction">🗑️</button>`;
                        $('#submit-btn').after(deleteBtn);
                        bindDeleteBtn();
                    }
                }
                $('#submit-btn').prop('disabled', false);
                $('#btn-spinner').addClass('hidden');
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                const msg    = errors
                    ? Object.values(errors).flat().join(' ')
                    : (xhr.responseJSON?.message || 'Something went wrong.');
                $('#form-errors').text(msg).removeClass('hidden');
                $('#btn-text').text('🎯 Submit Prediction');
                $('#submit-btn').prop('disabled', false);
                $('#btn-spinner').addClass('hidden');
            }
        });
    });

    // ── Delete prediction ──────────────────────────────────────────────────
    function bindDeleteBtn() {
        $(document).off('click', '#delete-btn').on('click', '#delete-btn', function () {
            if (!confirm('Remove your prediction for this game?')) return;
            const pid = $(this).data('prediction-id');

            $.ajax({
                url:    '/predictions/' + pid,
                method: 'DELETE',
                success: function (res) {
                    showToast(res.message, 'info');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function (xhr) {
                    showToast(xhr.responseJSON?.message || 'Could not remove prediction.', 'error');
                }
            });
        });
    }

    bindDeleteBtn();
});
</script>
@endpush
