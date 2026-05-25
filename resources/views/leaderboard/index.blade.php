@extends('layouts.app')

@section('title', 'Leaderboard – FIFA World Cup 2026 Predictor')
@section('description', 'See who is leading the FIFA World Cup 2026 prediction competition. Rankings updated live after every match.')

@section('content')

{{-- ── Hero Bar ──────────────────────────────────────────────────────────── --}}
<div class="relative overflow-hidden bg-gradient-to-br from-[#0e0b00] via-fifa-dark to-[#000a1e] border-b border-fifa-border">
    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle at 20% 50%, #F5A623 0%, transparent 50%), radial-gradient(circle at 80% 50%, #003087 0%, transparent 50%);"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
        <div class="text-5xl mb-4">🏆</div>
        <h1 class="font-display text-6xl sm:text-7xl text-white tracking-wide">LEADERBOARD</h1>
        <p class="text-gray-400 mt-3 text-base">Global rankings — updated after every match result</p>

        {{-- Current user's rank pill --}}
        @auth
            @if($userRank)
            <div class="mt-6 inline-flex items-center gap-3 bg-fifa-red/10 border border-fifa-red/30 text-white px-5 py-2.5 rounded-full text-sm">
                <span class="text-gray-400">Your rank:</span>
                <span class="font-display text-2xl text-fifa-gold">#{{ $userRank }}</span>
            </div>
            @endif
        @endauth
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Podium (Top 3) ───────────────────────────────────────────────── --}}
    @if($leaderboard->count() >= 3)
    <div class="mb-10">
        <div class="flex items-end justify-center gap-4 sm:gap-6">

            {{-- 2nd Place --}}
            @php $second = $leaderboard->get(1); @endphp
            <div class="flex flex-col items-center gap-3 text-center flex-1 max-w-48">
                <img src="{{ $second->avatar_url }}" class="w-16 h-16 rounded-full border-2 border-gray-400 object-cover" alt="{{ $second->name }}">
                <div>
                    <div class="text-3xl mb-1">🥈</div>
                    <div class="font-medium text-white text-sm truncate max-w-36">{{ $second->name }}</div>
                    <div class="font-display text-2xl text-gray-300 mt-1">{{ $second->total_points ?? 0 }}</div>
                    <div class="text-gray-600 text-xs">pts</div>
                </div>
                <div class="w-full bg-gradient-to-t from-gray-700/60 to-gray-600/30 border-t-2 border-gray-500 rounded-t-lg h-20 flex items-end justify-center pb-2">
                    <span class="font-display text-gray-400 text-4xl">2</span>
                </div>
            </div>

            {{-- 1st Place --}}
            @php $first = $leaderboard->get(0); @endphp
            <div class="flex flex-col items-center gap-3 text-center flex-1 max-w-52 -translate-y-4">
                <div class="relative">
                    <img src="{{ $first->avatar_url }}" class="w-20 h-20 rounded-full border-4 border-yellow-400 object-cover shadow-lg shadow-yellow-400/20" alt="{{ $first->name }}">
                    <span class="absolute -top-2 -right-2 text-2xl">👑</span>
                </div>
                <div>
                    <div class="text-4xl mb-1">🥇</div>
                    <div class="font-semibold text-white text-base truncate max-w-40">{{ $first->name }}</div>
                    <div class="font-display text-3xl text-fifa-gold mt-1">{{ $first->total_points ?? 0 }}</div>
                    <div class="text-gray-500 text-xs">pts</div>
                    <div class="text-green-400 text-xs mt-0.5">{{ $first->accuracy }}% accuracy</div>
                </div>
                <div class="w-full bg-gradient-to-t from-yellow-800/50 to-yellow-700/20 border-t-2 border-yellow-500 rounded-t-lg h-28 flex items-end justify-center pb-2">
                    <span class="font-display text-yellow-600/60 text-5xl">1</span>
                </div>
            </div>

            {{-- 3rd Place --}}
            @php $third = $leaderboard->get(2); @endphp
            <div class="flex flex-col items-center gap-3 text-center flex-1 max-w-48">
                <img src="{{ $third->avatar_url }}" class="w-16 h-16 rounded-full border-2 border-orange-600 object-cover" alt="{{ $third->name }}">
                <div>
                    <div class="text-3xl mb-1">🥉</div>
                    <div class="font-medium text-white text-sm truncate max-w-36">{{ $third->name }}</div>
                    <div class="font-display text-2xl text-orange-400 mt-1">{{ $third->total_points ?? 0 }}</div>
                    <div class="text-gray-600 text-xs">pts</div>
                </div>
                <div class="w-full bg-gradient-to-t from-orange-900/50 to-orange-800/20 border-t-2 border-orange-700 rounded-t-lg h-14 flex items-end justify-center pb-2">
                    <span class="font-display text-orange-700/60 text-4xl">3</span>
                </div>
            </div>

        </div>
    </div>
    @endif

    {{-- ── Controls: Refresh + Search ───────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="font-heading text-white text-xl uppercase tracking-wider">Full Rankings</h2>
            <p class="text-gray-600 text-xs mt-0.5">{{ $leaderboard->count() }} registered predictors</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search --}}
            <input type="text" id="lb-search" placeholder="🔍 Search player…"
                   class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-white text-sm placeholder-gray-600 focus:border-fifa-red outline-none transition-colors w-48">

            {{-- Refresh --}}
            <button id="lb-refresh"
                    class="flex items-center gap-2 border border-white/10 hover:border-fifa-red/40 text-gray-400 hover:text-white px-4 py-2 rounded-xl text-sm font-heading uppercase tracking-wider transition-colors">
                <svg id="refresh-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    {{-- ── Table ─────────────────────────────────────────────────────────── --}}
    <div id="leaderboard-container" class="glass-card rounded-2xl overflow-hidden">
        @include('leaderboard.partials.table', ['leaderboard' => $leaderboard, 'userRank' => $userRank])
    </div>

    {{-- ── Legend ────────────────────────────────────────────────────────── --}}
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="glass-card rounded-xl p-4 text-center">
            <div class="font-display text-2xl text-green-400">+3</div>
            <div class="text-gray-500 text-xs font-heading uppercase tracking-wider mt-1">Exact Score Prediction</div>
        </div>
        <div class="glass-card rounded-xl p-4 text-center">
            <div class="font-display text-2xl text-yellow-500">+1</div>
            <div class="text-gray-500 text-xs font-heading uppercase tracking-wider mt-1">Correct Result (W/D/L)</div>
        </div>
        <div class="glass-card rounded-xl p-4 text-center">
            <div class="font-display text-2xl text-red-700">+0</div>
            <div class="text-gray-500 text-xs font-heading uppercase tracking-wider mt-1">Wrong Prediction</div>
        </div>
    </div>

    {{-- ── CTA for guests ────────────────────────────────────────────────── --}}
    @guest
    <div class="mt-8 glass-card rounded-2xl p-8 text-center border border-fifa-red/20">
        <div class="text-4xl mb-4">🚀</div>
        <h3 class="font-heading text-white text-xl uppercase tracking-wider mb-2">Join the Competition</h3>
        <p class="text-gray-500 text-sm mb-6 max-w-md mx-auto">
            Sign up free, predict every World Cup match, and see your name on this leaderboard!
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-8 py-3 rounded-xl transition-all hover:scale-[1.02] text-sm">
                Sign Up Free
            </a>
            <a href="{{ route('login') }}"
               class="border border-white/20 text-white hover:border-white/40 font-heading uppercase tracking-wider px-8 py-3 rounded-xl transition-colors text-sm">
                Log In
            </a>
        </div>
    </div>
    @endguest

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // ── Client-side search filter ──────────────────────────────────────────
    let searchTimer;
    $('#lb-search').on('input', function () {
        clearTimeout(searchTimer);
        const query = $(this).val().toLowerCase().trim();
        searchTimer = setTimeout(() => {
            let shown = 0;
            $('#leaderboard-table tbody tr.lb-row').each(function () {
                const name = $(this).find('[data-name]').data('name').toLowerCase();
                const match = query === '' || name.includes(query);
                $(this).toggleClass('hidden', !match);
                if (match) shown++;
            });

            // Empty state
            const $empty = $('#lb-empty');
            if (shown === 0 && query !== '') {
                if ($empty.length === 0) {
                    $('#leaderboard-table tbody').append(
                        '<tr id="lb-empty"><td colspan="7" class="py-10 text-center text-gray-600 text-sm">No players found matching "' + query + '"</td></tr>'
                    );
                }
            } else {
                $empty.remove();
            }
        }, 200);
    });

    // ── AJAX refresh leaderboard ───────────────────────────────────────────
    $('#lb-refresh').on('click', function () {
        const $btn  = $(this);
        const $icon = $('#refresh-icon');

        $btn.prop('disabled', true);
        $icon.addClass('animate-spin');

        $.ajax({
            url:     '{{ route("leaderboard.index") }}',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (res) {
                $('#leaderboard-container').html(res.html);
                showToast('Leaderboard refreshed!', 'success');
                // Re-run any active search
                if ($('#lb-search').val()) $('#lb-search').trigger('input');
            },
            error: function () {
                showToast('Could not refresh leaderboard.', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false);
                $icon.removeClass('animate-spin');
            }
        });
    });

    // ── Auto-scroll to current user's row ─────────────────────────────────
    const $myRow = $('.lb-row.is-current-user');
    if ($myRow.length) {
        setTimeout(() => {
            $myRow[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 600);
    }

});
</script>
@endpush
