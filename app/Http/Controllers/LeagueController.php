<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeagueController extends Controller
{
    public function index()
    {
        $leagues = Auth::user()->leagues;
        return view('leagues.index', compact('leagues'));
    }

    public function create()
    {
        return view('leagues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_players' => 'required|integer|min:2|max:20',
            'initial_credits' => 'required|integer|min:100',
        ]);

        $league = League::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
            'max_players' => $request->max_players,
            'initial_credits' => $request->initial_credits,
            'invite_code' => strtoupper(Str::random(8)),
        ]);

        // aggiungi il creatore come membro admin
        $league->users()->attach(Auth::id(), [
            'is_admin' => true,
            'credits' => 500, // crediti iniziali
        ]);

        return redirect()->route('leagues.show', $league)->with('success', 'Lega creata con successo!');
    }

    public function show(League $league)
    {
        $league->load('teams');
        return view('leagues.show', compact('league'));
    }

    public function joinForm()
    {
        return view('leagues.join');
    }

    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|exists:leagues,invite_code',
        ]);

        $league = League::where('invite_code', $request->invite_code)->first();

        // Controlla se l’utente è già nella lega
        if ($league->users->contains(Auth::id())) {
            return redirect()->back()->with('error', 'Sei già in questa lega!');
        }

        // Controlla se la lega è piena
        if ($league->users()->count() >= $league->max_players) {
            return redirect()->back()->with('error', 'La lega è piena!');
        }

        // Aggiunge l’utente alla lega
        $league->users()->attach(Auth::id(), ['credits' => 500]);

        return redirect()->route('leagues.show', $league)
            ->with('success', 'Ti sei unito alla lega con successo!');
    }

    public function joinWithLink($invite_code)
    {
        $league = League::where('invite_code', $invite_code)->firstOrFail();
        return view('leagues.join', compact('league'));
    }
}
