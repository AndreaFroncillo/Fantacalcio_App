<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function create(League $league)
    {
        return view('teams.create', compact('league'));
    }

    public function store(Request $request, League $league)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Team::create([
            'user_id' => Auth::id(),
            'league_id' => $league->id,
            'name' => $request->name,
            'credits_remaining' => $league->initial_credits,
        ]);

        return redirect()->route('leagues.show', $league)->with('success', 'Squadra creata!');
    }
}
