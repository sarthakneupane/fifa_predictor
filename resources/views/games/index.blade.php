@extends('layouts.app')

@section('title', 'All Matches – FIFA 2026 Predictor')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="font-display text-5xl text-white tracking-wide">All Matches</h1>
        <p class="text-gray-500 mt-2">Browse and predict every FIFA World Cup 2026 match</p>
    </div>

    {{-- ── Filters ──────────────────────────────────────────────────────── --}}
    <div class="glass-card rounded-2xl p-5 mb-8">
        <form id="filter-form" class="flex flex-col sm:flex-row gap-4 flex-wrap">
            {{-- Search --}}
            <div class="flex-1 min-w-48">
                <input type="text" name="search" placeholder="🔍 Search team..." value="{{ request('search') }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-600 focus:border-fifa-red outline-none transition-colors">
            </div>

            {{-- Stage filter --}}
            <select name="stage" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-gray-300 text-sm focus:border-fifa-red outline-none transition-colors">
                <option value="">All Stages</option>
                @foreach($stages as $stage)
                    <option value="{{ $stage }}" {{ request('stage') === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                @endforeach
            </select>

            {{-- Group filter --}}
            <select name="group_id" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-gray-300 text-sm focus:border-fifa-red outline-none transition-colors">
                <option value="">All Groups</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select name="status" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-gray-300 text-sm focus:border-fifa-red outline-none transition-colors">
                <option value="">All Status</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="live"     {{ request('status') === 'live'     ? 'selected' : '' }}>Live</option>
                <option value="completed"{{ request('status') === 'completed'? 'selected' : '' }}>Completed</option>
            </select>

            <button type="submit" class="bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-5 py-2.5 rounded-xl text-sm transition-colors">
                Filter
            </button>
            <a href="{{ route('games.index') }}" class="border border-white/10 text-gray-400 hover:text-white font-heading uppercase tracking-wider px-5 py-2.5 rounded-xl text-sm transition-colors text-center">
                Reset
            </a>
        </form>
    </div>

    {{-- ── game Grid ───────────────────────────────────────────────────── --}}
    <div id="games-container">
        @if($games->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="game-grid">
                @foreach($games as $game)
                    @include('partials.game-card', ['game' => $game, 'userPredictions' => $userPredictions])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8" id="pagination-container">
                {{ $games->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-6xl mb-4">⚽</div>
                <h3 class="font-heading text-gray-500 uppercase tracking-wide text-xl">No games found</h3>
                <p class="text-gray-700 mt-2 text-sm">Try adjusting your filters</p>
                <a href="{{ route('games.index') }}" class="mt-6 inline-block text-fifa-red hover:text-red-400 text-sm transition-colors">Clear all filters</a>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
// Live filter without page reload
let filterTimeout;
$('#filter-form select').on('change', function() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(submitFilter, 300);
});

$('#filter-form input[name="search"]').on('input', function() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(submitFilter, 500);
});

$('#filter-form').on('submit', function(e) {
    e.preventDefault();
    submitFilter();
});

function submitFilter() {
    const params = new URLSearchParams($('#filter-form').serialize());

    // Update URL without reload
    window.history.pushState({}, '', '?' + params.toString());

    $('#games-container').css('opacity', '0.5');

    $.ajax({
        url:      '{{ route("games.index") }}',
        data:     params.toString(),
        headers:  { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(res) {
            $('#game-grid').html(res.html);
            $('#pagination-container').html(res.pagination);
            $('#games-container').css('opacity', '1');
            initCountdowns(); // re-init countdown timers
        },
        error: function() {
            $('#games-container').css('opacity', '1');
        }
    });
}
</script>
@endpush