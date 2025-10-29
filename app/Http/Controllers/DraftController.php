<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\DraftSession;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function showDraftPage(League $league)
    {
        return view('draft.show', compact('league'));
    }

    public function startDraft(Request $request, League $league)
    {
        $draft = DraftSession::create([
            'league_id' => $league->id,
            'name' => 'Mercato iniziale',
            'active' => true,
        ]);

        // aggiungi tutti i membri della lega con i crediti iniziali
        foreach ($league->users as $user) {
            $draft->users()->attach($user->id, ['remaining_credits' => $league->initial_credits]);
        }

        return redirect()->route('draft.show', $league)->with('success', 'Draft avviato!');
    }
}
