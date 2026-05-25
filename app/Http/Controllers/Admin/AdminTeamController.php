<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('group')->orderBy('name')->paginate(24);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        $groups = Group::orderBy('letter')->get();
        return view('admin.teams.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'short_name'    => 'required|string|max:3|uppercase',
            'flag_emoji'    => 'nullable|string|max:10',
            'group_id'      => 'required|exists:groups,id',
            'confederation' => 'nullable|string|max:20',
            'flag'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('flag')) {
            $validated['flag'] = $request->file('flag')->store('flags', 'public');
        }

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team added!');
    }

    public function edit(Team $team)
    {
        $groups = Group::orderBy('letter')->get();
        return view('admin.teams.edit', compact('team', 'groups'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'short_name'    => 'required|string|max:3',
            'flag_emoji'    => 'nullable|string|max:10',
            'group_id'      => 'required|exists:groups,id',
            'confederation' => 'nullable|string|max:20',
            'flag'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('flag')) {
            if ($team->flag) Storage::disk('public')->delete($team->flag);
            $validated['flag'] = $request->file('flag')->store('flags', 'public');
        }

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team updated!');
    }

    public function destroy(Team $team)
    {
        if ($team->flag) Storage::disk('public')->delete($team->flag);
        $team->delete();
        return redirect()->route('admin.teams.index')
            ->with('success', 'Team deleted.');
    }
}