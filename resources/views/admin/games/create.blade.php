@extends('layouts.app')
@section('title', (isset($game) ? 'Edit game' : 'New Game') . ' – Admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.games.index') }}" class="text-gray-500 hover:text-white transition-colors text-sm">← Back</a>
        <h1 class="font-display text-4xl text-white tracking-wide">
            {{ isset($game) ? 'Edit Game' : 'New Game' }}
        </h1>
    </div>

    <form method="POST"
          action="{{ isset($game) ? route('admin.games.update', $game) : route('admin.games.store') }}"
          class="glass-card rounded-2xl p-6 sm:p-8 space-y-6">
        @csrf
        @if(isset($game)) @method('PUT') @endif

        @if($errors->any())
        <div class="bg-red-900/30 border border-red-700 rounded-xl p-4">
            <ul class="text-red-400 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Teams --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Home Team *</label>
                <select name="home_team_id" required
                        class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    <option value="">Select team…</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('home_team_id', $game->home_team_id ?? '') == $team->id ? 'selected' : '' }}>
                            {{ $team->flag_emoji }} {{ $team->name }} ({{ $team->short_name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Away Team *</label>
                <select name="away_team_id" required
                        class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    <option value="">Select team…</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('away_team_id', $game->away_team_id ?? '') == $team->id ? 'selected' : '' }}>
                            {{ $team->flag_emoji }} {{ $team->name }} ({{ $team->short_name }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Stage & Group --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Stage *</label>
                <select name="stage" required
                        class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    @foreach(['Group Stage','Round of 32','Round of 16','Quarter Final','Semi Final','Third Place','Final'] as $stage)
                    <option value="{{ $stage }}" {{ old('stage', $game->stage ?? 'Group Stage') === $stage ? 'selected' : '' }}>
                        {{ $stage }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Group</label>
                <select name="group_id"
                        class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    <option value="">No group (knockout)</option>
                    @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id', $game->group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Date + Deadline --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Game Date & Time (UTC) *</label>
                <input type="datetime-local" name="game_date" required
                       value="{{ old('game_date', isset($game) ? $game->game_date->format('Y-m-d\TH:i') : '') }}"
                       class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Prediction Deadline *</label>
                <input type="datetime-local" name="prediction_deadline" required
                       value="{{ old('prediction_deadline', isset($game) ? $game->prediction_deadline->format('Y-m-d\TH:i') : '') }}"
                       class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                <p class="text-gray-700 text-xs mt-1">Usually same as kick-off</p>
            </div>
        </div>

        {{-- Stadium & City --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Stadium</label>
                <input type="text" name="stadium"
                       value="{{ old('stadium', $game->stadium ?? '') }}"
                       placeholder="e.g. MetLife Stadium, New York"
                       class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors placeholder-gray-700">
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">City</label>
                <input type="text" name="city"
                       value="{{ old('city', $game->city ?? '') }}"
                       class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
            </div>
        </div>

        {{-- Game Number --}}
        <div>
            <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Game Number</label>
            <input type="text" name="game_number"
                   value="{{ old('game_number', $game->game_number ?? '') }}"
                   placeholder="e.g. Game 1"
                   class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors placeholder-gray-700">
        </div>

        {{-- Score & Status (edit only) --}}
        @isset($game)
        <div class="border-t border-fifa-border pt-6">
            <h3 class="font-heading text-white uppercase tracking-wider text-sm mb-4">🏆 Game Result</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Home Score</label>
                    <input type="number" name="home_score" min="0" max="20"
                           value="{{ old('home_score', $game->home_score) }}"
                           class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Away Score</label>
                    <input type="number" name="away_score" min="0" max="20"
                           value="{{ old('away_score', $game->away_score) }}"
                           class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-heading uppercase tracking-wider mb-2">Status *</label>
                    <select name="status" required
                            class="w-full bg-white/5 border border-white/10 focus:border-fifa-red rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                        @foreach(['upcoming','live','completed'] as $status)
                        <option value="{{ $status }}" {{ old('status', $game->status) === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <p class="text-gray-600 text-xs mt-3">
                ⚠️ Setting status to <strong class="text-gray-400">Completed</strong> with scores will automatically calculate points for all predictions.
            </p>
        </div>
        @endisset

        {{-- Submit --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="flex-1 bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider py-3.5 rounded-xl transition-all hover:scale-[1.01] text-sm">
                {{ isset($game) ? '💾 Save Changes' : '➕ Create Game' }}
            </button>
            <a href="{{ route('admin.games.index') }}"
               class="px-5 py-3.5 border border-white/10 hover:border-white/30 text-gray-400 hover:text-white rounded-xl text-sm font-heading uppercase tracking-wider transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
