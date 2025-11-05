<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Player;
use App\Models\League;
use Illuminate\Support\Facades\Auth;

class DraftLive extends Component
{
    public League $league;
    public $playersQueue = [];
    public $allPlayers = [];
    public $currentPlayer;
    public $bids = [];
    public $highestBid = 0;
    public $highestBidder;
    public $selectedRole = 'all'; // filtro per ruolo
    public $showModal = false;
    public $selectedPlayer = null;
    public $rejectedPlayers = [];


    public function mount(League $league)
    {
        // Laravel passa automaticamente il modello League
        $this->league = $league->load('users');
        $this->loadPlayers();
    }

    public function loadPlayers()
    {
        $query = Player::where('drafted', false);

        if ($this->selectedRole !== 'all') {
            $query->where('position', $this->selectedRole);
        }

        $this->playersQueue = $query->get()->toArray();
        $this->allPlayers = Player::where('drafted', false)->get();
    }

    public function updatedSelectedRole()
    {
        $this->loadPlayers();
    }

    public function selectPlayer($playerId)
    {
        $this->selectedPlayer = Player::find($playerId);
        $this->showModal = true;
    }

    public function confirmSelection()
    {
        if ($this->selectedPlayer) {
            $this->currentPlayer = $this->selectedPlayer->toArray();
            $this->bids = [];
            $this->highestBid = 0;
            $this->highestBidder = null;
        }

        $this->showModal = false;
    }

    public function rejectPlayer()
    {
        $user = Auth::id();
        if (!$user || !$this->currentPlayer) return;

        $this->rejectedPlayers[$user][] = $this->currentPlayer['id'];
        session()->flash('info', 'Hai rifiutato questo giocatore.');

        $this->currentPlayer = null;
    }

    public function placeBid($amount)
    {
        $user = Auth::user();

        if (!$user) {
            session()->flash('error', 'Devi essere loggato per fare un\'offerta.');
            return;
        }

        if (isset($this->rejectedPlayers[$user->id]) && in_array($this->currentPlayer['id'], $this->rejectedPlayers[$user->id])) {
            session()->flash('error', 'Hai rifiutato questo giocatore.');
            return;
        }

        $pivot = $user->leagues()
            ->where('league_id', $this->league->id)
            ->first()?->pivot;

        if (!$pivot) {
            session()->flash('error', 'Non partecipi a questa lega.');
            return;
        }

        $credits = $pivot->credits;

        if ($amount > $credits) {
            session()->flash('error', 'Non hai abbastanza crediti.');
            return;
        }

        if ($amount <= $this->highestBid) {
            session()->flash('error', 'L\'offerta deve essere superiore.');
            return;
        }

        $this->highestBid = $amount;
        $this->highestBidder = $user->id;
        $this->bids[$user->id] = $amount;

        session()->flash('success', "Hai offerto {$amount} crediti!");
    }

    public function render()
    {
        return view('livewire.draft-live');
    }
}
