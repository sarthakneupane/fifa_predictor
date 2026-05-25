@extends('layouts.app')
@section('title', 'Manage Teams – Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 transition-colors">Admin</a>
                <span>/</span>
                <span class="text-gray-400">Teams</span>
            </nav>
            <h1 class="font-display text-5xl text-white tracking-wide">Manage Teams</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $teams->total() }} teams across all groups</p>
        </div>
        <a href="{{ route('admin.teams.create') }}"
           class="inline-flex items-center gap-2 bg-fifa-red hover:bg-red-700 text-white font-heading uppercase tracking-wider px-5 py-2.5 rounded-xl text-sm transition-all hover:scale-[1.02] self-start sm:self-auto">
            + Add Team
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-900/30 border border-green-700/50 text-green-300 text-sm px-4 py-3 rounded-xl mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/30 border border-red-700/50 text-red-300 text-sm px-4 py-3 rounded-xl mb-6">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Group filter tabs --}}
    @php
        $groups = \App\Models\Group::orderBy('letter')->get();
        $activeGroup = request('group_letter');
    @endphp
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.teams.index') }}"
           class="px-3 py-1.5 rounded-lg text-xs font-heading uppercase tracking-wider border transition-colors
               {{ !$activeGroup ? 'bg-fifa-red border-fifa-red text-white' : 'border-white/10 text-gray-500 hover:border-white/30 hover:text-gray-300' }}">
            All Groups
        </a>
        @foreach($groups as $group)
        <a href="{{ route('admin.teams.index', ['group_letter' => $group->letter]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-heading uppercase tracking-wider border transition-colors
               {{ $activeGroup === $group->letter ? 'bg-fifa-red border-fifa-red text-white' : 'border-white/10 text-gray-500 hover:border-white/30 hover:text-gray-300' }}">
            {{ $group->name }}
        </a>
        @endforeach
    </div>

    {{-- Teams grid --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-fifa-border bg-white/[0.02]">
                        <th class="text-left px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest w-12">#</th>
                        <th class="text-left px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Team</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Code</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden sm:table-cell">Group</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden md:table-cell">Confederation</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden lg:table-cell">Matches</th>
                        <th class="text-right px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-fifa-border/30">
                    @forelse($teams as $team)
                    <tr class="hover:bg-white/[0.025] transition-colors group">

                        {{-- ID --}}
                        <td class="px-5 py-4 text-gray-700 text-xs tabular-nums">{{ $team->id }}</td>

                        {{-- Team name + flag --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Flag (emoji or image) --}}
                                @if($team->flag)
                                    <img src="{{ Storage::url($team->flag) }}"
                                         class="w-8 h-6 object-cover rounded border border-fifa-border flex-shrink-0"
                                         alt="{{ $team->name }} flag">
                                @else
                                    <span class="text-2xl leading-none flex-shrink-0 select-none">{{ $team->flag_display }}</span>
                                @endif
                                <div>
                                    <div class="font-medium text-white">{{ $team->name }}</div>
                                    @if($team->flag_emoji && $team->flag)
                                        <div class="text-gray-700 text-xs mt-0.5">{{ $team->flag_emoji }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Short code --}}
                        <td class="px-4 py-4 text-center">
                            <span class="font-heading text-white text-sm tracking-widest bg-white/5 border border-white/10 px-2.5 py-1 rounded-lg">
                                {{ $team->short_name }}
                            </span>
                        </td>

                        {{-- Group --}}
                        <td class="px-4 py-4 text-center hidden sm:table-cell">
                            @if($team->group)
                                <span class="text-xs font-heading uppercase tracking-wider bg-blue-900/30 text-blue-400 border border-blue-900/50 px-2.5 py-1 rounded-full">
                                    {{ $team->group->name }}
                                </span>
                            @else
                                <span class="text-gray-700 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Confederation --}}
                        <td class="px-4 py-4 text-center text-gray-500 text-xs hidden md:table-cell">
                            @php
                                $confColors = [
                                    'UEFA'     => 'text-blue-400',
                                    'CONMEBOL' => 'text-yellow-400',
                                    'AFC'      => 'text-red-400',
                                    'CAF'      => 'text-green-400',
                                    'CONCACAF' => 'text-orange-400',
                                    'OFC'      => 'text-purple-400',
                                ];
                            @endphp
                            <span class="{{ $confColors[$team->confederation] ?? 'text-gray-500' }} font-heading text-xs">
                                {{ $team->confederation ?? '—' }}
                            </span>
                        </td>

                        {{-- Match count --}}
                        <td class="px-4 py-4 text-center text-gray-500 tabular-nums hidden lg:table-cell">
                            {{ $team->homeMatches->count() + $team->awayMatches->count() }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.teams.edit', $team) }}"
                                   class="text-xs text-blue-400 hover:text-blue-200 border border-blue-900/50 hover:border-blue-600 px-3 py-1.5 rounded-lg transition-colors font-heading uppercase tracking-wider">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="inline"
                                      onsubmit="return confirm('Delete {{ addslashes($team->name) }}? This will also affect related matches.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-600 hover:text-red-400 border border-red-900/30 hover:border-red-700 px-3 py-1.5 rounded-lg transition-colors font-heading uppercase tracking-wider">
                                        Del
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="text-5xl mb-4">🏴</div>
                            <p class="text-gray-600 font-heading uppercase tracking-wider text-sm">No teams found</p>
                            <a href="{{ route('admin.teams.create') }}"
                               class="inline-block mt-5 text-fifa-red hover:text-red-400 text-sm transition-colors">
                                Add the first team →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination + summary footer --}}
        @if($teams->hasPages() || $teams->count() > 0)
        <div class="px-6 py-4 border-t border-fifa-border bg-white/[0.01] flex flex-wrap items-center justify-between gap-4">
            @if($teams->hasPages())
                <div>{{ $teams->links() }}</div>
            @else
                <span class="text-gray-700 text-xs">Showing {{ $teams->count() }} teams</span>
            @endif
            <div class="flex items-center gap-4 text-xs text-gray-700">
                @foreach($confColors as $conf => $colorClass)
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $colorClass) }}"></span>
                        {{ $conf }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Quick stats --}}
    <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach($groups as $group)
        <div class="glass-card rounded-xl p-3 text-center">
            <div class="font-display text-xl text-white">{{ $group->teams->count() }}</div>
            <div class="text-gray-600 text-xs font-heading uppercase tracking-wider mt-0.5">{{ $group->name }}</div>
        </div>
        @endforeach
    </div>

</div>
@endsection
