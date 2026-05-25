{{--
    Leaderboard table partial.
    Rendered on initial page load AND returned as JSON html on AJAX refresh.
    Variables: $leaderboard (Collection), $userRank (int|null)
--}}
<div class="overflow-x-auto" id="leaderboard-table">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-fifa-border bg-white/[0.02]">
                <th class="text-left px-4 sm:px-6 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest w-16">Rank</th>
                <th class="text-left px-4 sm:px-6 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Player</th>
                <th class="text-center px-3 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden sm:table-cell">Predicted</th>
                <th class="text-center px-3 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden md:table-cell">Correct</th>
                <th class="text-center px-3 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden lg:table-cell">Exact</th>
                <th class="text-center px-3 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden md:table-cell">Accuracy</th>
                <th class="text-right px-4 sm:px-6 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Points</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-fifa-border/40">
            @forelse($leaderboard as $user)
            @php
                $isCurrentUser = auth()->check() && auth()->id() === $user->id;
                $points = $user->total_points ?? 0;
            @endphp
            <tr class="lb-row hover:bg-white/[0.025] transition-colors
                {{ $isCurrentUser ? 'is-current-user bg-fifa-red/[0.06] border-l-2 border-l-fifa-red' : '' }}">

                {{-- Rank --}}
                <td class="px-4 sm:px-6 py-4 w-16">
                    @if($user->rank === 1)
                        <span class="text-2xl" title="1st Place">🥇</span>
                    @elseif($user->rank === 2)
                        <span class="text-2xl" title="2nd Place">🥈</span>
                    @elseif($user->rank === 3)
                        <span class="text-2xl" title="3rd Place">🥉</span>
                    @else
                        <span class="font-heading text-gray-500 text-sm tabular-nums">#{{ $user->rank }}</span>
                    @endif
                </td>

                {{-- Player --}}
                <td class="px-4 sm:px-6 py-4" data-name="{{ $user->name }}">
                    <div class="flex items-center gap-3">
                        <div class="relative flex-shrink-0">
                            <img src="{{ $user->avatar_url }}"
                                 class="w-9 h-9 rounded-full object-cover border
                                    {{ $isCurrentUser ? 'border-fifa-red' : 'border-fifa-border' }}"
                                 alt="{{ $user->name }}">
                            @if($user->rank <= 3)
                                <span class="absolute -bottom-1 -right-1 text-xs leading-none">
                                    @if($user->rank === 1)👑@elseif($user->rank === 2)⭐@else✨@endif
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="font-medium text-white text-sm truncate max-w-36 sm:max-w-none">
                                {{ $user->name }}
                                @if($isCurrentUser)
                                    <span class="text-fifa-red text-xs ml-1">(you)</span>
                                @endif
                            </div>
                            @if($user->country)
                                <div class="text-gray-600 text-xs truncate">{{ $user->country }}</div>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Predicted count --}}
                <td class="px-3 py-4 text-center text-gray-400 tabular-nums hidden sm:table-cell">
                    {{ $user->total_predictions_count }}
                </td>

                {{-- Correct --}}
                <td class="px-3 py-4 text-center hidden md:table-cell">
                    <span class="text-green-400 tabular-nums font-medium">{{ $user->correct_count }}</span>
                </td>

                {{-- Exact --}}
                <td class="px-3 py-4 text-center hidden lg:table-cell">
                    <span class="text-blue-400 tabular-nums">{{ $user->exact_count }}</span>
                </td>

                {{-- Accuracy --}}
                <td class="px-3 py-4 text-center hidden md:table-cell">
                    <div class="flex flex-col items-center gap-1">
                        <span class="font-medium
                            {{ $user->accuracy >= 70 ? 'text-green-400' : ($user->accuracy >= 40 ? 'text-yellow-400' : 'text-gray-500') }}">
                            {{ $user->accuracy }}%
                        </span>
                        {{-- Mini accuracy bar --}}
                        <div class="w-16 h-1 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700
                                {{ $user->accuracy >= 70 ? 'bg-green-500' : ($user->accuracy >= 40 ? 'bg-yellow-500' : 'bg-gray-600') }}"
                                 style="width: {{ $user->accuracy }}%"></div>
                        </div>
                    </div>
                </td>

                {{-- Points --}}
                <td class="px-4 sm:px-6 py-4 text-right">
                    <div class="flex flex-col items-end gap-0.5">
                        <span class="font-display text-2xl tabular-nums
                            {{ $user->rank === 1 ? 'text-fifa-gold' : ($user->rank <= 3 ? 'text-gray-200' : 'text-white') }}">
                            {{ $points }}
                        </span>
                        <span class="text-gray-700 text-xs font-heading uppercase tracking-wider">pts</span>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-16 text-center">
                    <div class="text-4xl mb-4">🏆</div>
                    <p class="text-gray-600 font-heading uppercase tracking-wider text-sm">
                        No predictors yet
                    </p>
                    <p class="text-gray-700 text-xs mt-2">
                        Be the first to predict a game and claim the top spot!
                    </p>
                    <a href="{{ route('games.index') }}"
                       class="inline-block mt-5 text-fifa-red hover:text-red-400 text-sm transition-colors">
                        Browse Games →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Table footer with legend (visible on mobile for hidden columns) --}}
    @if($leaderboard->count() > 0)
    <div class="px-6 py-4 border-t border-fifa-border bg-white/[0.01] flex flex-wrap items-center justify-between gap-3 text-xs text-gray-600">
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>Correct result
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>Exact score
            </span>
        </div>
        <span class="text-gray-700">{{ $leaderboard->count() }} players ranked</span>
    </div>
    @endif

</div>
{{-- /leaderboard-table --}}
