@extends('layouts.app')
@section('title', 'Manage Users – Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-4xl text-white tracking-wide">Users</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $users->total() }} registered accounts</p>
        </div>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-fifa-border bg-white/[0.02]">
                        <th class="text-left px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">User</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden sm:table-cell">Verified</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden md:table-cell">Predictions</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest hidden md:table-cell">Points</th>
                        <th class="text-center px-4 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Role</th>
                        <th class="text-right px-5 py-4 text-gray-500 font-heading uppercase text-xs tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-fifa-border/30">
                    @foreach($users as $user)
                    <tr class="hover:bg-white/[0.02] transition-colors {{ $user->is_admin ? 'bg-yellow-950/10' : '' }}">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover border border-fifa-border" alt="">
                                <div>
                                    <div class="font-medium text-white text-sm">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="text-fifa-red text-xs ml-1">(you)</span>
                                        @endif
                                    </div>
                                    <div class="text-gray-600 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center hidden sm:table-cell">
                            @if($user->email_verified_at)
                                <span class="text-green-400 text-xs">✅ Yes</span>
                            @else
                                <span class="text-red-500 text-xs">❌ No</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-gray-400 tabular-nums hidden md:table-cell">
                            {{ $user->predictions_count }}
                        </td>
                        <td class="px-4 py-4 text-center hidden md:table-cell">
                            <span class="font-display text-lg text-fifa-gold tabular-nums">{{ $user->total_points ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($user->is_admin)
                                <span class="text-xs bg-yellow-700/30 text-yellow-400 border border-yellow-700/50 px-2.5 py-1 rounded-full font-heading uppercase tracking-wider">
                                    Admin
                                </span>
                            @else
                                <span class="text-xs bg-white/5 text-gray-500 border border-white/10 px-2.5 py-1 rounded-full font-heading uppercase tracking-wider">
                                    User
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($user->id !== auth()->id())
                                    {{-- Toggle admin --}}
                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-xs border px-2.5 py-1 rounded-lg font-heading uppercase tracking-wider transition-colors
                                                    {{ $user->is_admin
                                                        ? 'text-yellow-600 border-yellow-800 hover:border-yellow-600'
                                                        : 'text-gray-500 border-white/10 hover:border-white/30 hover:text-gray-300' }}"
                                                title="{{ $user->is_admin ? 'Remove admin' : 'Make admin' }}">
                                            {{ $user->is_admin ? 'Revoke' : 'Admin' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Delete {{ $user->name }} and all their predictions? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-xs text-red-600 hover:text-red-400 border border-red-900/30 hover:border-red-700 px-2.5 py-1 rounded-lg transition-colors font-heading uppercase tracking-wider">
                                            Del
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-700 text-xs">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-5 border-t border-fifa-border">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
