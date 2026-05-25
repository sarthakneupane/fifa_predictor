<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount('predictions')
            ->withSum(['predictions as total_points' => fn($q) => $q->where('is_calculated', true)], 'points')
            ->orderByDesc('total_points')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        // Cannot remove own admin status
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot change your own admin status.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'granted' : 'revoked';
        return back()->with('success', "Admin access {$status} for {$user->name}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}