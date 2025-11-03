<div>
    @if($currentPlayer)
    <h3>Giocatore: {{ $currentPlayer['name'] }} ({{ $currentPlayer['position'] }})</h3>

    <p>Offerta più alta: {{ $highestBid }} crediti da
        @if($highestBidder)
        {{ \App\Models\User::find($highestBidder)->name }}
        @else
        Nessuno
        @endif
    </p>

    <div class="mb-3">
        <input type="number" wire:model="bidAmount" placeholder="Inserisci la tua offerta">
        <button wire:click="placeBid({{ $bidAmount }})" class="btn btn-primary">Fai offerta</button>


    </div>

    <button wire:click="confirmPlayer" class="btn btn-success">Assegna Giocatore</button>
    @else
    <h4>Asta terminata!</h4>
    @endif
</div>