<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Player;
use App\Models\League;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DraftLive extends Component
{
    public League $league;
    public $playersQueue = [];
    public $currentPlayer;
    public $bids = [];
    public $highestBid = 0;
    public $highestBidder;
    public $selectedRole = 'all';
    public $searchTerm = '';
    public $showModal = false;
    public $selectedPlayer = null;
    public $totalPlayers = 0;
    public $remainingPlayers = 0;

    public function mount(League $league)
    {
        $this->league = $league->load('users');
        $this->loadPlayers();
    }

    public function updatedSelectedRole()
    {
        $this->loadPlayers();
    }

    public function updatedSearchTerm()
    {
        $this->loadPlayers();
    }

    public function loadPlayers()
    {
        $this->totalPlayers = Player::count(); // tutti i giocatori
        $query = Player::where('drafted', false);

        if ($this->selectedRole !== 'all') {
            $query->where('position', $this->selectedRole);
        }

        if (!empty($this->searchTerm)) {
            $term = Str::lower($this->normalize($this->searchTerm));
            $query->whereRaw('LOWER(REPLACE(name, "à", "a")) LIKE ?', ["%{$term}%"]);
        }

        $players = $query->orderBy('name')->get();
        $this->playersQueue = $players->toArray();
        $this->remainingPlayers = $players->count(); // rimasti
    }

    public function normalize($string)
    {
        // Rimuove accenti e converte in minuscolo
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        return preg_replace('/[^A-Za-z0-9 ]/', '', strtolower($string));
    }

    public function openModal($playerId)
    {
        $this->selectedPlayer = Player::find($playerId);
        $this->showModal = true;
    }

    public function callPlayer()
    {
        if (!$this->selectedPlayer) return;

        $this->currentPlayer = $this->selectedPlayer->toArray();
        $this->bids = [];
        $this->highestBid = 0;
        $this->highestBidder = null;

        // Rimuovi giocatore dalla lista
        $this->selectedPlayer->drafted = true;
        $this->selectedPlayer->save();

        $this->loadPlayers();

        // Chiudi modal e scrolla alla sezione offerte
        $this->showModal = false;
        $this->dispatch('scrollToAuction');
    }

    public function placeBid($amount)
    {
        $user = Auth::user();

        if (!$user) {
            session()->flash('error', 'Devi essere loggato per fare un\'offerta.');
            return;
        }

        $pivot = $user->leagues()->where('league_id', $this->league->id)->first()?->pivot;

        if (!$pivot) {
            session()->flash('error', 'Non partecipi a questa lega.');
            return;
        }

        if ($amount > $pivot->credits) {
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

    public function refusePlayer()
    {
        $this->currentPlayer = null;
        $this->bids = [];
        $this->highestBid = 0;
        $this->highestBidder = null;
    }

    public function render()
    {
        return view('livewire.draft-live');
    }
}
