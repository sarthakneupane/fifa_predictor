@extends('layouts.app')

@section('title', $match->homeTeam->name . ' vs ' . $match->awayTeam->name . ' – FIFA 2026 Predictor')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back --}}
    <a href="{{ route('matches.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-white text-sm mb-8 transition-colors">
        ← Back to Matches
    </a>

    {{-- Match Hero Card --}}
    <div class="glass-card rounded-2xl overflow-hidden mb-6">

        {{-- Stage + Date Bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-b border-fifa-border gap-3">
            <span class="text-xs font-heading uppercase tracking-wider px-3 py-1 rounded-full {{ $match->stage_badge_class }}">
                {{ $match->stage }}
                @if($match->group) · {{ $match->group->name }} @endif
            </span>
            <div class="text-gray-400 text-sm">
                📅 {{ $match->match_date->format('l, d F Y · H:i') }} UTC
            </div>
            @if($match->status === 'live')
                <span class="flex items-center gap-2 text-red-400 text-sm font-heading uppercase tracking-wider">
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full pulse-red"></span> Live
                </span>
            @elseif($match->status === 'completed')
                <span class="text-green-400 text-sm font-heading uppercase tracking-wider">✅ Full Time</span>
            @endif
        </div>

        {{-- Teams & Score --}}
        <div class="px-6 py-10">
            <div class="flex items-center justify-between gap-8">
                {{-- Home Team --}}
                <div class="flex flex-col items-center gap-3 flex-1">
                    <span class="text-7xl">{{ $match->homeTeam->flag_display }}</span>
                    <div class="text-center">
                        <div class="font-display text-white text-3xl tracking-wide">{{ $match->homeTeam->short_name }}</div>
                        <div class="text-gray-500 text-sm">{{ $match->homeTeam->name }}</div>
                        <div class="text-gray-700 text-xs mt-1">{{ $match->homeTeam->group->name ?? '' }}</div>
                    </div>
                </div>

                {{-- Score --}}
                <div class="text-center">
                    @if($match->status === 'upcoming')
                        <div class="font-display text-gray-600 text-5xl mb-2">VS</div>
                        @if($match->seconds_until_match > 0)
                        <div>
                            <div class="text-gray-600 text-xs font-heading uppercase tracking-wider mb-1">Kick-off in</div>
                            <div class="font-heading text-fifa-red text-lg" data-countdown="{{ $match->match_date->toIso8601String() }}">–</div>
                        </div>
                        @endif
                    @else
                        <div class="font-display text-white text-7xl">
                            {{ $match->home_score }} – {{ $match->away_score }}
                        </div>
                        @if($match->winner === 'home')
                            <div class="text-gray-500 text-xs mt-2 font-heading uppercase tracking-wider">{{ $match->homeTeam->name }} wins</div>
                        @elseif($match->winner === 'away')
                            <div class="text-gray-500 text-xs mt-2 font-heading uppercase tracking-wider">{{ $match->awayTeam->name }} wins</div>
                        @else
                            <div class="text-gray-500 text-xs mt-2 font-heading uppercase tracking-wider">Draw</div>
                        @endif
                    @endif
                    @if($match->stadium)
                        <div class="text-gray-700 text-xs mt-4">📍 {{ $match->stadium }}</div>
                    @endif
                </div>

                {{-- Away Team --}}
                <div class="flex flex-col items-center gap-3 flex-1">
                    <span class="text-7xl">{{ $match->awayTeam->flag_display }}</span>
                    <div class="text-center">
                        <div class="font-display text-white text-3xl tracking-wide">{{ $match->awayTeam->short_name }}</div>
                        <div class="text-gray-500 text-sm">{{ $match->awayTeam->name }}</div>
                        <div class="text-gray-700 text-xs mt-1">{{ $match->awayTeam->group->name ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Prediction Form (AJAX) ───────────────────────────────────────── --}}
    @auth
        @if($match->is_predictable)
        <div class="glass-card rounded-2xl p-6 mb-6" id="prediction-section">
            <h3 class="font-heading text-white text-xl uppercase tracking-wider mb-6">
                🎯 {{ $userPrediction ? 'Update Your Prediction' : 'Submit Your Prediction' }}
            </h3>

            <form id="prediction-form" novalidate>
                @csrf
                <input type="hidden" name="match_id" value="{{ $match->id }}">

                <div class="flex items-center gap-6">
                    {{-- Home Score Input --}}
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">{{ $match->homeTeam->flag_display }}</span>
                            <span class="font-heading text-white uppercase text-sm">{{ $match->homeTeam->short_name }}</span>
                        </div>
                        <input type="number" name="home_score" id="home_score" min="0" max="20"
                               value="{{ $userPrediction?->home_score ?? '' }}"
                               placeholder="0"
                               class="w-24 h-20 text-center text-4xl font-display bg-white/5 border-2 border-white/10 focus:border-fifa-red rounded-xl text-white outline-none transition-colors appearance-none">
                    </div>

                    {{-- Divider --}}
                    <div class="text-gray-600 font-display text-4xl">–</div>

                    {{-- Away Score Input --}}
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-heading text-white uppercase text-sm">{{ $match->awayTeam->short_name }}</span>
                            <span class="text-2xl">{{ $match->awayTeam->flag_display }}</span>
                        </div>
                        <input type="number" name="away_score" id="away_score" min="0" max="20"
                               value="{{ $userPrediction?->away_score ?? '' }}"
                               placeholder="0"
                               class="w-24 h-20 text-center text-4xl font-display bg-white/5 border-2 border-white/10 focus:border-fifa-red rounded-xl text-white outline-none transition-colors appearance-none">
                    </div>
                </div>

                {{-- Quick score picker --}}
                <div class="mt-5">
                    <p class="text-gray-600 text-xs font-heading uppercase tracking-wider mb-3 text-center">Quick picks</p>
                    <div class="flex flex-wrap gap-2 justify-center">
                        @foreach([[1,0],[2,0],[2,1],[3,0],[3,1],[3,2],[1,1],[2,2],[0,0]] as $score)
                        <button type="button" class="quick-score px-3 py-1.5 bg-white/5 hover:bg-fifa-red/20 border border-white/10 hover:border-fifa-red/40 text-gray-400 hover:text-white rounded-lg text-sm font-heading transition-all"
                                data-home="{{ $score[0] }}" data-away="{{ $score[1] }}">
                            {{ $score[0] }}–{{ $score[1] }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Submit button --}}
                <div class="mt-6 flex items-center gap-4">
                    <button type="submit" id="submit-btn"
                            class="flex-1 bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider py-3 rounded-xl transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                        <span id="btn-text">{{ $userPrediction ? 'Update Prediction' : 'Submit Prediction' }}</span>
                        <div id="btn-spinner" class="spinner w-5 h-5 hidden"></div>
                    </button>

                    @if($userPrediction)
                    <button type="button" id="delete-btn"
                            class="px-4 py-3 border border-red-800 text-red-500 hover:bg-red-900/20 rounded-xl font-heading uppercase tracking-wider text-sm transition-colors"
                            data-prediction-id="{{ $userPrediction->id }}">
                        🗑️ Remove
                    </button>
                    @endif
                </div>

                {{-- Error area --}}
                <div id="form-errors" class="mt-4 text-red-400 text-sm hidden"></div>
            </form>
        </div>
        @elseif($match->status === 'upcoming')
        <div class="glass-card rounded-xl p-5 mb-6 text-center">
            <p class="text-yellow-500 text-sm">⏰ Prediction deadline has passed for this match.</p>
        </div>
        @endif

        {{-- User's prediction result (completed match) --}}
        @if($userPrediction && $match->status === 'completed' && $userPrediction->is_calculated)
        <div class="glass-card rounded-2xl p-6 mb-6 border {{ $userPrediction->points === 3 ? 'border-green-500/40' : ($userPrediction->points === 1 ? 'border-yellow-500/40' : 'border-red-500/20') }}">
            <h3 class="font-heading text-white text-xl uppercase tracking-wider mb-4">Your Result</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Your prediction</p>
                    <p class="font-display text-3xl text-white">{{ $userPrediction->home_score }} – {{ $userPrediction->away_score }}</p>
                </div>
                <div class="text-center">
                    <span class="inline-flex items-center justify-center w-16 h-16 rounded-full text-2xl font-display
                        {{ $userPrediction->points === 3 ? 'bg-green-600 text-white' : ($userPrediction->points === 1 ? 'bg-yellow-500 text-black' : 'bg-red-700/40 text-red-400') }}">
                        +{{ $userPrediction->points }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1 font-heading uppercase tracking-wider">{{ $userPrediction->points_label }}</p>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="glass-card rounded-2xl p-8 mb-6 text-center">
            <p class="text-gray-400 mb-4">Log in to predict this match and earn points</p>
            <a href="{{ route('login') }}" class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-6 py-3 rounded-xl transition-colors inline-block">
                Log In to Predict
            </a>
        </div>
    @endauth

    {{-- ── Community Stats ─────────────────────────────────────────────── --}}
    @if($communityStats['total'] > 0)
    <div class="glass-card rounded-2xl p-6">
        <h3 class="font-heading text-white text-lg uppercase tracking-wider mb-4">
            Community Predictions <span class="text-gray-600 text-sm ml-2 normal-case font-body">{{ $communityStats['total'] }} total</span>
        </h3>
        <div class="space-y-3">
            @foreach([
                ['label' => $match->homeTeam->short_name . ' Win', 'pct' => $communityStats['home'], 'color' => 'bg-blue-600'],
                ['label' => 'Draw',                                   'pct' => $communityStats['draw'], 'color' => 'bg-gray-600'],
                ['label' => $match->awayTeam->short_name . ' Win',   'pct' => $communityStats['away'], 'color' => 'bg-red-600'],
            ] as $stat)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-400">{{ $stat['label'] }}</span>
                    <span class="text-white font-medium">{{ $stat['pct'] }}%</span>
                </div>
                <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                    <div class="{{ $stat['color'] }} h-full rounded-full transition-all duration-700"
                         style="width: {{ $stat['pct'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // ── Quick score picker ─────────────────────────────────────────────────
    $('.quick-score').on('click', function () {
        $('#home_score').val($(this).data('home'));
        $('#away_score').val($(this).data('away'));
        // Visual feedback
        $('.quick-score').removeClass('border-fifa-red/40 text-white bg-fifa-red/20');
        $(this).addClass('border-fifa-red/40 text-white bg-fifa-red/20');
    });

    // ── Prediction form AJAX submission ───────────────────────────────────
    $('#prediction-form').on('submit', function (e) {
        e.preventDefault();

        const homeScore = parseInt($('#home_score').val());
        const awayScore = parseInt($('#away_score').val());

        // Client-side validation
        if (isNaN(homeScore) || isNaN(awayScore) || homeScore < 0 || awayScore < 0) {
            $('#form-errors').text('Please enter valid scores (0 or higher for both teams).').removeClass('hidden');
            return;
        }

        $('#form-errors').addClass('hidden');
        $('#btn-spinner').removeClass('hidden');
        $('#btn-text').text('Saving...');
        $('#submit-btn').prop('disabled', true);

        $.ajax({
            url:    '{{ route('predictions.store') }}',
            method: 'POST',
            data: {
                match_id:   $('input[name="match_id"]').val(),
                home_score: homeScore,
                away_score: awayScore,
            },
            success: function (res) {
                if (res.success) {
                    showToast(res.message, 'success');
                    $('#btn-text').text('Update Prediction');
                    $('#submit-btn').prop('disabled', false);
                    $('#btn-spinner').addClass('hidden');
                } else {
                    showToast(res.message, 'error');
                    resetButton();
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                $('#form-errors').text(msg).removeClass('hidden');
                resetButton();
            }
        });
    });

    function resetButton() {
        $('#btn-spinner').addClass('hidden');
        $('#btn-text').text('Submit Prediction');
        $('#submit-btn').prop('disabled', false);
    }

    // ── Delete prediction ──────────────────────────────────────────────────
    $('#delete-btn').on('click', function () {
        if (!confirm('Remove your prediction for this match?')) return;

        const predictionId = $(this).data('prediction-id');

        $.ajax({
            url:    `/predictions/${predictionId}`,
            method: 'DELETE',
            success: function (res) {
                if (res.success) {
                    showToast(res.message, 'info');
                    setTimeout(() => location.reload(), 1200);
                }
            },
            error: function (xhr) {
                showToast(xhr.responseJSON?.message || 'Could not remove prediction.', 'error');
            }
        });
    });

});
</script>
@endpush