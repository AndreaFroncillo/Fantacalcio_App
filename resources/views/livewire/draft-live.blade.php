<div class="container-fluid py-4">
    <div class="row">
        {{-- COLONNA SINISTRA: Lista giocatori --}}
        <div class="col-md-4 border-end">
            <h4 class="text-center mb-3">📋 Lista Giocatori</h4>

            {{-- Barra di avanzamento --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between small">
                    <span>Giocatori rimanenti: {{ $remainingPlayers }}</span>
                    <span>Totale: {{ $totalPlayers }}</span>
                </div>
                @php
                $percent = $totalPlayers > 0 ? round(($totalPlayers - $remainingPlayers) / $totalPlayers * 100) : 0;
                @endphp
                <div class="progress" style="height: 10px;">
                    <div
                        class="progress-bar bg-success"
                        role="progressbar"
                        style='width: {{ $percent }}%;'
                        aria-valuenow="{{ $percent }}"
                        aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="text-center small mt-1">{{ $percent }}% completato</div>
            </div>


            {{-- Filtro Ruolo --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Filtra per ruolo:</label>
                <select wire:model.live="selectedRole" class="form-select">
                    <option value="all">Tutti</option>
                    <option value="P">Portieri</option>
                    <option value="D">Difensori</option>
                    <option value="C">Centrocampisti</option>
                    <option value="A">Attaccanti</option>
                </select>
            </div>

            {{-- Ricerca Nome --}}
            <div class="mb-3">
                <input
                    type="text"
                    wire:model.live="searchTerm"
                    class="form-control"
                    placeholder="Cerca per nome...">
            </div>

            {{-- Lista giocatori --}}
            <div class="list-group" style="max-height: 70vh; overflow-y: auto;">
                @forelse ($playersQueue as $player)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $player['name'] }} ({{ $player['position'] }})</span>
                    <button
                        class="btn btn-sm btn-outline-primary"
                        wire:click="openModal({{ $player['id'] }})">
                        Chiama
                    </button>
                </div>
                @empty
                <p class="text-muted text-center mt-3">Nessun giocatore disponibile</p>
                @endforelse
            </div>
        </div>

        {{-- COLONNA DESTRA: Asta live --}}
        <div class="col-md-8" id="auction-section">
            @if ($currentPlayer)
            <div class="text-center mb-4">
                <h3 class="fw-bold">
                    🎯 {{ $currentPlayer['name'] }}
                    <small class="text-muted">({{ $currentPlayer['position'] }})</small>
                </h3>

                <p>
                    Offerta più alta:
                    <strong>{{ $highestBid }}</strong> crediti
                    @if ($highestBidder)
                    da {{ \App\Models\User::find($highestBidder)->name }}
                    @else
                    <span class="text-muted">(nessuno)</span>
                    @endif
                </p>
            </div>

            {{-- Sezione Offerta --}}
            <div class="text-center mb-3">
                <input
                    type="number"
                    min="1"
                    wire:model.live="bidAmount"
                    placeholder="Inserisci la tua offerta"
                    class="form-control w-25 d-inline-block text-center">

                <button
                    wire:click="placeBid({{ $bidAmount ?? 1 }})"
                    class="btn btn-primary mx-2">
                    💰 Fai offerta
                </button>

                <button
                    wire:click="refusePlayer"
                    class="btn btn-danger">
                    ❌ Rifiuta Giocatore
                </button>
            </div>
            @else
            <h4 class="text-center text-success mt-5">🏁 Nessun giocatore in corso.</h4>
            @endif
        </div>
    </div>

    {{-- Modal per chiamare giocatore --}}
    @if ($showModal && $selectedPlayer)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chiama Giocatore</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Vuoi chiamare <strong>{{ $selectedPlayer->name }}</strong> ({{ $selectedPlayer->position }})?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary" wire:click="$set('showModal', false)">Annulla</button>
                    <button class="btn btn-success" wire:click="callPlayer">Chiama</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Script scroll automatico --}}
    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('scrollToAuction', () => {
                const section = document.getElementById('auction-section');
                if (section) {
                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</div>