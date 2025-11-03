<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Player;
use App\Models\League;
use Illuminate\Support\Facades\Auth;

class DraftLive extends Component
{
    public $bidAmount = 1;
    public $league;
    public $playersQueue = [];
    public $currentPlayer;
    public $bids = [];
    public $highestBid = 0;
    public $highestBidder;
    


    public function mount($league)
    {
        $this->league = $league;

        // Prendi i giocatori non ancora draftati
        $this->playersQueue = Player::where('drafted', false)->get()->toArray();

        $this->nextPlayer();
    }

    public function nextPlayer()
    {
        if (empty($this->playersQueue)) {
            $this->currentPlayer = null;
            return;
        }

        $this->currentPlayer = array_shift($this->playersQueue);
        $this->bids = [];
        $this->highestBid = 0;
        $this->highestBidder = null;
    }

    public function placeBid($amount)
    {
        $user = Auth::user();

        // Controllo crediti rimanenti (da aggiungere se usi attributi utente per i crediti)
        if ($amount <= $this->highestBid) return;

        $this->highestBid = $amount;
        $this->highestBidder = $user->id;
        $this->bids[$user->id] = $amount;
    }

    public function confirmPlayer()
    {
        if (!$this->highestBidder) return;

        // Aggiorna il giocatore come draftato e assegna all’utente
        $player = Player::find($this->currentPlayer['id']);
        $player->drafted = true;
        $player->user_id = $this->highestBidder;
        $player->save();

        // Passa al prossimo giocatore
        $this->nextPlayer();
    }

    public function render()
    {
        return view('livewire.draft-live');
    }
}
