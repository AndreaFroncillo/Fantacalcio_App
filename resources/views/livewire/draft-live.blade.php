<div class="card shadow-sm p-4">
    @if($currentPlayer)
    <h3 class="text-center mb-3">{{ $currentPlayer['name'] }} ({{ $currentPlayer['position'] }})</h3>
    <p class="text-center">Crediti attuali: {{ $currentBid }}</p>
    <p class="text-center">Tempo rimasto: {{ $timer }}s</p>
    <p class="text-center">Ultimo rilancio di: {{ $highestBidder->name ?? 'Nessuno' }}</p>

    <div class="d-flex justify-content-center gap-2">
        <button wire:click="bid" class="btn btn-primary">Rilancia +1</button>
    </div>
    @else
    <h3 class="text-center">Asta completata!</h3>
    @endif

    <script>
        setInterval(() => {
            Livewire.emit('refreshDraft');
        }, 1000);
    </script>
</div>