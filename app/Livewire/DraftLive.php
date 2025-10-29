<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Player;
use Livewire\Component;
use App\Models\DraftSession;
use Illuminate\Support\Facades\Auth;

class DraftLive extends Component
{
    public DraftSession $draft;
    public $currentPlayer;
    public $currentBid = 1;
    public $highestBidder;
    public $timer = 30;
    public $playersQueue;

    protected $listeners = ['refreshDraft' => '$refresh'];

    public function mount(DraftSession $draft)
    {
        $this->playersQueue = Player::where('drafted', false)->get()->toArray();
        $this->currentPlayer = $this->playersQueue[0] ?? null;
    }

    public function bid()
    {
        $userId = Auth::id();
        $userPivot = $this->draft->users()->where('user_id', $userId)->first();

        if ($userPivot->pivot->remaining_credits < ($this->currentBid + 1)) {
            session()->flash('error', 'Non hai crediti sufficienti!');
            return;
        }

        $this->currentBid++;
        $this->highestBidder = Auth::user();
        $this->resetTimer();
        $this->emitSelf('refreshDraft');
    }

    public function resetTimer()
    {
        $this->timer = 30;
    }

    public function tick()
    {
        if ($this->timer > 0) {
            $this->timer--;
        } else {
            $this->assignPlayer();
        }
    }

    public function assignPlayer()
    {
        if ($this->highestBidder) {
            // Aggiorna crediti dell’utente
            $pivot = $this->draft->users()->where('user_id', $this->highestBidder->id)->first();
            $pivot->pivot->remaining_credits -= $this->currentBid;
            $pivot->pivot->save();

            // Marca giocatore come draftato
            $player = Player::find($this->currentPlayer['id']);
            $player->drafted = true;
            $player->user_id = $this->highestBidder->id;
            $player->save();
        }

        // Passa al prossimo giocatore
        $this->playersQueue = array_slice($this->playersQueue, 1);
        $this->currentPlayer = $this->playersQueue[0] ?? null;
        $this->currentBid = 1;
        $this->highestBidder = null;
        $this->resetTimer();
    }
    
    public function render()
    {
        return view('livewire.draft-live');
    }
}
