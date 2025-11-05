<div class="container py-4">
    <div class="row">
        {{-- COLONNA SINISTRA - elenco giocatori --}}
        <div class="col-md-4 border-end">
            <h5 class="fw-bold mb-3 text-center">👥 Giocatori Disponibili</h5>
            <ul class="list-group">
                @foreach ($allPlayers as $player)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $player->name }} <small class="text-muted">({{ $player->position }})</small></span>
                        <button class="btn btn-sm btn-outline-primary" wire:click="selectPlayer({{ $player->id }})">
                            Chiama
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- COLONNA DESTRA - area asta --}}
        <div class="col-md-8">
            <div class="mb-4 text-center">
                <label for="roleFilter" class="form-label fw-semibold">Filtra per ruolo:</label>
                <select id="roleFilter" wire:model.live="selectedRole" class="form-select w-50 mx-auto">
                    <option value="all">Tutti</option>
                    <option value="P">Portieri</option>
                    <option value="D">Difensori</option>
                    <option value="C">Centrocampisti</option>
                    <option value="A">Attaccanti</option>
                </select>
            </div>

            <hr>

            @if ($currentPlayer)
                <div class="text-center mb-4">
                    <h3 class="fw-bold">
                        🎯 {{ $currentPlayer['name'] }}
                        <small class="text-muted">({{ $currentPlayer['position'] }})</small>
                    </h3>

                    <p class="mt-3">
                        Offerta più alta:
                        <strong>{{ $highestBid }}</strong> crediti
                        @if ($highestBidder)
                            da {{ \App\Models\User::find($highestBidder)->name }}
                        @else
                            <span class="text-muted">(nessuno)</span>
                        @endif
                    </p>
                </div>

                <div class="text-center mb-3">
                    <input type="number" min="1" wire:model.live="bidAmount" placeholder="Inserisci la tua offerta"
                        class="form-control w-25 d-inline-block text-center">
                    <button wire:click="placeBid({{ $bidAmount ?? 1 }})" class="btn btn-primary mx-2">
                        💰 Fai offerta
                    </button>
                    <button wire:click="rejectPlayer" class="btn btn-danger">
                        ❌ Rifiuta Giocatore
                    </button>
                </div>
            @else
                <h5 class="text-center text-muted mt-5">Nessun giocatore selezionato</h5>
            @endif

            {{-- Messaggi flash --}}
            @if (session()->has('success'))
                <div class="alert alert-success text-center mt-3">{{ session('success') }}</div>
            @elseif (session()->has('error'))
                <div class="alert alert-danger text-center mt-3">{{ session('error') }}</div>
            @elseif (session()->has('info'))
                <div class="alert alert-info text-center mt-3">{{ session('info') }}</div>
            @endif
        </div>
    </div>

    {{-- MODAL --}}
    @if ($showModal && $selectedPlayer)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <h4>⚽ Chiama Giocatore</h4>
                    <p class="mt-3">
                        Vuoi chiamare <strong>{{ $selectedPlayer->name }}</strong> ({{ $selectedPlayer->position }}) all'asta?
                    </p>
                    <div class="mt-4">
                        <button class="btn btn-success mx-2" wire:click="confirmSelection">✅ Chiama</button>
                        <button class="btn btn-secondary mx-2" wire:click="$set('showModal', false)">Annulla</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
